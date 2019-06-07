@extends('layouts.admin')

@section('title')
    VoucherMS | Accounts
@endsection

@section('content')
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
            <li class="breadcrumb-item active" aria-current="page">Members</li>
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
            <h3><i class="fa fa-fw fa-users"></i> Members</h3>
            </div>
        <div class="card-body">
            @if ($otherMembers->count() < 3)
                <p>
                    <a href="/accounts/addMember/{{ $account->id }}" class="btn btn-success pull-right"><i class="fa fa-fw fa-user-plus"></i> Add Member</a>
                </p>
            @endif

            {{-- Nav --}}
            <ul class="nav nav-tabs" id="tab-list" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="member-tab1" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Member 1</a>
                </li>
                @foreach ($otherMembers as $i => $others)
                    <li class="nav-item">
                        <a class="nav-link" id="member-tab{{ $i+2 }}" data-toggle="tab" href="#tab{{ $i+2 }}" role="tab" aria-controls="tab{{ $i+2 }}" aria-selected="false">Member {{ $i+2 }} <button class="btn-removeMember close ml-2" type="button" title="Remove this member" data-toggle="modal" data-target="#removeModal" data-id="{{ $others->id }}" data-tab="member-tab{{ $i+2 }}"> ×</button></a>
                    </li>
                @endforeach
            </ul>
           <div class="tab-content" id="nav-tabContent">
               {{-- First Member --}}
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="member-tab1">
                    <form>
                        <fieldset disabled>
                            <br>                       
                            <h5 class="col-sm-10">Personal Information</h5>
                            <br>
                            <div class="form-group row">
                                <label for="name" class="font-weight-bold col-sm-2 col-form-label text-right">Full Name: </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control-plaintext" id="name" value="{{ $firstMember->first_name . ' ' . $firstMember->middle_name . ' ' . $firstMember->last_name }}">
                                </div>

                                <div class="col-sm-2"></div>

                                <label for="birthday" class="font-weight-bold col-sm-2 col-form-label text-right">Birthday: </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control-plaintext" id="birthday" value="{{ date('M j, Y', strtotime($firstMember->birthday)) }}">
                                </div>                            
                            </div>

                            <div class="form-group row">
                                <label for="age" class="font-weight-bold col-sm-2 col-form-label text-right">Age: </label>
                                <div class="col-sm-2">
                                    @php
                                        $d1 = new DateTime(date('Y-m-d '));
                                        $d2 = new DateTime($firstMember->birthday);

                                        $diff = $d2->diff($d1);
                                    @endphp
                                    <input type="text" class="form-control-plaintext" id="age" value="{{ $diff->y }}">
                                </div>

                                <div class="col-sm-2"></div>

                                <label for="age" class="font-weight-bold col-sm-3 col-form-label text-right">Gender: </label>
                                <div class="col-sm-2">                                
                                    <input type="text" class="form-control-plaintext" id="age" value="{{ ucfirst($firstMember->gender) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="font-weight-bold col-sm-2 col-form-label text-right">Email: </label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control-plaintext" id="email" value="{{ ($firstMember->email == null) ? "none" : $firstMember->email->email_address }}">
                                </div>                                  
                            </div>

                            <div class="form-group row">
                                <label for="contact" class="font-weight-bold col-sm-2 col-form-label text-right">Contact Number(s): </label>
                                @foreach ($firstMember->contactNumbers as $contact)
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control-plaintext" id="contact" value="{{ $contact->contact_number }} - {{ $contact->contact_type }}">
                                    </div>
                                @endforeach
                            </div>

                            @foreach ($firstMember->addresses as $address)
                                <div class="form-group row">
                                    <label for="address" class="font-weight-bold col-sm-2 col-form-label text-right">Address:</label>
                                    <div class="col-sm-5">
                                        <textarea class="form-control-plaintext" style="min-width:300px; max-height:100px;" id="address">{{ $address->house_number . ' ' . $address->subdivision . ' ' . $address->barangay . ' ' . $address->city . ' ' . $address->state . ' ' . $address->country . ' ' . $address->postal_code }}</textarea>
                                    </div>
                                </div>
                            @endforeach

                            <br><hr><br>

                            <div class="form-group row">
                                <label for="id" class="font-weight-bold col-sm-3 col-form-label text-right">Member ID:</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control-plaintext" id="id" value="{{ $firstMember->id }}">
                                </div>

                                <div class="col-sm-1"></div>

                                <label for="status" class="font-weight-bold col-sm-3 col-form-label text-right">Status: </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control-plaintext font-weight-bold {{ $firstMember->status == 'active' ? 'text-success' : 'text-danger' }}" id="status" value="{{ strtoupper($firstMember->status) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="id" class="font-weight-bold col-sm-3 col-form-label text-right">Membership Type:</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control-plaintext" id="id" value="{{ $firstMember->membershipType->type }}">
                                </div>

                                <div class="col-sm-1"></div>

                                <label for="created_by" class="font-weight-bold col-sm-3 col-form-label text-right">Created By: </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control-plaintext" id="created_by" value="{{ $firstMember->created_by }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="created_at" class="font-weight-bold col-sm-3 col-form-label text-right">Created At: </label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control-plaintext" id="created_at" value="{{ date('M j, Y', strtotime($firstMember->created_at)) }}">
                                </div>

                                <div class="col-sm-1"></div>

                                <label for="updated_at" class="font-weight-bold col-sm-3 col-form-label text-right">Updated At: </label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control-plaintext" id="updated_at" value="{{ date('M j, Y', strtotime($firstMember->updated_at)) }}">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>

                {{-- Other Members --}}
                @foreach ($otherMembers as $i => $others)
                    <div class="tab-pane fade" id="tab{{ $i+2 }}" role="tabpanel" aria-labelledby="member-tab{{ $i+2 }}">
                        <form>
                            <fieldset disabled>
                                <br>                                
                                <h5 class="col-sm-10">Personal Information</h5>
                                <br>
                                <div class="form-group row">
                                    <label for="name" class="font-weight-bold col-sm-2 col-form-label text-right">Full Name: </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control-plaintext" id="name" value="{{ $others->first_name . ' ' . $others->middle_name . ' ' . $others->last_name }}">
                                    </div>

                                    <div class="col-sm-2"></div>

                                    <label for="birthday" class="font-weight-bold col-sm-2 col-form-label text-right">Birthday: </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control-plaintext" id="birthday" value="{{ date('M j, Y', strtotime($others->birthday)) }}">
                                    </div>                            
                                </div>

                                <div class="form-group row">
                                    <label for="age" class="font-weight-bold col-sm-2 col-form-label text-right">Age: </label>
                                    <div class="col-sm-2">
                                        @php
                                            $d1 = new DateTime(date('Y-m-d '));
                                            $d2 = new DateTime($others->birthday);

                                            $diff = $d2->diff($d1);
                                        @endphp
                                        <input type="text" class="form-control-plaintext" id="age" value="{{ $diff->y }}">
                                    </div>

                                    <div class="col-sm-2"></div>

                                    <label for="age" class="font-weight-bold col-sm-3 col-form-label text-right">Gender: </label>
                                    <div class="col-sm-2">                                
                                        <input type="text" class="form-control-plaintext" id="age" value="{{ ucfirst($others->gender) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="font-weight-bold col-sm-2 col-form-label text-right">Email: </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control-plaintext" id="email" value="{{ ($others->email == null) ? "none" :  $others->email->email_address }}">
                                    </div>                                  
                                </div>

                                <div class="form-group row">
                                    <label for="contact" class="font-weight-bold col-sm-2 col-form-label text-right">Contact Number(s): </label>
                                    @foreach ($others->contactNumbers as $contact)
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control-plaintext" id="contact" value="{{ $contact->contact_number }} - {{ $contact->contact_type }}">
                                        </div>
                                    @endforeach
                                </div>

                                @foreach ($others->addresses as $address)
                                    <div class="form-group row">
                                        <label for="address" class="font-weight-bold col-sm-2 col-form-label text-right">Address:</label>
                                        <div class="col-sm-5">
                                            <textarea class="form-control-plaintext" style="min-width:300px; max-height:100px;" id="address">{{ $address->house_number . ' ' . $address->subdivision . ' ' . $address->barangay . ' ' . $address->city . ' ' . $address->state . ' ' . $address->country . ' ' . $address->postal_code }}</textarea>
                                        </div>
                                    </div>
                                @endforeach

                                <br><hr><br>
                                
                                <div class="form-group row">
                                    <label for="id" class="font-weight-bold col-sm-3 col-form-label text-right">Member ID:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control-plaintext" id="id" value="{{ $others->id }}">
                                    </div>

                                    <div class="col-sm-1"></div>

                                    <label for="status" class="font-weight-bold col-sm-3 col-form-label text-right">Status: </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control-plaintext font-weight-bold {{ $others->status == 'active' ? 'text-success' : 'text-danger' }}" id="status" value="{{ strtoupper($others->status) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="id" class="font-weight-bold col-sm-3 col-form-label text-right">Membership Type:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control-plaintext" id="id" value="{{ $others->membershipType->type }}">
                                    </div>

                                    <div class="col-sm-1"></div>

                                    <label for="created_by" class="font-weight-bold col-sm-3 col-form-label text-right">Created By: </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control-plaintext" id="created_by" value="{{ $others->created_by }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="created_at" class="font-weight-bold col-sm-3 col-form-label text-right">Created At: </label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control-plaintext" id="created_at" value="{{ date('M j, Y', strtotime($others->created_at)) }}">
                                    </div>

                                    <div class="col-sm-1"></div>

                                    <label for="updated_at" class="font-weight-bold col-sm-3 col-form-label text-right">Updated At: </label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control-plaintext" id="updated_at" value="{{ date('M j, Y', strtotime($others->updated_at)) }}">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="removeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="/accounts/removeMember/{{ $account->id }}">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h5 class="modal-title" id="removeModalLabel">Remove Member</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to remove this member from this account?</p>
                        <input type="hidden" name="account" id="account" value="{{ $account->id }}">
                        <input type="hidden" name="member" id="member" value="">
                        <input type="hidden" name="tab" id="tab" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    {{-- Tab --}}
    <script>
        $(document).ready(function () {

            var count = '{{ $otherMembers->count() + 1 }}';
            var tabID = count;

            $('#btn-add-tab').click(function () {

               tabID++;
               count++;

               if(count == 4) {
                   $('#btn-add-tab').hide();
               }
                // <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab">Member No. 1</a>
                $('#tab-list').append($('<li class="nav-item"><a class="nav-link" id="member-tab' + tabID + '" data-toggle="tab" href="#tab' + tabID + '" role="tab" aria-controls="tab' + tabID + '" aria-selected="false">Member ' + tabID + ' <button class="close ml-2" type="button" title="Remove this member"> ×</button></a></li>'));

                $('#nav-tabContent').append($('<div class="tab-pane fade" id="tab' + tabID + '" role="tabpanel" aria-labelledby="member-tab' + tabID + '">Content Tab '+ tabID +' content</div>'));
            });

            $('#tab-list').on('click', '.btn-removeMember', function() {
                var member_id = $(this).data('id');
                var tab = $(this).data('tab');

                $('.modal-body #member').val(member_id);
                $('.modal-body #tab').val(tab);
            });

            // $('#tab-list').on('click','.close',function() {
            //     count--;

            //     var tabID = $(this).parents('a').attr('href');
            //     $(this).parents('li').remove();
            //     $(tabID).remove();

            //     if (count < 4){
            //         $('#btn-add-tab').show();
            //     }

            //     //display first tab
            //     var tabFirst = $('#tab-list a:first');
            //     tabFirst.tab('show');
            // });
        });
    </script>

    {{-- <script>
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
    </script> --}}
@endsection