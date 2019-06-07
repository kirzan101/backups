<?php

namespace App\Http\Controllers;

use App\Consultant;
use App\Http\Controllers\AuditLogController;
use App\UserGroup;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConsultantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        if ($search) {
            $consultants = Consultant::where('name', 'like', $search . '%')->paginate($per_page);

        } else {
            $consultants = Consultant::paginate($per_page);
        }

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

        return view('consultants.index', compact('consultants', 'canCreate', 'per_page', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('consultants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:consultants,name|regex:/^[a-zA-Z-\'\s]*$/',
        ]);

        $consultant = new Consultant;

        $consultant->name = $request->name;
        $consultant->created_by = Auth::user()->username;
        $consultant->save();

        $auditLog = new AuditLogController;
        $description = 'created consultant: ' . $consultant->id;
        $auditLog->store($description, 10, $request->post());

        return redirect('/consultants')->with('message', 'Consultant has been added.');
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
        $consultant = Consultant::findOrFail($id);

        return view('consultants.edit', compact('consultant'));

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
        $consultant = Consultant::find($id);

        $request->validate([
            'name' => [
                'required',
                'regex:/^[a-zA-Z-\'\s]*$/',
                Rule::unique('consultants')->ignore($consultant->id),
            ],
        ]);

        $consultant->name = $request->name;
        $consultant->updated_by = Auth::user()->username;
        $consultant->save();

        $auditLog = new AuditLogController;
        $description = 'updated consultant: ' . $consultant->id;
        $auditLog->store($description, 10, $request->post());

        return redirect('/consultants')->with('message', 'Consultant has been updated.');
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
