@extends('layouts.admin')

@section('title')
    VoucherMS | Members
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/members">Members</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Member</li>
        </ol>
    </nav>

    @if ($errors->any())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <i class="fa fa-fw fa-exclamation"></i>Your form has error(s). Please check all the fields.
            </div>
        </div>
    </div>
    @endif

    <div class="card mb-3">
        <div class="card-header">
            <h3><i class="fa fa-fw fa-user-plus"></i> Add Member</h3>
            </div>
        <div class="card-body">
            <p class="text-danger" style="display:inline-block;"><strong>*</strong></p> <p style="display:inline-block;"><em> - required fields. Please fill out.</em></p>

            <form method="POST" action="/members" id="addMemberForm" onsubmit="setFormSubmitting()">
                {{ csrf_field() }}

                <h5>Account Information</h5>
                <br>
                <legend class="col-form-label col-sm-2 pt-0">Account Type</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="account_type" id="existing" value="existing" {{ (!old('account_type') || old('account_type') == 'existing') ? 'checked' : '' }}>
                    <label class="form-check-label" for="existing">
                        Existing
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="account_type" id="new" value="new" {{ old('account_type') == 'new' ? 'checked' : '' }}>
                    <label class="form-check-label" for="new">
                        New
                    </label>
                </div>
                {{-- <div class="form-check">
                    <input class="form-check-input" type="radio" name="account_type" id="none" value="none" {{ old('account_type') == 'none' ? 'checked' : '' }}>
                    <label class="form-check-label" for="none">
                        None
                    </label>
                </div> --}}

                <br>

                <div id="existingAccount">
                    <div class="form-row">
                        <div class="form-group required col-md-4">
                            <label class="control-label" for="account_input">Account</label>
                            <input class="form-control {{ $errors->has('account_input') ? ' is-invalid' : '' }}" id="account_input" name="account_input" list="accounts" placeholder="Begin typing to search for accounts" autocomplete="off" value="{{ old('account_input') }}">
                            <datalist id="accounts">
                                @foreach ($accounts as $account)
                                    <option id="{{ $account->id }}" value="{{ $account->sales_deck }}" {{ old('account') == $account->id ? 'selected' : '' }}>
                                        @foreach ($account->accountMember as $i=>$accmember)
                                            {{ $accmember->member->first_name . ' ' . $accmember->member->middle_name . ' ' . $accmember->member->last_name }}
                                            
                                            @if (!$loop->last)
                                                {{ ', ' }}
                                            @endif
                                        @endforeach
                                    </option>
                                @endforeach
                            </datalist>
                            @if ($errors->has('account_input'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('account_input') }}</strong>
                                </span>
                            @endif

                            <input type="hidden" id="account" name="account" value="{{ old('account') }}">
                        </div>
                        <div class="col-md-1"></div>
                        <div class="form-group col-md-4">
                            <legend class="col-form-label pt-0">Account Members</legend>
                            <p id="account_members"></p>
                        </div>
                    </div>
                </div>

                <div id="createAccount">
                    <div class="form-row">
                        <div class="form-group required col-md-4">
                            <label class="control-label" for="principal_amount">Principal Amount</label>
                            <input type="number" step=".01" class="form-control {{ $errors->has('principal_amount') ? ' is-invalid' : '' }}" id="principal_amount" name="principal_amount" value="{{ old('principal_amount') }}" placeholder="Principal Amount">
                            @if ($errors->has('principal_amount'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('principal_amount') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group required col-md-4">
                            <label class="control-label" for="downpayment">Downpayment</label>
                            <input type="number" step=".01" class="form-control {{ $errors->has('downpayment') ? ' is-invalid' : '' }}" id="downpayment" name="downpayment" value="{{ old('downpayment') }}" placeholder="Downpayment">
                            @if ($errors->has('downpayment'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('downpayment') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group required col-md-4">
                            <label class="control-label" for="sales_deck">Sales Deck</label>
                            <input type="text" class="form-control {{ $errors->has('sales_deck') ? ' is-invalid' : '' }}" id="sales_deck" name="sales_deck" value="{{ old('sales_deck') }}" placeholder="Sales Deck Location">
                            @if ($errors->has('sales_deck'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('sales_deck') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group required col-md-4">
                            <label class="control-label" for="consultant">Consultant</label>
                            <input type="text" class="form-control {{ $errors->has('consultant') ? ' is-invalid' : '' }}" id="consultant" name="consultant" value="{{ old('consultant') }}" placeholder="Consultant Name">
                            @if ($errors->has('consultant'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('consultant') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

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
                        <label class="control-label" for="membership_type">Membership Type</label>
                        <input class="form-control" type="text" id="membership_type_label" value="{{ $membership_type->type }}" readonly>
                        <input class="form-control" type="hidden" name="membership_type" value="{{ $membership_type->id }}">
                    </div>

                    <div class="form-group required col-md-2">
                        <label class="control-label" for="status">Status</label>
                        <input class="form-control" type="text" id="status_label" value="Active" readonly>
                        <input class="form-control" type="hidden" name="status" value="active">
                    </div>
                </div>

                <hr>

                <div class="form-row" id="email_row">
                    <div class="form-group col-md-4">
                        <label class="control-label" for="email">Email</label>
                        <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" placeholder="(optional) E-mail Address">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <label class="control-label" for="email">Secondary Email</label>
                        <input type="email" class="form-control {{ $errors->has('second_email') ? ' is-invalid' : '' }}" id="second_email" name="second_email" value="{{ old('second_email') }}" placeholder="(optional) Secondary E-mail Address">
                        @if ($errors->has('second_email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('second_email') }}</strong>
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

                <div id="contactNumbers">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="control-label" for="contact">Contact Number(s)</label>
                            <input type="text" class="form-control {{ $errors->has('contact.0') ? ' is-invalid' : '' }}" id="contact" name="contact[]" value="{{ old('contact.0') }}" placeholder="Contact Number">
                            @if ($errors->has('contact.0'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('contact.0') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label class="control-label" for="contact_type">Type</label>
                            <select id="contact_type" name="contact_type[]" class="form-control {{ $errors->has('contact_type.0') ? ' is-invalid' : '' }}">
                                <option value="" {{ !old('contact_type.0') ? 'selected' : '' }}>Choose...</option>
                                <option value="home" {{ old('contact_type.0') == 'home' ? 'selected' : '' }}>Home</option>
                                <option value="work" {{ old('contact_type.0') == 'work' ? 'selected' : '' }}>Work</option>
                            </select>
                            @if ($errors->has('contact_type.0'))
                                <span class="invalid-feedback">
                                    <strong>Please select the contact type.</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="form-row">
                    <a id="addContactNumber" class="text-primary"><i class="fa fa-plus"></i> Add Contact Number</a>
                </div>
                <br>

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

                <div class="form-row">
                    <div class="form-group col-md-12 text-right">
                        <a href="/members"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            var selected = "{{ old('account_type') }}";

            if(selected == 'new'){
                $('#existingAccount').hide();
                $('#createAccount').show();
                $('#account_input').val('');
            } else if (selected == 'none') {
                $('#createAccount').hide();
                $('#existingAccount').hide();
            } else {
                $('#createAccount').hide();
                $('#existingAccount').show();
                $('#principal_amount').val('');
            }

            $('input[type=radio][name=account_type]').change(function() {
                if (this.value == 'existing') {
                    $("#addMemberForm").trigger('reset');
                    $('#createAccount').hide();
                    $('#existingAccount').show();
                    $('#existing').prop('checked', true);
                }
                else if (this.value == 'new') {
                    $("#addMemberForm").trigger('reset');
                    $('#existingAccount').hide();
                    $('#createAccount').show();
                    $('#new').prop('checked', true);
                } else {
                    $('#createAccount').hide();
                    $('#existingAccount').hide();                    
                }
            });
        });
    </script>

    <script>
        var formSubmitting = false;
        var setFormSubmitting = function() { formSubmitting = true; };

        window.onload = function() {
            window.addEventListener("beforeunload", function (e) {
                if (formSubmitting) {
                    return undefined;
                }

                var confirmationMessage = 'It looks like you have been editing something. '
                                        + 'If you leave before saving, your changes will be lost.';

                (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
            });
        };

        // Data passing from hidden
        $("#account_input").on('input', function () {
            var x = this.value;
            var z = $('#accounts');
            var val = $(z).find('option[value="' + x + '"]');
            var id = val.attr('id');

            $('#account').val(id);

            var members = $('#' + id).text();
            $('#account_members').text(members);
        });

        $(document).ready(function () {

            var contactID = 1;

            $('#addContactNumber').click(function () {
                contactID++;

                $('#contactNumbers').append($('<div class="form-row" id="contact'+ contactID +'"> <div class="form-group required col-md-4"><input type="number" class="form-control" id="contact' + contactID + '" name="contact[]" value="" placeholder="Contact Number"></div> <div class="form-group required col-md-4"> <select id="contact_type' + contactID + '" name="contact_type[]" class="form-control"> <option value="" selected>Choose...</option> <option value="home">Home</option> <option value="work">Work</option> </select> </div><div class="form-group col-md-1"><a id="remove' + contactID + '" class="btn-removeContact form-control-plaintext text-danger" data-id="'+ contactID +'"> <i class="fa fa-times"></i> Remove</a></div></div>'));
            });

            $('#contactNumbers').on('click', '.btn-removeContact', function() {
                var contact_id = $(this).data('id');
                $('#contact' + contact_id).remove();
            });
        });
    </script>
@endsection