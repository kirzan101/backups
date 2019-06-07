@extends('layouts.admin')

@section('title')
    VoucherMS | User Groups
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
            <h3 class="display-4">User Groups</h3>
        </div>
                        
        <br>
        <hr>
        <br>

        @if ($canCreate)
            <div class="row">
                <a class="btn btn-success" href="/user-groups/create" role="button"><i class="fa fa-plus"></i> Add User Group</a>
            </div>
        @endif

        <br>
            
        <div class="row">
            <div class="col-12">
                <table class="table table-hover">
                    <thead class="">
                        <tr class="" >
                        <th scope="col" style="border:none;">#</th>
                        <th scope="col" style="border:none;">User Group Name</th>
                        <th scope="col" style="border:none;">Description</th>
                        <th scope="col" style="border:none;">Created By</th>
                        <th scope="col" style="border:none;">Created At</th>
                        <th scope="col" style="border:none;">Updated By</th>
                        <th scope="col" style="border:none;">Updated At</th>
                        <th scope="col" style="border:none;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user_groups as $usergroup)
                            <tr>
                                <th scope="row"><a class="link-table" href="/user-groups/{{$usergroup->id}}/edit">{{$usergroup->id}}</a></th>
                                <td><a class="link-table" href="/user-groups/{{$usergroup->id}}/edit">{{ $usergroup->user_group_name }}</td>
                                <td><a class="link-table" href="/user-groups/{{$usergroup->id}}/edit">{{ $usergroup->description }}</td>
                                <td><a class="link-table" href="/user-groups/{{$usergroup->id}}/edit">{{ $usergroup->created_by }}</td>                            
                                <td><a class="link-table" href="/user-groups/{{$usergroup->id}}/edit">{{ date("d M Y", strtotime($usergroup->created_at)) }}</td>
                                <td><a class="link-table" href="/user-groups/{{$usergroup->id}}/edit">{{ $usergroup->updated_by }}</td>                            
                                <td><a class="link-table" href="/user-groups/{{$usergroup->id}}/edit">{{ date("d M Y", strtotime($usergroup->updated_at)) }}</td>
                                <td>
                                    <a href="/user-groups/{{$usergroup->id}}/edit" class="btn btn-outline-success">Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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