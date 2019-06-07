@extends('layouts.admin')

@section('title')
    Create Invoice
@endsection

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/invoices">Invoices</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ol>
    </nav>

    <div class="card mb-3">
        <div class="card-header">
          <h3><i class="fa fa-fw fa-plus"></i>Create Invoice</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/invoices" onsubmit="setFormSubmitting()">
                {{ csrf_field() }}

                <div class="form-row">
                    <div class="form-group required col-md-4">
                        <label class="control-label" for="account_input">Account</label>
                        <input class="form-control {{ $errors->has('account_input') ? ' is-invalid' : '' }}" id="account_input" name="account_input" list="accounts" placeholder="Begin typing to search for accounts" autocomplete="off">
                        <datalist id="accounts">
                            @foreach ($accounts as $account)
                                <option id="{{ $account->id }}" value="{{ $account->sales_deck }}" {{ old('account') == $account->id ? 'selected' : '' }}>
                                    @foreach ($account->members as $i=>$member)
                                        {{ $member->first_name . ' ' . $member->last_name }}
                                        
                                        @if (!$loop->last)
                                            {{ ', ' }}
                                        @endif
                                    @endforeach
                                </option>
                            @endforeach
                        </datalist>
                        @if ($errors->has('account'))
                            <span class="invalid-feedback">
                                <strong>Please select a valid account.</strong>
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

                <div class="form-row">
                    <div class="form-group required col-md-2">
                        <label class="control-label" for="invoice_date">Invoice Date</label>
                        <input type="date" class="form-control {{ $errors->has('invoice_date') ? ' is-invalid' : '' }}" id="invoice_date" name="invoice_date" value="{{ old('invoice_date') ? old('invoice_date') : date('Y-m-d') }}" placeholder="Invoice Date">
                        @if ($errors->has('invoice_date'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('invoice_date') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group required col-md-2">
                        <label class="control-label" for="due_at">Due At</label>
                        <input type="date" class="form-control {{ $errors->has('due_at') ? ' is-invalid' : '' }}" id="due_at" name="due_at" value="{{ old('due_at') ? old('due_at') : date('Y-m-d', strtotime('+1 month')) }}" placeholder="Due At">
                        @if ($errors->has('due_at'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('due_at') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label class="control-label">Item / Description</label>
                    </div>
                    <div class="form-group col-md-1">
                        <label class="control-label">Quantity</label>
                    </div>
                    <div class="form-group col-md-2 text-right">
                        <label class="control-label">Unit Price</label>
                    </div>
                    <div class="form-group col-md-2 text-right">
                        <label class="control-label">Amount</label>
                    </div>
                </div>

                <div id="items">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <select class="form-control select-item" id="items1" name="items[]">
                                <option selected>Choose...</option>
                                <option value="12000">Termination Fee</option>
                                <option value="20000">Legal and Processing Fee</option>
                                <option value="1000">Card Replacement Fee</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <input class="form-control item-quantity" type="number" id="quantity1" name="quantities[]">
                        </div>
                        <div class="form-group col-md-2">
                            <input class="form-control text-right item-price" type="number" id="unit_price1" name="unit_prices[]">
                        </div>
                        <div class="form-group col-md-2">
                            <input class="form-control-plaintext text-right" type="text" id="amount1" name="amounts[]" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <a id="addItem" class="text-primary"><i class="fa fa-plus"></i> Add Item</a>
                </div>

                <hr>

                <div class="form-row">
                    <div class="col-md-2">
                        <label class="control-label font-weight-bold">TOTAL</label>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <input class="form-control-plaintext text-right" type="text" id="total" name="total" readonly>
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
            var itemID = 1;

            $('#addItem').click(function () {
                itemID++;

                $('#items').append($('<div class="form-row" id="item'+ itemID +'"> <div class="form-group col-md-3"> <select class="form-control select-item" id="items'+ itemID +'" name="items[]"> <option value="" selected>Choose...</option> <option value="12000">Termination Fee</option> <option value="20000">Legal and Processing Fee</option> <option value="1000">Card Replacement Fee</option> </select> </div> <div class="form-group col-md-1"> <input class="form-control item-quantity" type="number" id="quantity'+ itemID +'" name="quantities[]"> </div> <div class="form-group col-md-2"> <input class="form-control text-right item-price" type="number" id="unit_price'+ itemID +'" name="unit_prices[]"> </div> <div class="form-group col-md-2"> <input class="form-control-plaintext text-right" type="text" id="amount'+ itemID +'" name="amounts[]" readonly> </div><div class="form-group col-md-2 text-center"><a id="remove' + itemID + '" class="btn-removeItem form-control-plaintext text-danger" data-id="'+ itemID +'"> <i class="fa fa-times"></i> Remove</a></div></div>'));
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
    </script>
@endsection