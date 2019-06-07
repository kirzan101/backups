@extends('layouts.admin')

@section('title')
    Settings
@endsection

@section('content')

    <div class="container-fluid">

        {{-- Message --}}
        <div class="row">
            <div class="col-12">
                @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert" id="success-alert">
                        <i class="fa fa-fw fa-check"></i> {{ session()->get('message') }}
                    </div>
                @endif
            </div>
        </div> 

        {{-- TITLE --}}
        <div class="">
            <h3 class="display-4">Settings</h3>
        </div>
                        
                <br> 

                {{-- USERGROUPS --}}
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-5">
                        <h4><i class="fa fa-fw fa-users"></i> User Groups</h4>
                    </div>
                    <div class="col-2"></div>
                    @if ($canCreate)
                        <div class="col-5 text-right">
                            <a class="btn btn-outline-success" href="/setting-create-group" role="button"><i class="fa fa-edit"></i> Add New User Group</a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="">
                        <tr class="" >
                        <th scope="col" style="border:none;">#</th>
                        <th scope="col" style="border:none;">User Group Name</th>
                        <th scope="col" style="border:none;">Description</th>
                        <th scope="col" style="border:none;">Created By</th>
                        <th scope="col" style="border:none;">Created At</th>
                        <th scope="col" style="border:none;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach($usergroups as $usergroup)
                        <tr>
                            <th scope="row"><a class="link-table" href="/settings-edit-group/{{$usergroup->id}}/edit">{{$usergroup->id}}</a></th>
                            <td><a class="link-table" href="/settings-edit-group/{{$usergroup->id}}/edit">{{$usergroup->user_group_name}}</td>
                            <td><a class="link-table" href="/settings-edit-group/{{$usergroup->id}}/edit">{{$usergroup->description}}</td>
                            <td><a class="link-table" href="/settings-edit-group/{{$usergroup->id}}/edit">{{$usergroup->created_by}}</td>                            
                            <td><a class="link-table" href="/settings-edit-group/{{$usergroup->id}}/edit">{{$usergroup->created_at}}</td>                            
                            <td>
                                <a class="btn btn-primary text-light" href="/settings-edit-group/{{$usergroup->id}}/edit" class="btn btn-primary">Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <br>

        <div class="row">
                {{-- Membership Type --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4><i class="fa fa-fw fa-users"></i>Membership Types</h4>
                            </div>
        
                            <div class="col-6 text-right">
                                @if ($canCreate)
                                    <a class="btn btn-outline-success" href="/setting-create-membertype" role="button"><i class="fa fa-edit"></i> Add New Type</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead class="">
                                <tr class="" >
                                {{-- <th scope="col" style="border:none;">User Group Number</th> --}}
                                <th scope="col" style="border:none;">ID</th>
                                <th scope="col" style="border:none;">Type</th>
                                <th scope="col" style="border:none;">Created By</th>
                                <th scope="col" style="border:none;">Created At</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($membershiptypes as $type)
                                <tr>
                                    {{-- <th scope="row"><a class="link-table" href="'/settings/{{$usergroup->id}}">{{$usergroup->id}}</a></th> --}}
                                    <td><a class="link-table" href="/member-type/{{$type->id}}">{{$type->id}}</td>
                                    <td><a class="link-table" href="/member-type/{{$type->id}}">{{$type->type}}</td>
                                    <td><a class="link-table" href="/member-type/{{$type->id}}">{{$type->created_by}}</td>
                                    <td><a class="link-table" href="/member-type/{{$type->id}}">{{$type->created_at}}</td>

                                    {{-- <td>{{$usertotal}}</td> {{-- TOTAL USERS FROM USER TABLE --}}
                                {{--  <td >
                                        <a class="btn btn-primary text-light" href="/member-type/{{$type->id}}" class="btn btn-primary">Details</a>
                                    </td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>               
            </div>
        </div>
    </div>  
    <br>

 <style>
        .link-table {
            display: block;
            color:#0d0d0d;
        }

        .link-table:hover {
            text-decoration: none;
            color:#0d0d0d;
        }
    </style>

@endsection