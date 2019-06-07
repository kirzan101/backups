@extends('layouts.admin')

@section('title')
    Invoice #{{ $invoices->invoice_number}}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/invoices">Invoices</a></li>
            <li class="breadcrumb-item active" aria-current="page">
               Invoice #{{ $invoices->invoice_number}}
            </li>
        </ol>
    </nav>
    <div class="col-10">
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
                <h3 class="col-sm-10"><i class="fa fa-envelope"></i> Invoice Information</h3>
            </div>
            <div class="card-body">
                 <form>
                    <fieldset disabled>
                        <div class="row">
                            <div class="form-group col">
                                <h5 class="col-sm-10">Member(s): </h5>
                                <div class="col-sm-10">
                                    @foreach($invoices->account->members as $member)
                                        {{$member->first_name." ".$member->last_name}}
                                        @if($key = 0)
                                            {{ " " }}
                                        @elseif($key % 2 == 0)
                                            @if(!$loop->last)
                                                {{ "," }}
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>

                <form>
                    <h5 class="col-sm-10">Invoice Information</h5>
                    <div class="form-group row"> 
                        <label for="date_issued" class="font-weight-bold col-md-2 col-form-label text-right">Date Issued: </label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control-plaintext" id="date_issued" value="{{ date('M d, Y', strtotime($invoices->created_at)) }}" readonly>
                            </div>  

                        <label for="invoices_status" class="font-weight-bold col-sm-2 col-form-label text-right">Invoice Status: </label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control-plaintext" id="invoices_status" value="{{ strtoupper($invoices->status) }}" readonly>
                            </div>
                    </div> 
                    <br>                

                    <table class="table table-borderless">
                        <thead>
                                
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">Principal Amount</td>
                                <td class="text-right">{{number_format($invoices->principal_amount,2)}}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Downpayment</td>
                                <td class="text-right">{{ number_format($invoices->downpayment,2) }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Total Amount Paid</td>
                                <td class="text-right">{{ number_format($invoices->total_paid_amount,2) }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-success">Remaining Balance</td>
                                <td class="text-right font-weight-bold text-success">{{ number_format($invoices->remaining_balance,2) }}</td>
                            </tr>
                        </tbody>
                    </table>                        
                </form>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h3 class="col-sm-10"><i class="fa fa-shopping-cart"></i> Payments</h3>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4 text-right">
                        <button class="btn btn-outline-success" data-toggle="modal" data-target="#addPayment"><i class="fa fa-plus"></i> Add Payment</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if ($invoices->payments->count() == 0)
                    <div class="alert alert-warning" role="alert">
                        No payments yet.
                    </div>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Rate</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td>{{ $payment->amount }}</td>
                                    <td>{{ $payment->percent_rate }}</td>
                                    <td>{{ $payment->comment }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    {{-- modal Add Payment --}}
    <div class="modal fade bd-example-modal-md" id="addPayment" tabindex="-1" role="dialog" aria-labelledby="addPaymentLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form method="POST" action='/invoices/storePayment'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPaymentLabel"><i class="fa fa-fw fa-plus"></i> Add Payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">                        
                        {{ csrf_field() }}

                        <input type="hidden" id="invoice_id" name="invoice_id" value="{{ $invoices->id }}">
                        <input type="hidden" id="balance" name="balance" value="{{ $invoices->remaining_balance }}">
                        <input type="hidden" id="total_paid" name="total_paid" value="{{ $invoices->total_paid_amount }}">

                        <div class="form-row">
                            {{-- Payment Date --}}
                            <div class="form-group col-md-12">
                                <label for="payment_date">Payment Date</label>
                                <input type="date" class="form-control {{ $errors->has('payment_date') ? ' is-invalid' : '' }}" id="payment_date" name="payment_date" value="{{ !old('payment_date') ? date("Y-m-d") : old('payment_date') }}" placeholder="Payment Date">
                                @if ($errors->has('payment_date'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('payment_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-row">
                            {{-- Amount --}}
                            <div class="form-group col-md-12">
                                <label for="amount">Amount</label>
                                <input type="number" step=".01" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Amount">
                                @if ($errors->has('amount'))
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
                                <input type="number" class="form-control {{ $errors->has('percent_rate') ? ' is-invalid' : '' }}" id="percent_rate" name="percent_rate" value="{{ old('percent_rate') }}" placeholder="Percent Rate">
                                @if ($errors->has('percent_rate'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('percent_rate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                                                
                        <div class="form-row">
                            {{-- Comment --}}
                            <div class="form-group col-md-12">
                                <label for="comment">Comments</label>
                                <textarea class="form-control {{ $errors->has('comment') ? ' is-invalid' : '' }}" id="comment" name="comment" placeholder="Enter information here">{{ old('comment') }}</textarea>
                                @if ($errors->has('comment'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('comment') }}</strong>
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
@endsection

@section('scripts')
    @if ($errors->any())
        <script>
            $('#addPayment').modal('show');
        </script>
    @endif
@endsection