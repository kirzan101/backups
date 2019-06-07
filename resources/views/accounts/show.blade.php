@extends('layouts.admin')

@section('title')
   VoucherMS | Account Details
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/accounts">Accounts</a></li>
            <li class="breadcrumb-item active" aria-current="page">
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
            </li>
        </ol>
    </nav>
    {{-- Account Information --}}
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h2> <i class="fa fa-user"></i> Account Details</h2>
                            </div>
                            <div class="col-2"></div>                            
                            <div class="col-4 text-right">
                            <a class="btn btn-outline-success" href="/accounts/members/{{ $account->id }}"><i class="fa fa-edit"></i> Edit Members</a>
                            </div>                            
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                             <div class="form-group col">
                                <label for="amount" class="font-weight-bold col-md col-form-label text-left">Account No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control-plaintext" id="id" value="{{ $account->id}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col">
                                <label for="amount" class="font-weight-bold col-md col-form-label text-left">Sales Deck: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control-plaintext" id="id" value="{{ $account->sales_deck}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col">
                                <label for="amount" class="font-weight-bold col-md col-form-label text-left">Consultant: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control-plaintext" id="id" value="{{ $account->consultant->name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group col">
                                                
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col">
                                <h5>Member's Information</h5>
                            </div>
                        </div>
                        <div class="row">                          
                            <div class="col-12">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($account->members as $member)
                                        <tr>
                                            {{-- <th scope="row">{{ $loop->iteration }}</th> --}}
                                            <td>{{ $member->id }}</td>
                                            <td><a href="/members/{{$member->id}}">{{ $member->first_name . " " . $member->last_name}}</a></td>
                                            <td><p class="font-weight-bold text-success">{{ strtoupper($member->status) }}</p></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>                           
                            </div>
                        </div>
                    </div> {{-- card body --}}
                </div> {{-- card --}}

                {{-- vouchers --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h2> <i class="fa fa-envelope"></i>  Vouchers</h2>
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
                                    <td><a href="/vouchers/{{ $voucher->id }}">{{ $voucher->card_number }}</a></td>
                                    <td>{{ $voucher->remarks }}</td>
                                    <td>{{ $voucher->destination->destination_name }}</td>
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


                {{-- invoices --}}
                {{-- <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h2> <i class="fa fa-credit-card"></i>  Invoices</h2>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-4 text-right">
                                <button class="btn btn-outline-success" data-toggle="modal" data-target="#addInvoice"><i class="fa fa-plus"></i> Add Invoice</button>
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
                                    <th scope="col">#</th>
                                    <th scope="col">Invoice No.</th>
                                    <th scope="col">Principal Amount</th>
                                    <th scope="col">Downpayment</th>
                                    <th scope="col">Total Paid Amount</th>
                                    <th scope="col">Remaining Balance</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td><a href="/invoices/{{ $invoice->id }}">{{ $invoice->invoice_number }}</a></td>
                                    <td>{{ number_format($invoice->principal_amount,2) }}</td>
                                    <td>{{ number_format($invoice->downpayment,2) }}</td>
                                    <td>{{ number_format($invoice->total_paid_amount,2) }}</td>
                                    <td>{{ number_format($invoice->remaining_balance,2) }}</td>
                                    <td>{{ $invoice->status }}</td>                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div> --}}


                {{-- payments --}}
                {{-- <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h2> <i class="fa fa-shopping-cart"></i> Payments</h2>
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
                                No payments yet.
                            </div>
                        @else
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Payment Date</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">%</th>
                                    <th scope="col">Comment</th>
                                    <th scope="col">Created By</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    @foreach ($invoice->payments as $payment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $payment->payment_date }}</td>
                                            <td>{{ number_format($payment->amount,2) }}</td>
                                            <td>{{ $payment->percent_rate }}</td>
                                            <td>{{ $payment->comment }}</td>
                                            <td>{{ $payment->created_by }}</td>
                                            <td>{{ $payment->created_at }}</td>
                                            <td>{{ $payment->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div> --}}
            </div>
                
            @include('accounts.modals')
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            var itemID = 1;

            $('#addItem').click(function () {
                itemID++;

                $('#items').append($('<div class="form-row" id="item'+ itemID +'"> <div class="form-group col-md-4"> <select class="form-control select-item" id="items'+ itemID +'" name="items[]"> <option value="" selected>Choose...</option> <option value="12000">Termination Fee</option> <option value="20000">Legal and Processing Fee</option> <option value="1000">Card Replacement Fee</option> </select> </div> <div class="form-group col-md-2"> <input class="form-control item-quantity" type="number" id="quantity'+ itemID +'" name="quantities[]"> </div> <div class="form-group col-md-2"> <input class="form-control text-right item-price" type="number" id="unit_price'+ itemID +'" name="unit_prices[]"> </div> <div class="form-group col-md-2"> <input class="form-control-plaintext text-right" type="text" id="amount'+ itemID +'" name="amounts[]" readonly> </div><div class="form-group col-md-2 text-center"><a id="remove' + itemID + '" class="btn-removeItem form-control-plaintext text-danger" data-id="'+ itemID +'"> <i class="fa fa-times"></i> Remove</a></div></div>'));
            });

            $('#items').on('click', '.btn-removeItem', function() {
                var item_id = $(this).data('id');
                $('#item' + item_id).remove();
            });

            $('#items').on('change', '.select-item', function() {
                var price = $(this).val();
                var item = this.id;
                var itemIndex = item.split("items")[1];

                $('#quantity' + itemIndex).val(1);
                $('#unit_price' + itemIndex).val(price);

                var quantity = $('#quantity' + itemIndex).val();
                var unit_price = $('#unit_price' + itemIndex).val();

                $('#amount' + itemIndex).val(quantity * unit_price);

                var total = 0;
                $('input[name^="amounts"]').each(function() {
                    total += parseInt($(this).val(), 10);
                });

                $('#total').val(total);
            });

            $('#items').on('input', '.item-quantity', function () {
                var item = this.id;
                var itemIndex = item.split("quantity")[1];
                var quantity = $(this).val();
                var unit_price = $('#unit_price' + itemIndex).val();
                $('#amount' + itemIndex).val(quantity * unit_price);

                var total = 0;
                $('input[name^="amounts"]').each(function() {
                    total += parseInt($(this).val(), 10);
                });

                $('#total').val(total);
            });

            $('#items').on('input', '.item-price', function () {
                var item = this.id;
                var itemIndex = item.split("unit_price")[1];
                var quantity = $('#quantity' + itemIndex).val();
                var unit_price = $(this).val();
                $('#amount' + itemIndex).val(quantity * unit_price);

                var total = 0;
                $('input[name^="amounts"]').each(function() {
                    total += parseInt($(this).val(), 10);
                });

                $('#total').val(total);
            });
        });

         $("#invoice_input").on('input', function () {
            var x = this.value;
            var z = $('#invoices');
            var val = $(z).find('option[value="' + x + '"]');
            var id = val.attr('id');

            $('#invoice').val(id); ///hidden input text             
        });
    </script>
    @if (!$errors->paymentErrors->isEmpty())
        <script>
            $('#addPayment').modal('show')
        </script>
    @endif

    @if (!$errors->voucherErrors->isEmpty())
        <script>
            $('#addVoucher').modal('show')
        </script>
    @endif

    @if (!$errors->invoiceErrors->isEmpty())
        <script>
            $('#addInvoice').modal('show')
        </script>
    @endif
@endsection