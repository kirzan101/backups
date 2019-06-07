@extends('layouts.admin')

@section('title')
    VoucherMS | Editing {{ $user->name }}
@endsection

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/users">Users</a></li>
            <li class="breadcrumb-item"><a href="/users/{{ $user->id }}">{{ $user->username }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <h3 class="display-4">Edit User</h3>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                <h3> <i class="fa fa-user"></i>  {{ $user->username }}</h3>
                </div>
                <div class="card-body">          
                    <form method="POST" action="/users/update">
                        @method('PUT')
                        {{ csrf_field() }}
                        <h5 class="col-sm-10">Account Information</h5>
                        <br>

                        <div class="form-group row">
                            <label for="id" class="font-weight-bold col-sm-2 col-form-label text-right">User ID</label>
                            <div class="col-sm-1">
                                <input type="text" disabled class="form-control-plaintext" id="id" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="id" value="{{ $user->id }}">
                            </div>
                        
                            <div class="col-sm-3"></div>

                            <label for="status" class="font-weight-bold col-sm-1 col-form-label text-right">Status</label>
                            <div class="col-sm-3">
                                <select id="status" name="status" class="form-control {{ $errors->has('status') ? ' is-invalid' : '' }}">
                                    <option value="" selected>Choose...</option>
                                    <option value="active" {{ old('status') == "active" || (!old('status') && $user->status == "active") ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == "inactive" || (!old('status') && $user->status == "inactive") ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @if ($errors->has('status'))
                                    <span class="invalid-feedback">
                                        <strong>Please select a status.</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <label for="username" class="font-weight-bold col-sm-2 col-form-label text-right">Username</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" id="username" name="username" value="{{ !old('username') ? $user->username : old('username') }}">
                                @if ($errors->has('username'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>

                            <label for="user_group" class="font-weight-bold col-sm-2 col-form-label text-right">User Group</label>
                            <div class="col-sm-3">
                                <select id="user_group" name="user_group" class="form-control {{ $errors->has('user_group') ? ' is-invalid' : '' }}">
                                    <option value="" selected>Choose...</option>
                                        @foreach ($user_groups as $group)
                                            <option value="{{ $group->id }}" {{ old('user_group') == $group->id || (!old('user_group') && $user->user_group == $group->id) ? 'selected' : '' }}>{{ $group->user_group_name }}</option>
                                        @endforeach
                                </select>
                                @if ($errors->has('user_group'))
                                    <span class="invalid-feedback">
                                        <strong>Please select a user group.</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            
                            <label for="email" class="font-weight-bold col-sm-2 col-form-label text-right">Email</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ !old('email') ? $user->email : old('email') }}">
                                @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            
                    
                            <label for="password" class="font-weight-bold col-sm-2 col-form-label text-right">Password</label>
                            <div class="col-sm-1">
                                <a href="#" class="btn btn-outline-danger" data-toggle="modal" data-target="#resetPasswordModal"><i class="fa fa-undo"></i> Reset Password</a>
                            </div>
                            
                            
                        </div>

                        <div class="form-group row">
                            <label for="canChangePortal" class="font-weight-bold col-sm-2 col-form-label text-right">Can Change Portal</label>
                            <div class="col-sm-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="canChangePortal" id="yes_change" value="1" {{ $user->can_change_portal == 1 || old('canChangePortal') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="yes_change">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="canChangePortal" id="no_change" value="0" {{ $user->can_change_portal == 0 || old('canChangePortal') == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="no_change">No</label>
                                </div>
                            </div>                        
                        </div>
                        <div class="form-group row" id="portalTypeRow">
                            <label for="portal_type" class="font-weight-bold col-sm-2 col-form-label text-right">Portal</label>
                            <div class="col-sm-2">
                                <select id="portal_type" name="portal_type" class="form-control {{ $errors->has('portal_type') ? ' is-invalid' : '' }}">
                                    @foreach ($membership_types as $mt)
                                        <option value="{{ $mt->id }}" {{ $user->portal_type == $mt->id || old('portal_type') == $mt->id ? 'selected' : '' }}>{{ $mt->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <br><hr><br>
                        <h5 class="col-sm-10">Personal Information</h5>
                        <br>
                        <div class="form-group row">
                            <label for="first_name" class="font-weight-bold col-sm-2 col-form-label text-right">First Name</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" id="first_name" name="first_name" value="{{ !old('first_name') ? $user->first_name : old('first_name') }}">
                                @if ($errors->has('first_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            <label for="middle_name" class="font-weight-bold col-sm-2 col-form-label text-right">Middle Name</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control {{ $errors->has('middle_name') ? ' is-invalid' : '' }}" id="middle_name" name="middle_name" value="{{ !old('middle_name') ? $user->middle_name : old('middle_name') }}">
                                @if ($errors->has('middle_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('middle_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            <label for="last_name" class="font-weight-bold col-sm-2 col-form-label text-right">Last Name</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" id="last_name" name="last_name" value="{{ !old('last_name') ? $user->last_name : old('last_name') }}">
                                @if ($errors->has('last_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>     
                        </div>              

                        <br>
                        <div class="form-group row">
                            <label for="department" class="font-weight-bold col-sm-2 col-form-label text-right">Department</label>
                            <div class="col-sm-2">
                                <select id="department" name="department" class="form-control {{ $errors->has('department') ? ' is-invalid' : '' }}">
                                    <option value="" selected>Choose...</option>
                                    <option value="mis" {{ old('department') == "mis" || (!old('department') && $user->department == "mis") ? 'selected' : '' }}>MIS</option>
                                    <option value="Front Office" {{ old('department') == "Front Office" || (!old('department') && $user->department == "Front Office") ? 'selected' : '' }}>Front Office</option>
                                    <option value="Club Accounting" {{ old('department') == "Club Accounting" || (!old('department') && $user->department == "Club Accounting") ? 'selected' : '' }}>Club Accounting</option>
                                    <option value="Credit/AR Accounting" {{ old('department') == "Credit/AR Accounting" || (!old('department') && $user->department == "Credit/AR Accounting") ? 'selected' : '' }}>Credit/AR Accounting</option>
                                    <option value="Sales Deck" {{ old('department') == "Sales Deck" || (!old('department') && $user->department == "Sales Deck") ? 'selected' : '' }}>Sales Deck</option>
                                    <option value="Audit" {{ old('department') == "Audit" || (!old('department') && $user->department == "Audit") ? 'selected' : '' }}>Audit</option>
                                    <option value="ICT" {{ old('department') == "ICT" || (!old('department') && $user->department == "ICT") ? 'selected' : '' }}>ICT</option>
                                </select>
                                @if ($errors->has('department'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('department') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 text-right">                        
                                <a href="/users/{{ $user->id }}" class="btn btn-outline-dark"><i class="fa fa-chevron-left"></i> Cancel</a>
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer small text-muted">User last updated on {{ date('m/d/Y \a\t h:i:s A', strtotime($user->updated_at)) }}</div>
            </div>
        </div>
    </div>
    

    <div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="/users/resetpassword">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resetPasswordModalLabel"><i class="fa fa-fw fa-undo"></i> Reset Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">                        
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="form-group">
                            <label for="password" class="col-form-label">Password</label>
                            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="Enter a new password">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="col-form-label">Confirm Password</label>
                            <input type="password" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
                            @if ($errors->has('password_confirmation'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </form>                                
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function(){           
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
    </script>

    @if ($errors->has('password'))
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
        <script>
            $('#resetPasswordModal').modal('show')
        </script>
    @endif

@endsection