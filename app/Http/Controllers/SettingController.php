<?php

namespace App\Http\Controllers;

use App\AuditLog;
use App\MembershipType;
use App\Module;
use App\User;
use App\UserGroup;
use Auth;
use DB;
use Illuminate\Http\Request;

class SettingController extends Controller
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

            $key = array_search('10', $modules_access);
            if (strpos($modules_permissions[$key], 'u') !== false) {
                return $next($request);
            }

            return redirect('/settings');

        }, ['only' => ['editGroup', 'editMemberType']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usergroups = UserGroup::get();
        $membershiptypes = DB::table('membership_types')->get();

        $user_group = Auth::user()->user_group;

        $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
        $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
        $modules_access = explode(',', $getModuleAccess);
        $modules_permissions = explode(',', $getModulePerms);

        $key = array_search('10', $modules_access);
        if (strpos($modules_permissions[$key], 'c') !== false) {
            $canCreate = true;
        } else {
            $canCreate = false;
        }

        return view('settings.index', compact('usergroups', 'membershiptypes', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) /* STORE MEMBERSHIP TYPE */
    {
        $this->validate($request, [
            'type' => 'required',
        ]);

        $type = new MembershipType;

        $type->type = $request->type;
        $type->created_by = Auth::user()->username;
        $type->save();

        // Created type id
        $type_id = $type->id;

        AuditLog::insert([
            'description' => 'created membership type: ' . $type_id,
            'user_id' => Auth::user()->id,
            'subject_type' => getenv('USERDOMAIN'),
            'module_id' => 10,
            'properties' => json_encode($request->post()),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect('/settings')->with('message', 'Membership Type has been added.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) /* SHOW/DISPLAY USER GROUP */
    {
        $modules_access = [];
        $modules_permissions = [];
        $usergroup = UserGroup::find($id);
        $users = User::where('user_group', $id)->get();
        $access = UserGroup::where('id', $id)->value('modules_access');
        $permissions = UserGroup::where('id', $id)->value('modules_permissions');

        $acc = explode(',', $access);
        $per = explode(',', $permissions);

        for ($i = 0; $i < count($acc); $i++) {
            $tmp = Module::where('id', $acc[$i])->value('module_name');
            array_push($modules_access, $tmp);
        }

        for ($i = 0; $i < count($per); $i++) {

            $length = strlen($per[$i]);

            $per[$i] = str_replace('r', 'Read,', $per[$i]);
            $per[$i] = str_replace('c', ' Create,', $per[$i]);
            $per[$i] = str_replace('u', ' Update', $per[$i]);
            array_push($modules_permissions, $per[$i]);
        }

        return view('settings.view-usergroup', compact('usergroup', 'acc', 'modules_access', 'modules_permissions', 'users'));
    }

    /*  *
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function permissions()
    {
        return view('settings.permissions');
    }

    /* USER GROUPS */
    public function createGroup()
    {
        $modules = Module::get();

        return view('settings.create-usergroup', compact('modules'));
    }

    public function storeGroup(Request $request)
    {
        $request->validate([
            'user_group_name' => 'required',
            'description' => 'nullable|string',
            'modules_access' => 'required|array|min:1',
            'canChangePortal' => 'required',
        ]);

        $modules_access = $request->modules_access; //checkboxes

        $modules_arr = array(); //store all modules with at least one permission
        $permissions_arr = array(); //store permissions of each module

        foreach ($modules_access as $ma) {
            $get_mod = explode('-', $ma)[1];

            if (!in_array($get_mod, $modules_arr)) {
                array_push($modules_arr, $get_mod);
            }
        }

        foreach ($modules_access as $ma) {
            $get_mod = explode('-', $ma)[1];
            $get_per = explode('-', $ma)[0];
            $perm = $get_per[0];

            array_push($permissions_arr, array('module' => $get_mod, 'permission' => $perm));
        }

        $permissions = '';
        for ($i = 0; $i < count($permissions_arr); $i++) {
            if ($i == 0) {
                $permissions .= $permissions_arr[$i]['permission'];
            } else {
                if ($permissions_arr[$i]['module'] == $permissions_arr[$i - 1]['module']) {
                    $permissions .= $permissions_arr[$i]['permission'];
                } else {
                    $permissions .= ',' . $permissions_arr[$i]['permission'];
                }
            }
        }

        $modules = implode(',', $modules_arr); //convert to string

        $userGroup = new UserGroup;
        $userGroup->user_group_name = $request->user_group_name;
        $userGroup->description = $request->description;
        $userGroup->modules_access = $modules;
        $userGroup->modules_permissions = $permissions;
        $userGroup->can_change_portal = $request->canChangePortal;
        $userGroup->created_by = Auth::user()->username;
        $userGroup->save();

        AuditLog::insert([
            'description' => 'created user_group: ' . $userGroup->id,
            'user_id' => Auth::user()->id,
            'subject_type' => getenv('USERDOMAIN'),
            'module_id' => 10,
            'properties' => json_encode($request->post()),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect('/settings')->with('message', 'User Group has been added.');
    }

    public function editGroup($id)
    {
        $modules = [];
        $modules_access = [];
        $modules_permissions = [];

        $usergroup = UserGroup::find($id);
        $modlist = Module::get();
        $users = User::where('user_group', $id)->get();
        $access = UserGroup::where('id', $id)->value('modules_access');
        $permissions = UserGroup::where('id', $id)->value('modules_permissions');

        $acc = explode(',', $access);

        for ($i = 0; $i < count($acc); $i++) {
            array_push($modules_access, $acc[$i]);
            $tmp = Module::where('id', $acc[$i])->value('module_name');
            array_push($modules, $tmp);
        }

        $per = explode(',', $permissions);

        for ($i = 0; $i < count($per); $i++) {
            array_push($modules_permissions, $per[$i]);
        }

        return view('settings.edit-usergroup', compact('usergroup', 'acc', 'modules', 'users', 'modlist', 'modules_access', 'modules_permissions'));
    }

    public function updateGroup(Request $request, $id)
    {
        $request->validate([
            'user_group_name' => 'required',
            'description' => 'nullable|string',
            'modules_access' => 'required|array|min:1',
            'canChangePortal' => 'required',
        ]);

        $modules_access = $request->modules_access; //checkboxes

        $modules_arr = array(); //store all modules with at least one permission
        $permissions_arr = array(); //store permissions of each module

        foreach ($modules_access as $ma) {
            $get_mod = explode('-', $ma)[1];

            if (!in_array($get_mod, $modules_arr)) {
                array_push($modules_arr, $get_mod);
            }
        }

        foreach ($modules_access as $ma) {
            $get_mod = explode('-', $ma)[1];
            $get_per = explode('-', $ma)[0];
            $perm = $get_per[0];

            array_push($permissions_arr, array('module' => $get_mod, 'permission' => $perm));
        }

        $permissions = '';
        for ($i = 0; $i < count($permissions_arr); $i++) {
            if ($i == 0) {
                $permissions .= $permissions_arr[$i]['permission'];
            } else {
                if ($permissions_arr[$i]['module'] == $permissions_arr[$i - 1]['module']) {
                    $permissions .= $permissions_arr[$i]['permission'];
                } else {
                    $permissions .= ',' . $permissions_arr[$i]['permission'];
                }
            }
        }

        $modules = implode(',', $modules_arr); //convert to string

        $userGroup = UserGroup::find($id);
        $userGroup->user_group_name = $request->user_group_name;
        $userGroup->description = $request->description;
        $userGroup->modules_access = $modules;
        $userGroup->modules_permissions = $permissions;
        $userGroup->can_change_portal = $request->canChangePortal;
        $userGroup->created_by = Auth::user()->username;
        $userGroup->save();

        AuditLog::insert([
            'description' => 'updated user_group: ' . $id,
            'user_id' => Auth::user()->id,
            'subject_type' => getenv('USERDOMAIN'),
            'module_id' => 10,
            'properties' => json_encode($request->post()),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect('/settings')->with('message', 'User Group has been updated.');
    }

    /* Membertypes */
    public function memberTypeView($id)
    {
        $types = DB::table('membership_types')->where('id', $id)->get();

        return view('settings.view-membertype', compact('types'));
    }

    public function createMemberType()
    {
        return view('settings.create-membertype');
    }

    public function editMemberType($id)
    {
        $types = DB::table('membership_types')->where('id', $id)->get();

        return view('settings.edit-membertype', compact('types'));
    }

    public function updateMemberType(Request $request, $id)
    {

        $this->validate($request, [
            'type' => 'required',
        ]);

        $type = MembershipType::find($id);

        $type->type = $request->type;
        $type->created_by = Auth::user()->username;
        $type->save();

        AuditLog::insert([
            'description' => 'created membership type: ' . $id,
            'user_id' => Auth::user()->id,
            'subject_type' => getenv('USERDOMAIN'),
            'module_id' => 10,
            'properties' => json_encode($request->post()),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect('/settings')->with('message', 'Membership Type has been updated.');

    }
}
