@extends('layouts.admin')

@section('title')
    {{ $usergroup->user_group_name}}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/settings">Settings</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $usergroup->user_group_name}}</li>
        </ol>
    </nav>
   <div class="container-fluid">
       <div class="card">
           <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h2> <i class="fa fa-user"></i>  {{ $usergroup->user_group_name }}</h2>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4 text-right">
                        <a class="btn btn-outline-success" href="/settings-edit-group/{{ $usergroup->id }}/edit"><i class="fa fa-edit"></i> Edit Usergroup</a>
                    </div>
                </div>
            </div>
           
            <div class="card-body">
                <h4 class="card-title">Module Access</h4>

                <div class="row">
                    <div class="col-4">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules_access as $i => $access)
                                    <tr>
                                        <td>{{ ucfirst($access) }}</td>
                                        <td>{{ $modules_permissions[$i] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>

                <h4 class="card-title">Users</h4>
                <p class="card-text">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">ID #</th>
                            <th scope="col">Username</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Middle Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Created_at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $key=>$user)
                            <tr>
                            <th scope="row">{{ $user->id }}</th>
                            <td>{{$user->username}}</td>
                            <td>{{$user->first_name}}</td>
                            <td>{{$user->middle_name}}</td>
                            <td>{{$user->last_name}}</td>
                            <td>{{$user->created_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </p>
                {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
            </div>
        </div>
    </div>

@endsection