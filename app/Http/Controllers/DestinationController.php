<?php

namespace App\Http\Controllers;

use App\Destination;
use App\Http\Controllers\AuditLogController;
use App\UserGroup;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $destinations = Destination::All();

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

        return view('destinations.index', compact('destinations', 'canCreate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('destinations.create');
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
            'code' => 'required|alpha_num|unique:destinations',
            'name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'remarks' => 'nullable|string|max:255',
        ]);

        $destination = new Destination;
        $destination->code = $request->code;
        $destination->destination_name = $request->name;
        $destination->remarks = $request->remarks;
        $destination->created_by = Auth::user()->username;
        $destination->save();

        $auditLog = new AuditLogController;
        $description = 'created destination: ' . $destination->id;
        $auditLog->store($description, 10, $request->post());

        return redirect('/destinations')->with('message', 'Destination has been added.');
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
        $destination = Destination::find($id);
        return view('destinations.edit', compact('destination'));
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
        $request->validate([
            'code' => [
                'required',
                'alpha_num',
                Rule::unique('destinations')->ignore($id),
            ],
            'name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
        ]);

        $destination = Destination::find($id);
        $destination->code = $request->code;
        $destination->destination_name = $request->name;
        $destination->remarks = $request->remarks;
        $destination->updated_by = Auth::user()->username;
        $destination->save();

        $auditLog = new AuditLogController;
        $description = 'updated destination: ' . $destination->id;
        $auditLog->store($description, 10, $request->post());

        return redirect('/destinations')->with('message', 'Destination has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->des_id;
        $destination = Destination::find($id);
        $destination->delete();

        $auditLog = new AuditLogController;
        $description = 'deleted destination: ' . $destination->id;
        $auditLog->store($description, 10, $request->post());

        return redirect('/destinations')->with('message', 'Destination has been deleted.');
    }
}
