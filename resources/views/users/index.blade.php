@extends('layouts.admin')

@section('title')
    VoucherMS | Users
@endsection

@section('content')

    <div id="addUser">
        <div class="modal fade bd-example-modal-lg" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">

                <form method="POST" action="/users">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel"><i class="fa fa-fw fa-user-plus"></i> Add User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">                        
                            {{ csrf_field() }}

                             <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" style="text-transform: capitalize;">
                                    @if ($errors->has('first_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control {{ $errors->has('middle_name') ? ' is-invalid' : '' }}" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle Name" style="text-transform: capitalize;">
                                    @if ($errors->has('middle_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('middle_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" style="text-transform: capitalize;">
                                    @if ($errors->has('last_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" id="username" name="username" value="{{ old('username') }}" placeholder="Username">
                                    @if ($errors->has('username'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="user_group">User Group</label>
                                    <select id="user_group" name="user_group" class="form-control {{ $errors->has('user_group') ? ' is-invalid' : '' }}">
                                        <option value="" selected>Choose...</option>
                                        @foreach ($user_groups as $group)
                                            <option value="{{ $group->id }}" {{ old('user_group') == $group->id ? 'selected' : '' }}>{{ $group->user_group_name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('user_group'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('user_group') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="Enter a password">
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control {{ $errors->has('password_confirmation') || $errors->has('password') ? ' is-invalid' : '' }}" id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>                             
                                
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" placeholder="Email address">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="department">Department</label>
                                    <select id="department" name="department" class="form-control {{ $errors->has('department') ? ' is-invalid' : '' }}">
                                        <option value="" selected>Choose...</option>
                                        <option value="Accounting" {{ old('department') == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                                        <option value="Audit" {{ old('department') == 'Audit' ? 'selected' : '' }}>Audit</option>
                                        <option value="Club Accounting" {{ old('department') == 'Club Accounting' ? 'selected' : '' }}>Club Accounting</option>                                        
                                        <option value="Customer Service" {{ old('department') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                                        <option value="Front Office - Plaza" {{ old('department') == 'Front Office - Plaza' ? 'selected' : '' }}>Front Office - Plaza</option>
                                        <option value="Front Office - Greenbelt" {{ old('department') == 'Front Office - Greenbelt' ? 'selected' : '' }}>Front Office - Greenbelt</option>
                                        <option value="Front Office - Boracay" {{ old('department') == 'Front Office - Boracay' ? 'selected' : '' }}>Front Office - Boracay</option>
                                        <option value="Front Office - Current" {{ old('department') == 'Front Office - Current' ? 'selected' : '' }}>Front Office - Current</option>
                                        <option value="Front Office - Palawan" {{ old('department') == 'Front Office - Palawan' ? 'selected' : '' }}>Front Office - Palawan</option>
                                        <option value="ICT" {{ old('department') == 'ICT' ? 'selected' : '' }}>ICT</option>
                                        <option value="Sales Deck" {{ old('department') == 'Sales Deck' ? 'selected' : '' }}>Sales Deck</option>                                       
                                    </select>
                                    @if ($errors->has('department'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('department') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <input type="hidden" name="status" value="active">
                            </div>

                            {{-- <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="canChangePortal" class="mr-4">Can Change Portal</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="canChangePortal" id="yes_change" value="1" {{ !old('canChangePortal') || old('canChangePortal') == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="yes_change">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="canChangePortal" id="no_change" value="0" {{ old('canChangePortal') && old('canChangePortal') == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="no_change">No</label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label for="portal_type" class="mr-4">Portal</label>
                                    <input class="form-check-input" type="radio" name="canChangePortal" id="no_change" value="0" checked hidden>
                                    <select id="portal_type" name="portal_type" class="form-control {{ $errors->has('portal_type') ? ' is-invalid' : '' }}">
                                        @foreach ($membership_types as $mt)
                                            <option value="{{ $mt->id }}" {{ old('portal_type') == $mt->id ? 'selected' : '' }}>{{ $mt->type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <div id="users">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
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
          <h3><i class="fa fa-fw fa-users"></i> Users</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="/users">
                <div class="row mb-3">
                    <div class="col-1">
                        @if ($canCreate)
                            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#addModal"><i class="fa fa-fw fa-user-plus"></i> Add User</a>
                        @endif
                    </div>
                    <div class="col-6"></div>
                    <div class="col-5">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search for users" aria-label="Search" value="{{ $search }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-search"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div class="text-right">
                        <form method="GET" action="/audit-log" class="form-inline"></form>
                            <label class="my-1 mr-2" for="per_page">Entries per page: </label>
                            <select class="custom-select my-1 mr-sm-2 col-sm-1" id="per_page" name="per_page">
                                <option value="10" {{ $per_page == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $per_page == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $per_page == '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </form>
                    </div>
                    

                    @if ($search != '')
                    <p>
                        Showing {{ $users->total() }} results for <strong>{{ $search }}</strong>
                    </p>
                    @endif

                    <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th class="text-center"># <a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Name <a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=last_name&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=last_name&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Email <a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=email&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=email&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Username <a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=username&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=username&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>User Group <a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=user_group&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=user_group&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Department <a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=department&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=department&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Status <a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=status&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=status&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Created By <a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Created At <a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Updated At <a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/users?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $i=>$user)
                                <tr>
                                    <th class="text-center">{{ $i + 1 + ($users->perPage() * ($users->currentPage() - 1)) }}</th>
                                    <td><a href="/users/{{ $user->id }}" class="link-table">{{ $user->first_name . ' ' . $user->last_name }}</a></td>
                                    <td><a href="/users/{{ $user->id }}" class="link-table">{{ $user->email }}</a></td>
                                    <td><a href="/users/{{ $user->id }}" class="link-table">{{ $user->username }}</a></td>
                                    <td><a href="/users/{{ $user->id }}" class="link-table">{{ $user->user_group_name }}</a></td>
                                    <td><a href="/users/{{ $user->id }}" class="link-table">{{ $user->department }}</a></td>
                                    <td><a href="/users/{{ $user->id }}" class="link-table">{{ ucwords($user->status) }}</a></td>
                                    <td><a href="/users/{{ $user->id }}" class="link-table">{{ $user->created_by }}</a></td>
                                    <td><a href="/users/{{ $user->id }}" class="link-table">{{ date("d M Y", strtotime($user->created_at)) }}</a></td>
                                    <td><a href="/users/{{ $user->id }}" class="link-table">{{ date("d M Y", strtotime($user->updated_at)) }}</a></td>
                                    <td><a href="/users/{{ $user->id }}" class="btn btn-outline-success">Details</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    {{ $users->appends([
                        'search' => $search,
                        'per_page' => $per_page,
                        'sort' => app('request')->input('sort'),
                        'dir' => app('request')->input('dir')
                    ])->links() }}

                </div>
            </form>
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


@section('scripts')

  <script>
        $(document).ready(function(){
            $('#portalTypeRow').hide();
            
            var canChangePortal = $('#canChangePortal').val();

            if(canChangePortal == '1'){
                $('#portalTypeRow').hide();
            }
        });

        $('input[type=radio][name=canChangePortal]').change(function() {
            if (this.value == '1') {
                $('#portalTypeRow').hide();
            }
            else if (this.value == '0') {
                $('#portalTypeRow').show();
            }
        });

        $(function() {
            $('#per_page').change(function() {
                this.form.submit();
            });
        });
    </script> 


    @if ($errors->any())
        <script>
            $('#addModal').modal('show')
        </script>
    @endif

@endsection