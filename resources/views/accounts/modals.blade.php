{{-- modal Add Payment --}}
        <div class="modal fade bd-example-modal-md" id="addPayment" tabindex="-1" role="dialog" aria-labelledby="addPaymentLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
            <form method="POST" action='/accounts/createPayment'>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPaymentLabel"><i class="fa fa-fw fa-plus"></i> Add Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">                        
                            {{ csrf_field() }}
                            {{-- Account No --}}
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="invoice_id">Account No.</label>                 
                                    <input type="text" class="form-control" id="account_id" name="account_id" value="{{ $account->id }}" readonly>
                                </div>
                            </div>

                            {{-- Invoices --}}
                           <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="invoice_input">Invoice No.</label>
                                    <input class="form-control {{ $errors->paymentErrors->has('invoice') || $errors->paymentErrors->has('invoice_input') ? ' is-invalid' : '' }}" id="invoice_input" name="invoice_input" list="invoices" placeholder="Begin typing to search for accounts" autocomplete="off">
                                    <datalist id="invoices">
                                        @foreach ($invoices as $invoice) 
                                            <option id="{{ $invoice->id }}" value="{{ $invoice->id }}" {{ $invoice->id == old('invoice_input') ? 'selected' : '' }} {{ $invoice->remaining_balance < 1 ? "disabled" : '' }}>
                                                @foreach($invoice->account->accountMember as $name)
                                                    {{ $name->member->first_name . " " . $name->member->middle_name . " " . $name->member->last_name }}

                                                    @if (!$loop->last)
                                                        {{ ", " }}
                                                    @endif
                                                @endforeach 
                                            </option>
                                        @endforeach
                                    </datalist>
                                    @if ($errors->paymentErrors->has('invoice') || $errors->paymentErrors->has('invoice_input'))
                                        <span class="invalid-feedback">
                                            <strong>Please select a valid invoice.</strong>
                                        </span>
                                    @endif
                                    <input type="hidden" id="invoice" name="invoice" value="{{ old('invoice') }}">
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
        {{-- end of modal Add Payments --}}


        {{-- modal Add Invoice --}}
        <div class="modal fade bd-example-modal-lg" id="addInvoice" tabindex="-1" role="dialog" aria-labelledby="addInvoiceLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action='/accounts/createInvoice'>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addInvoiceLabel"><i class="fa fa-fw fa-plus"></i> Add Invoice</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">                        
                            {{ csrf_field() }}
                            {{-- Account No --}}
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="invoice_id">Account No.</label>                 
                                    <input type="text" class="form-control" id="account_id" name="account_id" value="{{ $account->id }}" readonly>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="invoice_date">Invoice Date</label>
                                    <input type="date" class="form-control {{ $errors->has('invoice_date') ? ' is-invalid' : '' }}" id="invoice_date" name="invoice_date" value="{{ old('invoice_date') ? old('invoice_date') : date('Y-m-d') }}" placeholder="Invoice Date">
                                    @if ($errors->has('invoice_date'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('invoice_date') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="control-label" for="due_at">Due At</label>
                                    <input type="date" class="form-control {{ $errors->has('due_at') ? ' is-invalid' : '' }}" id="due_at" name="due_at" value="{{ old('due_at') ? old('due_at') : date('Y-m-d', strtotime('+1 month')) }}" placeholder="Due At">
                                    @if ($errors->has('due_at'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('due_at') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="control-label">Item / Description</label>
                                </div>
                                <div class="form-group col-md-2">
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
                                    <div class="form-group col-md-4">
                                        <select class="form-control select-item" id="items1" name="items[]">
                                            <option selected>Choose...</option>
                                            <option value="12000">Termination Fee</option>
                                            <option value="20000">Legal and Processing Fee</option>
                                            <option value="1000">Card Replacement Fee</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
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
                                <div class="col-md-7"></div>
                                <div class="col-md-3">
                                    <input class="form-control-plaintext text-right" type="text" id="total" name="total" readonly>
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
        {{-- end of modal Add Payments --}}


        {{-- modal Add Vouchers --}}
        <div class="modal fade bd-example-modal-md" id="addVoucher" tabindex="-1" role="dialog" aria-labelledby="addVoucherLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <form method="POST" action='/accounts/createVoucher'>
                     {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addVoucherLabel"><i class="fa fa-fw fa-plus-square"></i>Add Voucher</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">                        

                            <div class="form-row">
                                {{-- Member Name --}}
                                <div class="form-group col-md-12">
                                    <label for="member_input">Account No.</label> 
                                    {{-- <input type="text" class="form-control" id="member_input" name="member_input" value="{{ $member->first_name . ' ' . $member->last_name }}" readonly> --}}
                                    <input type="text" class="form-control" id="member" name="member" value="{{ $account->id }}" readonly>
                                    <input type="hidden" id="member" name="member" value="{{ $account->id }}">
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
                                    <input type="date" class="form-control {{ $errors->voucherErrors->has('date_issued') ? ' is-invalid' : '' }}" id="date_issued" name="date_issued" value="{{ old('date_issued') }}" placeholder="Date Issued">
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
                                    <select class="form-control {{ $errors->voucherErrors->has('destination') ? ' is-invalid' : '' }}" id="destination" name="destination">
                                        <option value="">Choose...</option>
                                        @foreach ($destinations as $des)
                                            <option value="{{ $des->id }}"{{ old('destination') == $des->id ? 'selected' : '' }}>{{ $des->destination_name }} ({{ $des->remarks }})</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->voucherErrors->has('destination'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->voucherErrors->first('destination') }}</strong>
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
        </div> {{-- modal add voucher --}}