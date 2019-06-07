<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditLogController;
use App\MembershipType;
use App\User;
use App\UserGroup;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user_group = Auth::user()->user_group;

            $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
            $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
            $modules_access = explode(',', $getModuleAccess);
            $modules_permissions = explode(',', $getModulePerms);

            $key = array_search('7', $modules_access);
            if (strpos($modules_permissions[$key], 'r') !== false) {
                return $next($request);
            }

            return redirect('/users');

        }, ['only' => ['show']]);

        $this->middleware(function ($request, $next) {
            $user_group = Auth::user()->user_group;

            $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
            $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
            $modules_access = explode(',', $getModuleAccess);
            $modules_permissions = explode(',', $getModulePerms);

            $key = array_search('7', $modules_access);
            if (strpos($modules_permissions[$key], 'u') !== false) {
                return $next($request);
            }

            return redirect('/users');

        }, ['only' => ['edit']]);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($request->has('per_page')) {
            $per_page = $request->input('per_page');
        } else {
            $per_page = 10;
        }

        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $dir = $request->input('dir');
        } else {
            $sort = 'created_at';
            $dir = 'desc';
        }

        $user_groups = UserGroup::get();

        $users = DB::table('users')
            ->select('users.*', 'user_groups.user_group_name')
            ->join('user_groups', 'user_groups.id', 'users.user_group')
            ->where('first_name', 'like', $search . '%')
            ->Orwhere('middle_name', 'like', $search . '%')
            ->Orwhere('last_name', 'like', $search . '%')
            ->orWhere('username', 'like', $search . '%')
            ->orderBy($sort, $dir)
            ->paginate($per_page);

        $user_group = Auth::user()->user_group;

        $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
        $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
        $modules_access = explode(',', $getModuleAccess);
        $modules_permissions = explode(',', $getModulePerms);

        $key = array_search('7', $modules_access);
        if (strpos($modules_permissions[$key], 'c') !== false) {
            $canCreate = true;
        } else {
            $canCreate = false;
        }

        $membership_types = MembershipType::get();

        return view('users.index', compact('user_groups', 'users', 'search', 'per_page', 'canCreate', 'membership_types'));
    }

    public function show(User $user)
    {
        $user_group = UserGroup::where('id', $user->user_group)->first();
        return view('users.show', compact('user', 'user_group'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users|alpha_num|max:255',
            'user_group' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'middle_name' => 'nullable|regex:/^[\pL\s\-]+$/u|max:255',
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'email' => 'required|email|unique:users|unique:emails,email_address|max:255',
            'department' => 'required',
            'canChangePortal' => 'required',
            'status' => 'required',
        ]);

        $user = new User;

        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->user_group = $request->user_group;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->department = $request->department;
        $user->can_change_portal = $request->canChangePortal;

        if ($request->canChangePortal == '0') {
            $user->portal_type = $request->portal_type;
        } else {
            $user->portal_type = 1;
        }

        $user->status = $request->status;
        $user->created_by = Auth::user()->username;

        $user->save();

        $auditLog = new AuditLogController;
        $description = 'created user: ' . $user->id;
        $auditLog->store($description, 7, $request->post());

        return redirect('/users')->with('message', 'User has been added.');
    }

    public function edit(User $user)
    {
        $user_group = UserGroup::where('id', $user->user_group)->first();
        $user_groups = UserGroup::get();
        $membership_types = MembershipType::get();

        return view('users.edit', compact('user', 'user_group', 'user_groups', 'membership_types'));
    }

    public function update(Request $request)
    {

        $request->validate([
            'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'middle_name' => 'nullable|regex:/^[\pL\s\-]+$/u|max:255',
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'user_group' => 'required',
            'canChangePortal' => 'required',
            'status' => 'required',
            'username' => 'required|alpha_num|max:255|unique:emails,email_address|unique:users,username,' . $request->id,
            'department' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $request->id . '|unique:emails,email_address',
        ]);

        $user = User::find($request->id);

        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->user_group = $request->user_group;
        $user->username = $request->username;
        $user->department = $request->department;
        $user->can_change_portal = $request->canChangePortal;

        if ($request->canChangePortal == '0') {
            $user->portal_type = $request->portal_type;
        }

        $user->status = $request->status;
        $user->updated_by = Auth::user()->username;

        $user->save();

        $auditLog = new AuditLogController;
        $description = 'updated user: ' . $request->id;
        $auditLog->store($description, 7, $request->post());

        return redirect('/users/' . $request->id)->with('message', 'User has been successfully updated.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find($request->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/users/' . $request->id)->with('message', 'User\'s password has been successfully updated.');
    }

    public function getUserList()
    {
        $users = DB::table('users')
            ->select('users.*', 'user_groups.user_group_name')
            ->join('user_groups', 'user_groups.id', '=', 'users.user_group')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return \Response::json($users, 200);
    }

    public function searchUser($search)
    {
        $users = DB::table('users')
            ->select('users.*', 'user_groups.user_group_name')
            ->join('user_groups', 'user_groups.id', '=', 'users.user_group')
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('username', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return \Response::json($users, 200);
    }

    public function sort($col, $dir)
    {
        $portal = session('portal');

        $users = DB::table('users')
            ->select('users.*', 'user_groups.user_group_name')
            ->join('user_groups', 'user_groups.id', '=', 'users.user_group')
            ->orderBy($col, $dir)
            ->paginate(10);

        return \Response::json($users, 200);
    }

    public function changePassword()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        return view('users.change_password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|is_correct_password',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect('/dashboard')->with('message', 'Your password has been changed.');
    }
}
