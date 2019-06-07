@extends('layouts.admin')

@section('title')
    VoucherMS | Editing Voucher #{{ $voucher->card_number }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/vouchers">Vouchers</a></li>
            <li class="breadcrumb-item"><a href="/vouchers/{{ $voucher->id }}">{{ $voucher->card_number }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
    <div class="col-12">              
        <div class="card mb-3">
            <div class="card-header">
            <h2> <i class="fa fa-envelope"></i> Voucher {{ $voucher->card_number }}</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="/vouchers/{{ $voucher->id }}">
                    @method('PUT')
                    {{ csrf_field() }}

                    {{-- <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                    </ul> --}}

                    <fieldset disabled>
                        <div class="form-group row">
                            <label for="sales_deck" class="font-weight-bold col-sm-2 col-form-label text-right">Sales Deck</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control-plaintext" id="sales_deck" value="{{ $account->sales_deck }}">
                            </div>
                            
                            <label for="membership_type" class="font-weight-bold col-sm-2 col-form-label text-right">Membership Type</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control-plaintext" id="membership_type" value="{{ $memberType->type }}">
                            </div>
                            
                            <label for="consultant" class="font-weight-bold col-sm-2 col-form-label text-right">Consultant</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control-plaintext" id="consultant" value="{{ $consultant->name }}">
                            </div>
                        </div>
                    </fieldset>
                    
                    <hr><br>

                        <h5 class="col-sm-10">Member(s) Information</h5>
                        <br>

                        <fieldset disabled>
                            @foreach($members as $member)
                                <div class="form-group row">
                                    <label for="name" class="font-weight-bold col-sm-2 col-form-label text-right">Name: </label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control-plaintext" id="card_number" value="{{ $member->first_name . ' ' . $member->last_name }}">
                                    </div>
                                </div>
                            @endforeach
                        </fieldset>

                    <hr><br>
                    
                    <h5 class="col-sm-10">Voucher Information</h5>
                    <br>
                    <div class="form-group row">
                        <input type="hidden" name="id" id="id" value="{{ $voucher->id}}">
                        {{-- <label for="card_number" class="font-weight-bold col-sm-2 col-form-label text-right">Voucher No.</label>
                            <div class="col-sm-2">
                                <input type="text" readonly name="card_number" class="form-control-plaintext" id="card_number" value="{{ $voucher->card_number }}">
                            </div> --}}
                        
                        <label for="date_issued" class="font-weight-bold col-sm-1 col-md-2 col-form-label text-right sm-text-left">Date Issued</label>
                            <div class="col-sm-4 col-md-4">
                                <input type="{{ strtolower($voucher->status) != ('unused') ? 'text' : 'date' }}" name="date_issued" class="{{ strtolower($voucher->status) != ('unused') ? 'form-control-plaintext' : 'form-control' }} {{ $errors->has('date_issued') ? ' is-invalid' : '' }}" id="date_issued" name="date_issued" value="{{ !old('date_issued') ? $voucher->date_issued : old('date_issued')  }}" {{ strtolower($voucher->status) != "unused" ? 'readonly' : '' }}>
                                @if ($errors->has('date_issued'))
                                    <div class="text-danger">
                                        <small><strong>{{ $errors->first('date_issued') }}</strong></small>
                                    </div>
                                @endif
                            </div>  

                        <label for="voucher_status" class="font-weight-bold col-sm-1 col-md-1 col-form-label text-right" >Voucher Status</label>
                            <div class="col-sm-4 col-md-4">
                                @if (strtolower($voucher->status) != 'unused')
                                    <input id="voucher_status" name="status" class="form-control-plaintext" value="{{ strtolower($voucher->status) }}" readonly>
                                @else
                                    <select id="voucher_status" name="status" class="form-control {{ $errors->has('status') ? ' is-invalid' : '' }}" {{ strtolower($voucher->status) != "unused" ? 'readonly' : '' }} >
                                        <option value="" selected>Choose...</option>
                                        <option value="unused" {{ old('status') == "unused" || (!old('status') && strtolower($voucher->status) == "unused") ? 'selected' : '' }}>Unused</option>
                                        <option value="redeemed" {{ old('status') == "redeemed" || (!old('status') && strtolower($voucher->status) == "redeemed") ? 'selected' : '' }}>Redeemed</option>
                                        <option value="forfeited" {{ old('status') == "forfeited" || (!old('status') && strtolower($voucher->status) == "forfeited") ? 'selected' : '' }}>Forfeited</option>
                                        <option value="canceled" {{ old('status') == "canceled" || (!old('status') && strtolower($voucher->status) == "canceled") ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <span class="invalid-feedback">
                                            <strong>Please select a status.</strong>
                                        </span>
                                    @endif
                                @endif
                            </div>
                    </div>

                    <div class="form-group row">
                        <label for="valid_from" class="font-weight-bold col-sm-2 col-md-2 col-form-label text-right">Valid From</label>
                        <div class="col-sm-2 col-md-4">
                            <input type="{{ strtolower($voucher->status) != ('unused') ? 'text' : 'date' }}" name="valid_from" class="{{ strtolower($voucher->status) != ('unused') ? 'form-control-plaintext' : 'form-control' }} {{ $errors->has('date_issued') ? ' is-invalid' : '' }}" id="valid_from" value="{{ !old('valid_from') ? $voucher->valid_from : old('valid_from') }}" {{ strtolower($voucher->status) != "unused" ? 'readonly' : '' }}>
                            @if ($errors->has('valid_from'))
                                <div class="text-danger">
                                    <small><strong>{{ $errors->first('valid_from') }}</strong></small>
                                </div>
                            @endif
                        </div>

                        <label for="valid_to" class="font-weight-bold col-sm-2 col-md-1 col-form-label text-right">Valid To</label>
                        <div class="col-sm-2 col-md-4">
                            <input type="{{ strtolower($voucher->status) != ('unused') ? 'text' : 'date' }}" name="valid_to" class="{{ strtolower($voucher->status) != ('unused') ? 'form-control-plaintext' : 'form-control' }} {{ $errors->has('valid_to') ? ' is-invalid' : '' }}" id="valid_to" value="{{ !old('valid_to') ? $voucher->valid_to : old('valid_to') }}" {{ strtolower($voucher->status) != "unused" ? 'readonly' : '' }}>
                            @if ($errors->has('valid_to'))
                                <div class="text-danger">
                                    <small><strong>{{ $errors->first('valid_to') }}</strong></small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="destination" class="font-weight-bold col-sm-2 col-md-2 col-form-label text-right">Destination</label>
                        <div class="col-sm-10 col-md-9">
                            @if (strtolower($voucher->status) != 'unused')
                                <input id="destination_label" name="destination_label" class="form-control-plaintext" value="{{ $voucher->destination->destination_name }}" readonly>
                                <input type="hidden" id="destination" name="destination" value="{{ $voucher->destination_id }}">
                            @else
                                <select class="form-control {{ $errors->has('destination') ? ' is-invalid' : '' }}" id="destination" name="destination">
                                    <option value="">Choose...</option>
                                    @foreach ($destinations as $des)
                                        <option value="{{ $des->id }}"{{ (!old('destination') && $voucher->destination_id == $des->id) || (old('destination') == $des->id) ? 'selected' : '' }}>{{ $des->code }} - {{ $des->destination_name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        @if ($errors->has('destination'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('destination') }}</strong>
                            </span>
                        @endif
                    </div>


                    <div id="redeemed">
                        <div class="form-group row">
                            <label for="date_redeemed" class="font-weight-bold col-sm-2 col-form-label text-right">Date Redeemed</label>
                            <div class="col-sm-9 col-md-9">
                                <input type="date" name="date_redeemed" class="form-control {{ $errors->has('date_redeemed') ? ' is-invalid' : '' }}" id="date_redeemed" value="{{ !old('date_redeemed') ? $voucher->date_redeemed : old('date_redeemed') }}">
                                @if ($errors->has('date_redeemed'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('date_redeemed') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="check_in_date" class="font-weight-bold col-sm-2 col-form-label text-right">Check In</label>
                            <div class="col-sm-3 col-md-3">
                                <input type="date" name="check_in_date" class="form-control {{ $errors->has('check_in_date') ? ' is-invalid' : '' }}" id="check_in_date" value="{{ !old('check_in_date') ? date('Y-m-d', strtotime($voucher->check_in)) : old('check_in_date') }}">
                                @if ($errors->has('check_in_date'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('check_in_date') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="check_in_time" class="font-weight-bold col-sm-2 col-form-label text-right">Time</label>
                            <div class="col-sm-3 col-md-3">
                                <input type="time" name="check_in_time" class="form-control {{ $errors->has('check_in_time') ? ' is-invalid' : '' }}" id="check_in_time" value="{{ !old('check_in_time') ? date('H:i', strtotime($voucher->check_in)) : old('check_in_time') }}">
                                @if ($errors->has('check_in_time'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('check_in_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="check_out_date" class="font-weight-bold col-sm-2 col-form-label text-right">Check Out</label>
                            <div class="col-sm-3 col-md-3">
                                <input type="date" name="check_out_date" class="form-control {{ $errors->has('check_out_date') ? ' is-invalid' : '' }}" id="check_out_date" value="{{ !old('check_out_date') && $voucher->check_out != null ? date('Y-m-d', strtotime($voucher->check_out)) : old('check_out_date') }}">
                                @if ($errors->has('check_out_date'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('check_out_date') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="check_out_time" class="font-weight-bold col-sm-2 col-form-label text-right">Time</label>
                            <div class="col-sm-3 col-md-3">
                                <input type="time" name="check_out_time" class="form-control {{ $errors->has('check_out_time') ? ' is-invalid' : '' }}" id="check_out_time" value="{{ !old('check_out_time') ? date('H:i', strtotime($voucher->check_out)) : old('check_out_time') }}">
                                @if ($errors->has('check_out_time'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('check_out_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-check ml-5">
                                <input class="form-check-input" type="checkbox" value="on" id="check_guest" name="check_guest" {{ old('check_guest') ? 'checked' : '' }}>
                                <label class="form-check-label" for="check_guest">
                                    Redeemed by other guest
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group row mt-3" id="row_members">
                            <label for="members_list" class="font-weight-bold col-sm-2 col-form-label text-right">Members</label>
                            <div class="col-md-10">
                                <input type="text" readonly class="form-control" id="members_list" name="members_list" value="@foreach($members as $mem){{ $mem->first_name . ' ' . $mem->last_name }}@if(!$loop->last){{ "," }} @endif @endforeach ">
                            </div>
                        </div>

                        <div class="form-group row" id="guest_row">
                            <label for="guest_first_name" class="font-weight-bold col-sm-2 col-form-label text-right">Guest First Name</label>
                            <div class="col-sm-2">
                                <input type="text" name="guest_first_name" class="form-control {{ $errors->has('guest_first_name') ? ' is-invalid' : '' }}" id="guest_first_name" value="{{ !old('guest_first_name') ? $voucher->guest_first_name : old('guest_first_name') }}">
                                @if ($errors->has('guest_first_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('guest_first_name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="guest_middle_name" class="font-weight-bold col-sm-2 col-form-label text-right">Guest Middle Name</label>
                            <div class="col-sm-2">
                                <input type="text" name="guest_middle_name" class="form-control {{ $errors->has('guest_middle_name') ? ' is-invalid' : '' }}" id="guest_middle_name" value="{{ !old('guest_middle_name') ? $voucher->guest_middle_name : old('guest_middle_name') }}">
                                @if ($errors->has('guest_middle_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('guest_middle_name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="guest_last_name" class="font-weight-bold col-sm-2 col-form-label text-right">Guest Last Name</label>
                            <div class="col-sm-2">
                                <input type="text" name="guest_last_name" class="form-control {{ $errors->has('guest_last_name') ? ' is-invalid' : '' }}" id="guest_last_name" value="{{ !old('guest_last_name') ? $voucher->guest_last_name : old('guest_last_name') }}">
                                @if ($errors->has('guest_last_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('guest_last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="remarks" class="font-weight-bold col-sm-2 col-form-label text-right">Remarks</label>
                        <div class="col-sm-10">
                            <input id="remarks" name="remarks" class="{{ strtolower($voucher->status) != ('unused') && strtolower($voucher->status) != ('redememed') ? 'form-control-plaintext' : 'form-control' }} {{ $errors->has('remarks') ? ' is-invalid' : '' }}"  value="{{ !old('remarks') ? $voucher->remarks : old('remarks') }}">
                            {{-- {{ strtolower($voucher->status) != "unused" ? 'readonly' : '' }} --}}
                            @if ($errors->has('remarks'))
                                <div class="text-danger">
                                    <small><strong>{{ $errors->first('remarks') }}</strong></small>
                                </div>
                            @endif
                        </div>                        
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-12 text-right">                      
                            <a href="/vouchers/{{ $voucher->id }}" class="btn btn-danger mr-2"><i class="fa fa-times"></i> Cancel</a>
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var status = $("#voucher_status").val();
            if(status != 'redeemed'){
                $("#redeemed").hide();
            }

            if ($('#check_guest').is(':checked')){
                $("#guest_row").show();
                $("#row_members").hide();
            } else {
                $("#guest_row").hide();
                $("#guest_first_name").val('');
                $("#guest_last_name").val('');
                $("#row_members").show();
            }
        });

        $("#voucher_status").change(function () {
            var selected = this.value;

            if (selected === 'redeemed') {
                $("#redeemed").show();
            } else {
                $("#redeemed").hide();
            }
        });

        $('#check_guest').click(function() {
            $("#guest_row").toggle(this.checked);
            $("#guest_first_name").val('');
            $("#guest_last_name").val('');
            $("#row_members").toggle(!this.checked);
        });

    </script>
@endsection