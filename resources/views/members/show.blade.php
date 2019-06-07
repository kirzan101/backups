@extends('layouts.admin')

@section('title')
    VoucherMS | {{ $member->first_name . ' ' . $member->last_name }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/members">Members</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $member->first_name . ' ' . $member->last_name }}</li>
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

    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h2> <i class="fa fa-user"></i>  {{ $member->first_name . ' ' . $member->last_name }}</h2>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-4 text-right">
                                <a class="btn btn-outline-success" href="/members/{{ $member->id }}/edit"><i class="fa fa-edit"></i> Edit Member</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <fieldset disabled>
                                <div class="form-group row">
                                    <label for="id" class="font-weight-bold col-sm-3 col-form-label text-right">Member ID:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control-plaintext" id="id" value="{{ $member->id }}">
                                    </div>

                                    <div class="col-sm-1"></div>

                                    <label for="status" class="font-weight-bold col-sm-3 col-form-label text-right">Status: </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control-plaintext font-weight-bold {{ $member->status == 'active' ? 'text-success' : 'text-danger' }}" id="status" value="{{ strtoupper($member->status) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="id" class="font-weight-bold col-sm-3 col-form-label text-right">Membership Type:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control-plaintext" id="id" value="{{ $memberType->type }}">
                                    </div>

                                    <div class="col-sm-1"></div>

                                    <label for="created_by" class="font-weight-bold col-sm-3 col-form-label text-right">Created By: </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control-plaintext" id="created_by" value="{{ $member->created_by }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="created_at" class="font-weight-bold col-sm-3 col-form-label text-right">Created At: </label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control-plaintext" id="created_at" value="{{ date("d M Y, h:i:s A", strtotime($member->created_at)) }}">
                                    </div>

                                    <div class="col-sm-1"></div>

                                    <label for="updated_at" class="font-weight-bold col-sm-3 col-form-label text-right">Updated At: </label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control-plaintext" id="updated_at" value="{{ date("d M Y, h:i:s A", strtotime($member->updated_at)) }}">
                                    </div>
                                </div>

                                <br><hr><br>
                                
                                <h5 class="col-sm-10">Personal Information</h5>
                                <br>
                                <div class="form-group row">
                                    <label for="name" class="font-weight-bold col-sm-2 col-form-label text-right">Full Name: </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control-plaintext" id="name" value="{{ $member->first_name . ' ' . $member->middle_name . ' ' . $member->last_name }}">
                                    </div>

                                    <div class="col-sm-2"></div>

                                    <label for="birthday" class="font-weight-bold col-sm-2 col-form-label text-right">Birthday: </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control-plaintext" id="birthday" value="{{ date('M j, Y', strtotime($member->birthday)) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="age" class="font-weight-bold col-sm-2 col-form-label text-right">Age: </label>
                                    <div class="col-sm-2">
                                        @php
                                            $d1 = new DateTime(date('Y-m-d '));
                                            $d2 = new DateTime($member->birthday);

                                            $diff = $d2->diff($d1);
                                        @endphp
                                        <input type="text" class="form-control-plaintext" id="age" value="{{ $diff->y }}">
                                    </div>

                                    <div class="col-sm-2"></div>

                                    <label for="age" class="font-weight-bold col-sm-3 col-form-label text-right">Gender: </label>
                                    <div class="col-sm-2">                                
                                        <input type="text" class="form-control-plaintext" id="age" value="{{ ucfirst($member->gender) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="font-weight-bold col-sm-2 col-form-label text-right">Email(s): </label>
                                    <div class="col-sm-4">
                                        @foreach($emails as $i=>$email)
                                            <input type="text" class="form-control-plaintext" id="email{{ $i }}" value="{{ $email->email_address }}">
                                        @endforeach
                                    </div>
                                    
                                    <label for="contact" class="font-weight-bold col-sm-3 col-form-label text-right">Contact Number(s): </label>
                                    <div class="col-sm-3">
                                            @foreach ($contacts as $i=>$contact)
                                                <input type="text" class="form-control-plaintext" id="contact{{ $i }}" value="{{ $contact->contact_number }} ({{ $contact->contact_type }})">
                                            @endforeach
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="address" class="font-weight-bold col-sm-2 col-form-label text-right">Address:</label>
                                    <div class="col-sm-8">
                                        <label class="form-control-plaintext" id="address">
                                            @if ($address->house_number)
                                                {{ $address->house_number . ' ' . $address->subdivision . ' ' . $address->barangay . ' ' . $address->city . ' ' . $address->state . ' ' . $address->country . ' ' . $address->postal_code }}
                                            @else
                                                {{ $address->complete_address }}
                                            @endif                            
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                </div>            
                            </fieldset>
                        </form>
                    </div>
                </div> {{-- card --}}

                 {{-- accounts --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h2> <i class="fa fa-envelope"></i>  Accounts</h2>
                            </div>
                            <div class="col-2"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($accounts as $account)
                            <div class="form-group row">
                                <label for="id" class="font-weight-bold col-sm-1 col-form-label text-right">ID: </label>
                                <div class="col-sm-1">
                                    <a class="form-control-plaintext" id="id" href="/accounts/{{ isset( $account->id) ? " $account->id" : " " }}">{{ isset( $account->id) ? " $account->id" : " " }}</a>
                                </div> 

                                <label for="consultant" class="font-weight-bold col-sm-2 col-form-label text-right">Consultant: </label>
                                <div class="col-sm-2">
                                    <input type="text" readonly class="form-control-plaintext" id="consultant" value="{{ $account->consultant->name }}">
                                </div> 

                                <label for="id" class="font-weight-bold col-sm-2 col-form-label text-right">Sales Deck: </label>
                                <div class="col-sm-4">
                                    <input type="text" readonly class="form-control-plaintext" id="id" value="{{ $account->sales_deck }}">
                                </div>                    
                            </div>
                            <div class="form-group-row">
                                <label class="font-weight-bold col-sm-2 col-form-label">Members: </label>
                                <table class="table table-sm table-borderless">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($account->members as $member)
                                            <tr>
                                                <td>{{ $member->id }}</td>
                                                <td><a href="/members/{{ $member->id }}"> {{ $member->first_name . ' ' . $member->last_name }}</a></td>
                                                <td>{{ $member->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if (!$loop->last)
                                <br><hr><br>
                            @endif
                        @endforeach                
                    </div>
                </div> {{-- end of accounts --}}

                {{-- vouchers --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h2> <i class="fa fa-envelope"></i>  Voucher</h2>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-4 text-right">
                                <button class="btn btn-outline-success" data-toggle="modal" data-target="#addVoucher"><i class="fa fa-plus"></i> Add Voucher</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($vouchers->count() == 0)
                            <div class="alert alert-warning" role="alert">
                                No vouchers yet.
                            </div>
                        @else
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Voucher No.</th>
                                    <th scope="col">Details</th>
                                    <th scope="col">Destination</th>
                                    <th scope="col">Date Redeemed</th>
                                    <th scope="col">Date Issued</th>
                                    <th scope="col">Valid From</th>
                                    <th scope="col">Valid To</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vouchers as $voucher)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $voucher->card_number }}</td>
                                    <td>{{ $voucher->remarks }}</td>
                                    <td>{{ $voucher->destination->destination_name }}</td>
                                    <td>{{ $voucher->date_redeemed }}</td>
                                    <td>{{ $voucher->date_issued }}</td>
                                    <td>{{ $voucher->valid_from }}</td>
                                    <td>{{ $voucher->valid_to }}</td>
                                    <td><p class="font-weight-bold text-success">{{ strtoupper($voucher->status) }}</p></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div> {{-- end of vouchers --}}

                {{-- Payments --}}
                {{-- <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h2> <i class="fa fa-shopping-cart"></i> Invoices / Payments</h2>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-4 text-right">
                                @if($invoices->count() > 0)
                                    <button class="btn btn-outline-success" data-toggle="modal" data-target="#addPayment" ><i class="fa fa-plus"></i> Add Payment</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($invoices->count() == 0)
                            <div class="alert alert-warning" role="alert">
                                No invoices yet.
                            </div>
                        @else
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Invoice No.</th>
                                    <th scope="col">Principal Amount</th>
                                    <th scope="col">Downpayment</th>
                                    <th scope="col">Total Amount Paid</th>
                                    <th scope="col">Remaining Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->id}}</td>
                                        <td>{{ number_format($invoice->principal_amount,2)}}</td>
                                        <td>{{ number_format($invoice->downpayment,2)}}</td>
                                        <td>{{ number_format($invoice->total_paid_amount,2)}}</td>
                                        <td>{{ number_format($invoice->remaining_balance,2)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div> --}}

            </div> {{-- col-12 --}}


            {{-- modal Add Payment --}}
            <div class="modal fade bd-example-modal-md" id="addPayment" tabindex="-1" role="dialog" aria-labelledby="addPaymentLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <form method="POST" action='/members/createPayment'>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addPaymentLabel"><i class="fa fa-fw fa-plus"></i> Add Payment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">                        
                                {{ csrf_field() }}
                                
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="invoice_input">Invoice No.</label>
                                        <input type="hidden" class="form-control" id="member_id" name="member_id" value="{{ $member->id }}">
                                        <select class="form-control {{ $errors->has('invoice') ? ' is-invalid' : '' }}" id="invoice" name="invoice">
                                            @foreach ($invoices as $invoice) 
                                                <option id="{{ $invoice->id }}" value="{{ $invoice->id }}" {{ old('invoice') == $invoice->id ? 'selected' : '' }}>
                                                    {{ $invoice->id }} (
                                                        @foreach($invoice->account->accountMember as $name)
                                                            {{ $name->member->first_name . " " . $name->member->last_name }}

                                                            @if (!$loop->last)
                                                                {{ ', ' }}                                                            
                                                            @endif
                                                        @endforeach
                                                    )
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- <input class="form-control {{ $errors->has('invoice') ? ' is-invalid' : '' }}" id="invoice_input" name="invoice_input" list="invoices" placeholder="Begin typing to search for accounts" autocomplete="off">
                                        <datalist id="invoices">
                                            @foreach ($invoices as $invoice) 
                                                <option id="{{ $invoice->id }}" value="{{ $invoice->id }}" {{ $invoice->id == $invoice->id ? 'selected' : '' }} {{ $invoice->remaining_balance < 1 ? "disabled" : " "}}>
                                                    @foreach($invoice->account->accountMember as $name)
                                                        {{ $name->member->first_name . " " . $name->member->last_name }}
                                                    @endforeach 
                                                </option>
                                            @endforeach
                                        </datalist> --}}
                                        @if ($errors->has('invoice'))
                                            <span class="invalid-feedback">
                                                <strong>Please select a valid invoice.</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    {{-- Payment Date --}}
                                    <div class="form-group col-md-12">
                                        <label for="payment_date">Payment Date</label>
                                        <input type="date" class="form-control {{ $errors->paymentErrors->has('payment_date') ? ' is-invalid' : '' }}" id="payment_date" name="payment_date" value="{{ !old('payment_date') ? date("Y-m-d") : old('payment_date') }}" placeholder="Payment Date">
                                        @if ($errors->has('payment_date'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->paymentErrors->first('payment_date') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    {{-- Amount --}}
                                    <div class="form-group col-md-12">
                                        <label for="amount">Amount</label>
                                        <input type="number" step=".01" class="form-control {{ $errors->paymentErrors->has('amount') ? ' is-invalid' : '' }}" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Amount">
                                        @if ($errors->paymentErrors->has('amount'))
                                            <span class="invalid-feedback">
                                                <strong> The amount must be less than or equal to the remaining balance.</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    {{-- Percent Rate --}}
                                    <div class="form-group col-md-12">
                                        <label for="percent_rate">Percent Rate</label>
                                        <input type="number" class="form-control {{ $errors->paymentErrors->has('percent_rate') ? ' is-invalid' : '' }}" id="percent_rate" name="percent_rate" value="{{ old('percent_rate') }}" placeholder="Percent Rate">
                                        @if ($errors->paymentErrors->has('percent_rate'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->paymentErrors->first('percent_rate') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                                        
                                <div class="form-row">
                                    {{-- Comment --}}
                                    <div class="form-group col-md-12">
                                        <label for="comment">Comments</label>
                                        <textarea class="form-control {{ $errors->paymentErrors->has('comment') ? ' is-invalid' : '' }}" id="comment" name="comment" placeholder="Enter information here">{{ old('comment') }}</textarea>
                                        @if ($errors->paymentErrors->has('comment'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->paymentErrors->first('comment') }}</strong>
                                            </span>
                                        @endif
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

            {{-- modal Add Vouchers --}}
            <div class="modal fade bd-example-modal-md" id="addVoucher" tabindex="-1" role="dialog" aria-labelledby="addVoucherLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <form method="POST" action='/members/createVoucher'>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addVoucherLabel"><i class="fa fa-fw fa-plus-square"></i>Add Voucher</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">                        
                                {{ csrf_field() }}
                                    
                                <div class="form-row">
                                    {{-- Member Name --}}
                                    <div class="form-group col-md-12">
                                        <label for="member_input">Account No.</label> 
                                        {{-- <input type="text" class="form-control" id="member_input" name="member_input" value="{{ $member->first_name . ' ' . $member->last_name }}" readonly> --}}
                                        <input type="text" class="form-control" id="member" name="member" value="{{ isset( $accounts[0]->id) ? "$account->id" : " " }}" readonly>
                                        <input type="hidden" id="account_id" name="account_id" value="{{ isset( $account->id) ? " $account->id" : " " }}">
                                        <input type="hidden" id="member_id" name="member_id" value="{{ isset( $member->id) ? " $member->id" : " " }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    {{-- Voucher Number --}}
                                    <div class="form-group col-md-12">
                                        <label for="card_number">Voucher No.</label> 
                                        <input type="text" class="form-control {{ $errors->voucherErrors->has('card_number') ? ' is-invalid' : '' }}" id="card_number" name="card_number" value="{{ old('card_number') }}" placeholder="Voucher Card No.">
                                        @if ($errors->voucherErrors->has('card_number'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->voucherErrors->first('card_number') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            <div class="form-row" hidden>
                                {{-- Voucher Status HIDDEN --}}
                                    <div class="form-group col-md-12">
                                        <label for="status">Voucher Status</label>
                                        <select id="status" name="status" class="form-control {{ $errors->voucherErrors->has('status') ? ' is-invalid' : '' }}">
                                            <option value="">Choose...</option>
                                            <option value="unused" selected {{ old('status') == 'unused' ? 'selected' : '' }}>Unused</option>
                                            <option value="redeemed" {{ old('status') == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                                            <option value="forfeited" {{ old('status') == 'forfeited' ? 'selected' : '' }}>Forfeited</option>
                                            <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                        </select>
                                        @if ($errors->voucherErrors->has('status'))
                                            <span class="invalid-feedback">
                                                <strong>Please select a status.</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row">
                                    {{-- Date Issued --}}
                                    <div class="form-group col-md-12">
                                        <label for="date_issued">Date Issued</label>
                                        <input type="date" class="form-control {{ $errors->voucherErrors->has('date_issued') ? ' is-invalid' : '' }}" id="date_issued" name="date_issued" value="{{ old('date_issued') ? old('date_issued') : date('Y-m-d') }}" placeholder="Date Issued">
                                        @if ($errors->has('date_issued'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->voucherErrors->first('date_issued') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    {{-- Valid From --}}
                                    <div class="form-group col-md-12">
                                        <label for="valid_from">Valid From</label>
                                        <input type="date" class="form-control {{ $errors->voucherErrors->has('valid_from') ? ' is-invalid' : '' }}" id="valid_from" name="valid_from" value="{{ old('valid_from') }}" placeholder="Valid From">
                                        @if ($errors->voucherErrors->has('valid_from'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->voucherErrors->first('valid_from') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                {{-- Valid To --}}
                                    <div class="form-group col-md-12">
                                        <label for="valid_to">Valid To</label>
                                        <input type="date" class="form-control {{ $errors->voucherErrors->has('valid_to') ? ' is-invalid' : '' }}" id="valid_to" name="valid_to" value="{{ old('valid_to') }}" placeholder="Valid To">
                                        @if ($errors->voucherErrors->has('valid_to'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->voucherErrors->first('valid_to') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row">
                                    {{-- Destination --}}
                                    <div class="form-group col-md-12">
                                        <label for="destination">Destination</label>
                                        <select class="form-control {{ $errors->has('destination') ? ' is-invalid' : '' }}" id="destination" name="destination">
                                            <option value="">Choose...</option>
                                            @foreach ($destinations as $des)
                                                <option value="{{ $des->id }}"{{ old('destination') == $des->id ? 'selected' : '' }}>{{ $des->code }} - {{ $des->destination_name }} - {{ $des->remarks }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('destination'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('destination') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                                        
                                <div class="form-row">
                                    {{-- Remarks --}}
                                    <div class="form-group col-md-12">
                                        <label for="remarks">Remarks</label>
                                        <textarea style="resize:none;" class="form-control {{ $errors->voucherErrors->has('remarks') ? ' is-invalid' : '' }}" id="remarks" name="remarks" placeholder="Enter information here">{{ old('remarks') }}</textarea>
                                        @if ($errors->voucherErrors->has('remarks'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->voucherErrors->first('remarks') }}</strong>
                                            </span>
                                        @endif
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

        </div> {{-- row --}}
@endsection

@section('scripts')
    {{-- <script>
         $("#invoice_input").on('input', function () {
            var x = this.value;
            var z = $('#invoices');
            var val = $(z).find('option[value="' + x + '"]');
            var id = val.attr('id');

            $('#invoice').val(id); ///hidden input text 
            
        });
    </script> --}}

    @if ($errors->paymentErrors->any())
        <script>
             $('#addPayment').modal('show');
        </script>
    @endif

    @if ($errors->voucherErrors->any())
        <script>
             $('#addVoucher').modal('show');
        </script>
    @endif

@endsection