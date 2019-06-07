@extends('layouts.admin')

@section('title')
    VoucherMS | {{ $user->name }}
@endsection

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/users">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $user->username }}</li>
        </ol>
    </nav>

    <div class="col-10">
        <div class="row">
            <div class="col-12">
                @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <i class="fa fa-fw fa-check"></i> {{ session()->get('message') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h2> <i class="fa fa-user"></i>  {{ $user->first_name }}  {{ $user->middle_name }}  {{ $user->last_name }}</h2>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4 text-right">
                        <a class="btn btn-outline-success" href="/users/{{ $user->id }}/edit"><i class="fa fa-edit"></i> Edit User</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form>
                    <fieldset disabled>
                        
                        <form>
                            <h5 class="col-sm-10">Account Information</h5>
                            <br>

                            <div class="form-group row">
                                <label for="id" class="font-weight-bold col-sm-2 col-form-label text-right">User ID: </label>
                                <div class="col-sm-3">
                                    <input type="text" readonly class="form-control-plaintext" id="id" value="{{ $user->id }}">
                                </div>

                                 <label for="status" class="font-weight-bold col-sm-2 col-form-label text-right">Status: </label>
                                <div class="col-sm-2">
                                    <input type="text" readonly class="form-control-plaintext font-weight-bold {{ $user->status == 'active' ? 'text-success' : 'text-danger' }}" id="status" value="{{ strtoupper($user->status) }}">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="username" class="font-weight-bold col-sm-2 col-form-label text-right">Username: </label>
                                <div class="col-sm-2">
                                    <input type="text" readonly class="form-control-plaintext" id="id" value="{{ $user->username }}">
                                </div>

                                <div class="col-sm-1"></div>

                                <label for="user_group" class="font-weight-bold col-sm-2 col-form-label text-right">User Group: </label>
                                <div class="col-sm-2">
                                    <input type="text" readonly class="form-control-plaintext" id="user_group" value="{{ $user_group->user_group_name }}">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="email" class="font-weight-bold col-sm-2 col-form-label text-right">Email:</label>
                                <div class="col-sm-4">
                                    <input type="text" readonly class="form-control-plaintext" id="email" value="{{ $user->email }}">
                                </div>                                                         
                            </div>

                            <br><hr><br>
                            <h5 class="col-sm-10">Personal Information</h5>
                            <br>
                            <div class="form-group row">
                                <label for="frist_name" class="font-weight-bold col-sm-2 col-form-label text-right">First Name:</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly class="form-control-plaintext" id="first_name" value="{{ $user->first_name }}">
                                </div> 
                                
                                <label for="middle_name" class="font-weight-bold col-sm-2 col-form-label text-right">Middle Name:</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly class="form-control-plaintext" id="middle_name" value="{{ $user->middle_name }}">
                                </div> 

                                <label for="last_name" class="font-weight-bold col-sm-2 col-form-label text-right">Last Name:</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly class="form-control-plaintext" id="last_name" value="{{ $user->last_name }}">
                                </div> 

                                <div class="col-sm-1"></div>
                            </div>

                            <div class="form-group row">
                                <label for="department" class="font-weight-bold col-sm-2 col-form-label text-right">Department:</label>
                                <div class="col-sm-3">
                                    <input type="text" readonly class="form-control-plaintext" id="department" value="{{ $user->department }}">
                                </div>
                            </div>

                        </form>

                    </fieldset>
                </form>
            </div>
            <div class="card-footer small text-muted">User last updated on {{ date('m/d/Y \a\t h:i:s A', strtotime($user->updated_at)) }}</div>
        </div>
    </div>

@endsection