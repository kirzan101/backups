@extends('layouts.admin')

@section('title')
    VoucherMS | Change Password
@endsection

@section('content')

    <div class="container-fluid">
        <h3 class="display-4">Change Password</h3>

        <hr>

        <div class="row">
            <div class="col-md-4">
                <form method="POST" action="/updatepassword">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control {{ $errors->has('current_password') ? ' is-invalid' : '' }}" id="current_password" name="current_password" placeholder="Current Password">
                        @if ($errors->has('current_password'))
                            <span class="invalid-feedback">
                                <strong>Your current password is incorrect.</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control {{ $errors->has('new_password') ? ' is-invalid' : '' }}" id="new_password" name="new_password" placeholder="New Password">
                        @if ($errors->has('new_password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('new_password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirn New Password</label>
                        <input type="password" class="form-control {{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm New Password">
                        @if ($errors->has('new_password_confirmation'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>                    
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                </form>
            </div>
        </div>
    </div>

@endsection