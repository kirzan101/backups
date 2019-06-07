@extends('layouts.admin')

@section('title')
    VoucherMS | Voucher  #{{ $voucher->card_number }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/vouchers">Vouchers</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $voucher->card_number }}</li>
        </ol>
    </nav>
    <div class="col-md-12">
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
                        <h2> <i class="fa fa-envelope"></i>  {{ $voucher->card_number }}</h2>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4 text-right">
                        <a class="btn btn-outline-success" href="/vouchers/{{ $voucher->id }}/edit"><i class="fa fa-edit"></i> Edit Voucher</a>
                        {{-- @if( $voucher->status == 'unused' )
                            @method('DELETE')
                            @csrf
                        <a class="btn btn-outline-danger" href="/vouchers/{{ $voucher->id }}/destroy"><i class="fa fa-trash"></i> Delete Voucher</a>
                        @endif --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form>
                    <fieldset disabled>
                        <h5 class="col-sm-10">Account Information</h5>
                        <br>
                        <div class="form-group row">
                            <label for="sales_deck" class="font-weight-bold col-sm-2 col-form-label text-right">Sales Deck: </label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control-plaintext" id="sales_deck" value="{{ $account->sales_deck }}">
                            </div>

                            <label for="m_type" class="font-weight-bold col-sm-2 col-form-label text-right">Membership Type: </label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control-plaintext" id="m_type" value="{{ $memberType->type }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4">
                                
                            </div>
                            <label for="consultant" class="font-weight-bold col-sm-3 col-form-label text-right">Consultant: </label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control-plaintext font-weight-bold" id="consultant" value="{{ $consultant->name }}">
                            </div>
                        </div>

                        <hr><br>

                        <h5 class="col-sm-10">Member(s) Information</h5>
                        <br>

                        @foreach($members as $member)
                            <div class="form-group row">
                                <label for="name" class="font-weight-bold col-sm-2 col-form-label text-right">Name: </label>
                                <div class="col-sm-2">
                                    <a class="form-control-plaintext" id="card_number" href="/members/{{ $member->id }}">
                                        {{ $member->first_name . ' ' . $member->last_name }}
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        <hr><br>
                        
                        <h5 class="col-sm-10">Voucher Information</h5>
                        <br>
                        <div class="form-group row">
                            <label for="name" class="font-weight-bold col-sm-2 col-form-label text-right" >Voucher No: </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control-plaintext" id="card_number" value="{{ $voucher->card_number }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date_issued" class="font-weight-bold col-sm-2 col-form-label text-right">Date Issued: </label>
                            <div class="col-sm-2 col-md-3">
                                <input type="text" class="form-control-plaintext" id="date_issued" value="{{ date('M d, Y', strtotime($voucher->date_issued)) }}">
                            </div>  

                            <label for="voucher_status" class="font-weight-bold col-sm-2 col-form-label text-right">Voucher Status: </label>
                            <div class="col-sm-2 col-md-3">
                                <input type="text" class="form-control-plaintext" id="voucher_status" value="{{ strtoupper($voucher->status) }}">
                            </div>                                
                        </div>

                        <div class="form-group row">
                            <label for="valid_from" class="font-weight-bold col-sm-2 col-form-label text-right">Valid From: </label>
                            <div class="col-sm-2 col-md-3">
                                <input type="text" class="form-control-plaintext" id="valid_from" value="{{ date('M d, Y', strtotime($voucher->valid_from)) }}">
                            </div>

                            <label for="valid_to" class="font-weight-bold col-sm-2 col-form-label text-right">Valid To: </label>
                            <div class="col-sm-2 col-md-3">
                                <input type="text" class="form-control-plaintext" id="valid_to" value="{{ date('M d, Y', strtotime($voucher->valid_to)) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="destination" class="font-weight-bold col-sm-2 col-form-label text-right">Destination: </label>
                            <div class="col-sm-2 col-md-3">
                                <input type="text" class="form-control-plaintext" id="destination" value="{{ $voucher->destination->destination_name }}">
                            </div>
                        </div>

                        @if ($voucher->date_redeemed != null)
                            <div class="form-group row">
                                <label for="date_redeemed" class="font-weight-bold col-sm-2 col-form-label text-right">Date Redeemed: </label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control-plaintext" id="date_redeemed" value="{{ date('M d, Y', strtotime($voucher->date_redeemed)) }}">
                                </div>

                                <label for="check_in" class="font-weight-bold col-sm-2 col-form-label text-right">Check In: </label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control-plaintext" id="check_in" value="{{ date('M d, Y \a\t h:i a', strtotime($voucher->check_in)) }}">
                                </div>

                                <label for="check_out" class="font-weight-bold col-sm-2 col-form-label text-right">Check Out: </label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control-plaintext" id="check_out" value="{{ date('M d, Y \a\t h:i a', strtotime($voucher->check_out)) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="guest" class="font-weight-bold col-sm-2 col-form-label text-right">Guest Name: </label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control-plaintext" id="guest" value="{{ $voucher->guest_first_name . ' ' . $voucher->guest_last_name }}">
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label for="remarks" class="font-weight-bold col-sm-2 col-form-label text-right">Remarks:</label>
                            <div class="col-sm-6">
                                <textarea class="form-control-plaintext" id="remarks" style="resize: none;">{{ $voucher->remarks }}</textarea>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="card-footer small text-muted">Details last updated on {{ date('m/d/Y \a\t h:i:s A', strtotime($voucher->updated_at)) }}</div>
        </div>
    </div>

@endsection