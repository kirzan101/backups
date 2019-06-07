@extends('layouts.admin')

@section('title')
    VoucherMS | Add Member
@endsection

@section('content')

    <div id="accounts">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/accounts">Accounts</a></li>
            <li class="breadcrumb-item">
                <a href="/accounts/{{ $account->id }}">
                    @foreach($account->members as $key=>$member)
                        {{ $member->first_name . ' ' . $member->last_name }} 
                        @if($key = 0)
                            {{ " " }}
                        @elseif($key % 2 == 0)
                            @if(!$loop->last)
                            {{ "," }}
                            @endif
                        @endif
                    @endforeach
                </a>                                  
            </li>
            <li class="breadcrumb-item" aria-current="page">Add Member</li>
        </ol>
    </nav>

    <div class="card mb-3">
        <div class="card-header">
            <h3><i class="fa fa-fw fa-user-plus"></i> Add Member</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/accounts/addMember/store">
                {{ csrf_field() }}
                
                <legend class="col-form-label col-sm-2 pt-0">Member Type</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="member_type" id="existing" value="existing" checked>
                    <label class="form-check-label" for="existing">
                        Existing
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="member_type" id="new" value="new" {{ old('member_type') == 'new' ? 'checked' : '' }}>
                    <label class="form-check-label" for="new">
                        New
                    </label>
                </div>

                <br>

                <div id="existing_member">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="member">Select Member</label>
                            <div class="col-4">
                                <input class="form-control {{ $errors->has('member_input') ? ' is-invalid' : '' }}" id="member_input" name="member_input" list="members" placeholder="Begin typing to search for members" autocomplete="off">
                                @if ($errors->has('member_input') || $errors->has('member'))
                                    <span class="invalid-feedback">
                                        <strong>Please select a valid member from the list.</strong>
                                    </span>
                                @endif
                            </div>

                            <datalist id="members" name="member_list">
                                @foreach ($members as $member)
                                    <option id="{{ $member->id }}" value="{{ $member->first_name . ' ' . $member->middle_name . ' ' . $member->last_name }}"></option>
                                @endforeach
                            </datalist>        

                            <input type="hidden" id="member" name="member" value="">
                        </div>
                    </div>
                </div>

                <div id="new_member">
                    <input type="hidden" name="account" value="{{ $account->id }}">
                    <input type="hidden" name="membership_type" value="{{ $account->membership_type }}">
                    
                    <h5>Personal Information</h5>
                    <br>
                    <div class="form-row">
                        <div class="form-group required col-md-4">
                            <label class="control-label" for="first_name">First Name</label>
                            <input type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name">
                            @if ($errors->has('first_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>                            

                        <div class="form-group col-md-4">
                            <label class="control-label" for="middle_name">Middle Name</label>
                            <input type="text" class="form-control {{ $errors->has('middle_name') ? ' is-invalid' : '' }}" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle Name">
                            @if ($errors->has('middle_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('middle_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="form-group required col-md-4">
                            <label class="control-label" for="last_name">Last Name</label>
                            <input type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name">
                            @if ($errors->has('last_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group required col-md-4">
                            <label class="control-label" for="email">Email</label>
                            <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" placeholder="E-mail Address">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div> 

                        {{-- Member Status Default: Active HIDDEN --}}
                        <div class="form-group required col-md-2" hidden>
                            <label class="control-label" for="status">Status</label>
                            <select id="status" name="status" class="form-control {{ $errors->has('status') ? ' is-invalid' : '' }}">
                                <option value="" >Choose...</option>
                                <option value="active" selected {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                                <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @if ($errors->has('status'))
                                <span class="invalid-feedback">
                                    <strong>Please select a status.</strong>
                                </span>
                            @endif
                        </div>                   
                    </div>


                    <div class="form-row">                  
                        <div class="form-group required col-md-4">
                            <label class="control-label" for="birthday">Birthday</label>
                            <input type="date" class="form-control {{ $errors->has('birthday') ? ' is-invalid' : '' }}" id="birthday" name="birthday" value="{{ old('birthday') }}" placeholder="Birthday">
                            @if ($errors->has('birthday'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('birthday') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group required col-md-2">
                            <label class="control-label" for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-control {{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                <option value="" selected>Choose...</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @if ($errors->has('gender'))
                                <span class="invalid-feedback">
                                    <strong>Please select a gender.</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group required col-md-4">
                            <label class="control-label" for="contact">Contact Number(s)</label>
                            <input type="number" class="form-control {{ $errors->has('contact') ? ' is-invalid' : '' }}" id="contact" name="contact" value="{{ old('contact') }}" placeholder="Contact Number">
                            @if ($errors->has('contact'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('contact') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group required col-md-4">
                            <label class="control-label" for="contact_type">Type</label>
                            <select id="contact_type" name="contact_type" class="form-control {{ $errors->has('contact_type') ? ' is-invalid' : '' }}">
                                <option value="" selected>Choose...</option>          <option value="home" {{ old('contact_type') == 'home' ? 'selected' : '' }}>Home</option>
                                <option value="work" {{ old('contact_type') == 'work' ? 'selected' : '' }}>Work</option>
                            </select>
                            @if ($errors->has('contact_type'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('contact_type') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <input type="number" class="form-control {{ $errors->has('contact2') ? ' is-invalid' : '' }}" id="contact2" name="contact2" value="{{ old('contact2') }}" placeholder="Contact Number">
                            @if ($errors->has('contact2'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('contact2') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <select id="contact_type2" name="contact_type2" class="form-control {{ $errors->has('contact_type2') ? ' is-invalid' : '' }}">
                                <option value="" selected>Choose...</option>          <option value="home" {{ old('contact_type2') == 'home' ? 'selected' : '' }}>Home</option>
                                <option value="work" {{ old('contact_type2') == 'work' ? 'selected' : '' }}>Work</option>
                            </select>
                            @if ($errors->has('contact_type2'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('contact_type2') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group required col-md-3">
                            <label class="control-label" for="house_number">Address</label>
                            <input type="text" class="form-control {{ $errors->has('house_number') ? ' is-invalid' : '' }}" id="house_number" name="house_number" placeholder="House / Block / Lot Number" value="{{ old('house_number') }}">
                            <small id="houseNumberHelpText" class="form-text text-muted">House Number</small>
                            @if ($errors->has('house_number'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('house_number') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label" for="subdivision">&nbsp;</label>
                            <input type="text" class="form-control {{ $errors->has('subdivision') ? ' is-invalid' : '' }}" id="subdivision" name="subdivision" placeholder="Street, Subdivision / Village" value="{{ old('subdivision') }}">
                            <small id="subdivisionHelpText" class="form-text text-muted">Subdivision</small>
                            @if ($errors->has('subdivision'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('subdivision') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label" for="barangay">&nbsp;</label>
                            <input type="text" class="form-control {{ $errors->has('barangay') ? ' is-invalid' : '' }}" id="barangay" name="barangay" placeholder="Barangay" value="{{ old('barangay') }}">
                            <small id="barangayHelpText" class="form-text text-muted">Barangay</small>
                            @if ($errors->has('barangay'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('barangay') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group required col-md-3">
                            <label class="control-label" for="city">&nbsp;</label>
                            <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" id="city" name="city" placeholder="City / Municipality" value="{{ old('city') }}">
                            <small id="cityHelpText" class="form-text text-muted">City</small>
                            @if ($errors->has('city'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('city') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group required col-md-3">
                            <label class="control-label" for="state">&nbsp;</label>
                            <input type="text" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" id="state" name="state" placeholder="State / Province / Region" value="{{ old('state') }}">
                            <small id="stateHelpText" class="form-text text-muted">State</small>
                            @if ($errors->has('state'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('state') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group required col-md-3">
                            <label class="control-label" for="country">&nbsp;</label>
                            <select class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}" id="country" name="country">
                                @foreach ($countries as $country)
                                    <option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                            <small id="countryHelpText" class="form-text text-muted">Country</small>
                            @if ($errors->has('country'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('country') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group required col-md-3">
                            <label class="control-label" for="postal_code">&nbsp;</label>
                            <input type="text" class="form-control {{ $errors->has('postal_code') ? ' is-invalid' : '' }}" id="postal_code" name="postal_code" placeholder="Postal / Zip Code" value="{{ old('postal_code') }}">
                            <small id="postalCodeHelpText" class="form-text text-muted">Postal Code</small>
                            @if ($errors->has('postal_code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('postal_code') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12 text-right">
                        <a href="/accounts/{{ $account->id }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>

@endsection


@section('scripts')
    <script>
        $(document).ready(function(){
            var selected = "{{ old('member_type') }}";

            if(selected == 'new'){
                $('#existing_member').hide();
                $('#new_member').show();
            } else {
                $('#new_member').hide();
                $('#existing_member').show();
            }            

            $('input[type=radio][name=member_type]').change(function() {
                if (this.value == 'existing') {
                    $('#new_member').hide();
                    $('#existing_member').show();
                }
                else if (this.value == 'new') {
                    $('#existing_member').hide();
                    $('#new_member').show();
                }
            });
        });

        $("#member_input").on('input', function () {
            var x = this.value;
            var z = $('#members');
            var val = $(z).find('option[value="' + x + '"]');
            var id = val.attr('id');

            $('#member').val(id);
        });
    </script>
@endsection