<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditLogController;
use App\Module;
use App\User;
use App\Report;
use App\UserGroup;
use Auth;
use DB;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_groups = UserGroup::All();

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

        return view('user_groups.index', compact('user_groups', 'canCreate'));
    }

    public function create()
    {
        $modules = Module::get();
        $reports = DB::table('reports')->get();
        return view('user_groups.create', compact('modules', 'reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_group_name' => 'required|unique:user_groups',
            'description' => 'nullable|string',
            'modules_access' => 'required|array|min:1',
        ]);

        $modules_access = $request->modules_access; //checkboxes
        $reports_access = $request->report_access;

        $modules_arr = array(); //store all modules with at least one permission
        $permissions_arr = array(); //store permissions of each module
        $reports_arr = array();

        foreach ($modules_access as $ma) {
            $get_mod = explode('-', $ma)[1];

            if (!in_array($get_mod, $modules_arr)) {
                array_push($modules_arr, $get_mod);
            }
        }
       
        // set reports_access as $ra
        foreach ($reports_access as $ra) {
            $get_rep = explode('-', $ra)[1];

            if (!in_array($get_rep, $reports_arr)) {
                array_push($reports_arr, $get_rep);
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
        $reports = implode(',', $reports_arr); //includes ',' to $reports_arr 

        $userGroup = new UserGroup;
        $userGroup->user_group_name = $request->user_group_name;
        $userGroup->description = $request->description;
        $userGroup->modules_access = $modules;
        $userGroup->report_access = $reports;
        $userGroup->modules_permissions = $permissions;
        $userGroup->created_by = Auth::user()->username;
        $userGroup->save();

        $auditLog = new AuditLogController;
        $description = 'created user group: ' . $userGroup->id;
        $auditLog->store($description, 10, $request->post());

        return redirect('/user-groups')->with('message', 'User Group has been added.');
    }

    public function edit($id)
    {
        $modules = [];
        $modules_access = [];
        $report_access = [];
        $modules_permissions = [];

        $usergroup = UserGroup::find($id);
        $modlist = Module::get();
        $replist = DB::table('reports')->get();
        $users = User::where('user_group', $id)->get();
        $access = UserGroup::where('id', $id)->value('modules_access');
        $repaccess = UserGroup::where('id', $id)->value('report_access');
        $permissions = UserGroup::where('id', $id)->value('modules_permissions');

        $acc = explode(',', $access);

        for ($i = 0; $i < count($acc); $i++) {
            array_push($modules_access, $acc[$i]);
            $tmp = Module::where('id', $acc[$i])->value('module_name');
            array_push($modules, $tmp);
        }
        
        $repacc = explode(',', $repaccess);

        for ($i = 0; $i < count($repacc); $i++) {
            array_push($report_access, $repacc[$i]);
            $tmp = DB::table('reports')->where('id', $repacc[$i])->value('name');
            array_push($modules, $tmp);
        }

        $per = explode(',', $permissions);

        for ($i = 0; $i < count($per); $i++) {
            array_push($modules_permissions, $per[$i]);
        }

        return view('user_groups.edit', compact('usergroup', 'acc', 'modules', 'users', 'modlist', 'modules_access', 'modules_permissions', 'report_access', 'replist'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_group_name' => 'required|unique:user_groups,user_group_name,' . $request->id,
            'description' => 'nullable|string',
            'modules_access' => 'required|array|min:1',
        ]);

        $modules_access = $request->modules_access; //checkboxes
        $reports_access = $request->report_access;

        $modules_arr = array(); //store all modules with at least one permission
        $permissions_arr = array(); //store permissions of each module
        $reports_arr = array();

        foreach ($modules_access as $ma) {
            $get_mod = explode('-', $ma)[1];

            if (!in_array($get_mod, $modules_arr)) {
                array_push($modules_arr, $get_mod);
            }
        }

        // set reports_access as $ra
        foreach ($reports_access as $ra) {
            $get_rep = explode('-', $ra)[1];

            if (!in_array($get_rep, $reports_arr)) {
                array_push($reports_arr, $get_rep);
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
        $reports = implode(',', $reports_arr); //includes ',' to $reports_arr 

        $id = $request->id;

        $userGroup = UserGroup::find($id);
        $userGroup->user_group_name = $request->user_group_name;
        $userGroup->description = $request->description;
        $userGroup->modules_access = $modules;
        $userGroup->report_access = $reports;
        $userGroup->modules_permissions = $permissions;
        $userGroup->updated_by = Auth::user()->username;
        $userGroup->save();

        $auditLog = new AuditLogController;
        $description = 'updated user group: ' . $userGroup->id;
        $auditLog->store($description, 10, $request->post());

        return redirect('/user-groups')->with('message', 'User Group has been updated.');
    }
}
