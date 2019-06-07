@extends('layouts.admin')

@section('title')
    VoucherMS | Editing {{ $member->first_name . ' ' . $member->last_name }}
@endsection

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/members">Members</a></li>
            <li class="breadcrumb-item"><a href="/members/{{ $member->id }}">{{ $member->first_name . ' ' . $member->last_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="col-10">      
        <div class="card mb-3">
            <div class="card-header">
            <h2> <i class="fa fa-user"></i> Editing {{ $member->first_name . ' ' . $member->last_name }}</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="/members/{{ $member->id }}" onsubmit="setFormSubmitting()">
                    @method('PUT')
                    {{ csrf_field() }}
                    
                    <div class="form-group row">
                        <label for="member_id" class="font-weight-bold col-sm-2 col-form-label text-right">Member ID</label>
                        <div class="col-sm-1">
                            <input type="text" class="form-control-plaintext" id="member_id" name="member_id" value="{{ $member->id }}" readonly>
                        </div>

                        <label for="membership_type" class="font-weight-bold col-sm-2 col-form-label text-right">Membership Type</label>
                        <div class="col-sm-2">
                            <input type="hidden" name="membership_type" class="form-control" value="{{ $member->membership_type }}">
                            <fieldset disabled>
                            <select id="membership_select" name="membership_select" class="form-control">
                                @foreach ($membership_types as $m_type)
                                    <option value="{{ $m_type->id }}" {{ $member->membership_type == $m_type->id ? 'selected' : '' }}>{{ $m_type->type }}</option>
                                @endforeach
                            </select>
                            </fieldset>
                        </div>

                        <label for="status" class="font-weight-bold col-sm-1 col-form-label text-right">Status</label>
                        <div class="col-sm-2">
                            <select id="status" name="status" class="form-control {{ $errors->has('status') ? ' is-invalid' : '' }}">
                                <option value="" selected>Choose...</option>
                                    <option value="active" {{ old('status') == 'active' || (!old('status') && $member->status == 'active') ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' || (!old('status') && $member->status == 'inactive') ? 'selected' : '' }}>Inactive</option>
                                    <option value="blocked" {{ old('status') == 'blocked' || (!old('status') && $member->status == 'blocked') ? 'selected' : '' }}>Blocked</option>
                                    <option value="canceled" {{ old('status') == 'canceled' || (!old('status') && $member->status == 'canceled') ? 'selected' : '' }}>Canceled</option>
                                    <option value="pending" {{ old('status') == 'pending' || (!old('status') && $member->status == 'pending') ? 'selected' : '' }}>Pending</option>
                            </select>
                            @if ($errors->has('status'))
                                <span class="invalid-feedback">
                                    <strong>Please select a status.</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <br><hr><br>
                    
                    <h5 class="col-sm-10">Personal Information</h5>
                    <br>
                    <div class="form-row">
                        <div class="form-group required col-md-4">
                            <label class="control-label" for="first_name">First Name</label>
                            <input type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" id="first_name" name="first_name" value="{{ !old('first_name') ? $member->first_name : old('first_name') }}" placeholder="First Name">
                            @if ($errors->has('first_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>                            

                        <div class="form-group col-md-4">
                            <label class="control-label" for="middle_name">Middle Name</label>
                            <input type="text" class="form-control {{ $errors->has('middle_name') ? ' is-invalid' : '' }}" id="middle_name" name="middle_name" value="{{ !old('middle_name') ? $member->middle_name : old('middle_name') }}" placeholder="Middle Name">
                            @if ($errors->has('middle_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('middle_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="form-group required col-md-4">
                            <label class="control-label" for="last_name">Last Name</label>
                            <input type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" id="last_name" name="last_name" value="{{ !old('last_name') ? $member->last_name : old('last_name') }}" placeholder="Last Name">
                            @if ($errors->has('last_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-row" id="email_row">
                        <div class="form-group col-md-4">
                            <label class="control-label" for="email_address">Email</label>
                            <input type="email" class="form-control {{ $errors->has('email_address') ? ' is-invalid' : '' }}" id="email_address" name="email_address" value="{{ !old('email_address') && $primary_email != null ? $primary_email->email_address : old('email_address') }}" placeholder="(optional) E-mail Address">
                            
                            @if ($primary_email != null)
                                <input type="hidden" name="old_email_id" value="{{ $primary_email->id }}">
                                <input type="hidden" name="old_email" value="{{ $primary_email->email_address }}">
                            @endif

                            @if ($errors->has('email_address'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email_address') }}</strong>
                                </span>
                            @endif
                        </div>

                        
                        <div class="form-group col-md-4" id="email_row">
                            <label class="control-label" for="second_email">Secondary Email</label>
                           
                            @if ($secondary_email != null)
                                <input type="email" class="form-control {{ $errors->has('second_email') ? ' is-invalid' : '' }}" id="second_email" name="second_email" value="{{ !old('second_email') ? $secondary_email->email_address : old('second_email') }}" placeholder="(optional) Secondary E-mail Address">
                                <input type="hidden" name="old_second_email_id" value="{{ $secondary_email->id }}">
                                <input type="hidden" name="old_second_email" value="{{ $secondary_email->email_address }}">
                            @else
                                <input type="email" class="form-control {{ $errors->has('second_email') ? ' is-invalid' : '' }}" id="second_email" name="second_email" value="{{ old('second_email') }}" placeholder="(optional) Secondary E-mail Address">
                                <input type="hidden" name="old_second_email" value="">
                            @endif

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
                            <input type="date" class="form-control {{ $errors->has('birthday') ? ' is-invalid' : '' }}" id="birthday" name="birthday" value="{{ !old('birthday') ? $member->birthday : old('birthday') }}" placeholder="Birthday">
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
                                <option value="male" {{ old('gender') == 'male' || (!old('gender') && $member->gender == 'male') ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' || (!old('gender') && $member->gender == 'female') ? 'selected' : '' }}>Female</option>
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
                                <label class="control-label" for="main_contact">Contact Number(s)</label>
                                <input type="number" class="form-control {{ $errors->has('main_contact') ? ' is-invalid' : '' }}" id="main_contact" name="main_contact" value="{{ !old('main_contact') && $contact != null ? $contact->contact_number : old('main_contact') }}" placeholder="Contact Number">
                                @if ($errors->has('main_contact'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('main_contact') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-md-4">
                                <label class="control-label" for="main_contact_type">Type</label>
                                <select id="main_contact_type" name="main_contact_type" class="form-control {{ $errors->has('main_contact_type') ? ' is-invalid' : '' }}">
                                    <option value="" selected>Choose...</option>
                                    <option value="home" {{ old('main_contact_type') == 'home' || (!old('main_contact_type') && $contact != null && $contact->contact_type == 'home') ? 'selected' : '' }}>{{ 'home' }}</option>
                                    <option value="work" {{ old('main_contact_type') == 'work' || (!old('main_contact_type') && $contact != null && $contact->contact_type == 'work') ? 'selected' : '' }}>{{ 'work' }}</option>
                                </select>
                                @if ($errors->has('main_contact_type'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('main_contact_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if ($contact != null)
                            @foreach ($contacts as $i => $con)
                                <div class="form-row" id="contact{{ $i }}">
                                    <input type="hidden" class="contact-id" id="contact_id-{{ $i }}" name="contact_id[{{ $i }}]" value="{{ $con->id }}">
                                    <div class="form-group required col-md-4">
                                        <input type="number" class="form-control {{ $errors->has('contact['.$i.']') ? ' is-invalid' : '' }}" id="contact[{{ $i }}]" name="contact[{{ $i }}]" value="{{ !old('contact') ? $con->contact_number : old('contact['.$i.']') }}" placeholder="Contact Number">
                                        @if ($errors->has('contact['.$i.']'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('contact['.$i.']') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group required col-md-4">
                                        <select id="contact_type[{{ $i }}]" name="contact_type[{{ $i }}]" class="form-control {{ $errors->has('contact_type['.$i.']') ? ' is-invalid' : '' }}">
                                            <option value="" selected>Choose...</option>
                                            <option value="home" {{ old('contact_type['.$i.']') == 'home' || (!old('contact_type['.$i.']') && $con->contact_type == 'home') ? 'selected' : '' }}>{{ 'home' }}</option>
                                            <option value="work" {{ old('contact_type['.$i.']') == 'work' || (!old('contact_type['.$i.']') && $con->contact_type == 'work') ? 'selected' : '' }}>{{ 'work' }}</option>
                                        </select>
                                        @if ($errors->has('contact_type['.$i.']'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('contact_type['.$i.']') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-md-1">
                                        <a id="remove{{ $i }}" class="btn-removeContact form-control-plaintext text-danger" data-id="{{ $i }}"> <i class="fa fa-times"></i> Remove</a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="form-row">
                        <a id="addContactNumber" class="text-primary"><i class="fa fa-plus"></i> Add Contact Number</a>
                    </div>

                    <input type="hidden" id="removed" name="removed" value="">

                    <div class="form-row">
                        <div class="form-group required col-md-3">
                            <label class="control-label" for="house_number">Address</label>
                            <input type="text" class="form-control {{ $errors->has('house_number') ? ' is-invalid' : '' }}" id="house_number" name="house_number" placeholder="House / Block / Lot Number" value="{{ !old('house_number') ? $address->house_number : old('house_number') }}">
                            <small id="houseNumberHelpText" class="form-text text-muted">House Number</small>
                            @if ($errors->has('house_number'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('house_number') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label" for="subdivision">&nbsp;</label>
                            <input type="text" class="form-control {{ $errors->has('subdivision') ? ' is-invalid' : '' }}" id="subdivision" name="subdivision" placeholder="Street, Subdivision / Village" value="{{ !old('subdivision') ? $address->subdivision : old('subdivision') }}">
                            <small id="subdivisionHelpText" class="form-text text-muted">Subdivision</small>
                            @if ($errors->has('subdivision'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('subdivision') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label" for="barangay">&nbsp;</label>
                            <input type="text" class="form-control {{ $errors->has('barangay') ? ' is-invalid' : '' }}" id="barangay" name="barangay" placeholder="Barangay" value="{{ !old('barangay') ? $address->barangay : old('barangay') }}">
                            <small id="barangayHelpText" class="form-text text-muted">Barangay</small>
                            @if ($errors->has('barangay'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('barangay') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group required col-md-3">
                            <label class="control-label" for="city">&nbsp;</label>
                            <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" id="city" name="city" placeholder="City / Municipality" value="{{ !old('city') ? $address->city : old('city') }}">
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
                            <input type="text" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" id="state" name="state" placeholder="State / Province / Region" value="{{ !old('state') ? $address->state : old('state') }}">
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
                                    <option value="{{ $country }}" {{ old('country') == $country || (!old('country') && $address->country == $country) ? 'selected' : '' }}>{{ $country }}</option>
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
                            <input type="text" class="form-control {{ $errors->has('postal_code') ? ' is-invalid' : '' }}" id="postal_code" name="postal_code" placeholder="Postal / Zip Code" value="{{ !old('postal_code') ? $address->postal_code : old('postal_code') }}">
                            <small id="postalCodeHelpText" class="form-text text-muted">Postal Code</small>
                            @if ($errors->has('postal_code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('postal_code') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <br>

                    <div class="form-group row ">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <a href="/members/{{ $member->id }}" class="btn btn-outline-dark"><i class="fa fa-chevron-left"></i> Cancel</a>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer small text-muted">Details last updated on {{ date('m/d/Y \a\t h:i:s A', strtotime($member->updated_at)) }}</div>
        </div>
    </div>

@endsection

@section('scripts')
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

        $(document).ready(function () {
            var contactID = '{{ $contact != null ? $contacts->keys()->last() : 0 }}';

            $('#addContactNumber').click(function () {
                contactID++;

                $('#contactNumbers').append($('<div class="form-row" id="contact'+ contactID +'"> <div class="form-group required col-md-4"><input type="number" class="form-control" id="contact' + contactID + '" name="contact[' + contactID + ']" value="" placeholder="Contact Number"></div> <div class="form-group required col-md-4"> <select id="contact_type' + contactID + '" name="contact_type[' + contactID + ']" class="form-control"> <option value="" selected>Choose...</option> <option value="home">Home</option> <option value="work">Work</option> </select> </div><div class="form-group col-md-1"><a id="remove' + contactID + '" class="btn-removeContact form-control-plaintext text-danger" data-id="'+ contactID +'"> <i class="fa fa-times"></i> Remove</a></div></div>'));
            });

            $('#contactNumbers').on('click', '.btn-removeContact', function() {
                var loop_id = $(this).data('id');
                var contact_id = $('#contact_id-' + loop_id).val();

                $('#contact' + loop_id).remove(); //Remove form row

                if($('.contact-id').val()){ //If contact number is in the database
                    var removed = $('#removed').val();

                    if(removed == ""){
                        $('#removed').val(contact_id);
                    } else {
                        $('#removed').val(removed + ',' + contact_id);
                    }
                }
            });
        });
    </script>
@endsection