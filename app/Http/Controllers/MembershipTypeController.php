<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditLogController;
use App\MembershipType;
use App\UserGroup;
use Auth;
use DB;
use Illuminate\Http\Request;

class MembershipTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member_types = MembershipType::All();

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

        return view('member_types.index', compact('member_types', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|unique:membership_types',
        ]);

        $type = new MembershipType;

        $type->type = $request->type;
        $type->created_by = Auth::user()->username;
        $type->save();

        $auditLog = new AuditLogController;
        $description = 'created membership type: ' . $type->id;
        $auditLog->store($description, 10, $request->post());

        return redirect('/member-types')->with('message', 'Membership Type has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $types = DB::table('membership_types')->where('id', $id)->get();
        return view('member_types.edit', compact('types'));
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
        $this->validate($request, [
            'type' => 'required|unique:membership_types,type,' . $id,
        ]);

        $type = MembershipType::find($id);

        $type->type = $request->type;
        $type->updated_by = Auth::user()->username;
        $type->save();

        $auditLog = new AuditLogController;
        $description = 'updated membership type: ' . $type->id;
        $auditLog->store($description, 10, $request->post());

        return redirect('/member-types')->with('message', 'Membership Type has been updated.');
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
}
