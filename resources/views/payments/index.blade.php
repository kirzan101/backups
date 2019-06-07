@extends('layouts.admin')

@section('title')
    Payments
@endsection

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Payments</li>
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

    <div id="addPayment">
        <div class="modal fade bd-example-modal-md" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
            <form method="POST" action='/payments'>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel"><i class="fa fa-fw fa-plus"></i> Add Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">                        
                            {{ csrf_field() }}

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="invoice">Invoice No.</label>
                                    <input class="form-control {{ $errors->has('invoice') ? ' is-invalid' : '' }}" id="invoice_input" name="invoice_input" list="invoices" placeholder="Begin typing to search for accounts" autocomplete="off" value="{{ old('invoice') }}">
                                    <datalist id="invoices">
                                        @foreach ($invoices as $invoice) 
                                            <option id="{{ $invoice->id }}" value="{{ $invoice->invoice_number }}">
                                                    {{ $invoice->first_name . " " . $invoice->last_name }}
                                                    @if($key = 0)
                                                        {{ " " }}
                                                    @elseif($key % 2 == 0)
                                                        @if(!$loop->last)
                                                            {{ "," }}
                                                        @endif
                                                    @endif
                                                 - Account No. {{ $invoice->account_id }} 
                                            </option>
                                        @endforeach
                                    </datalist>
                                    @if ($errors->has('invoice') || $errors->has('invoice_input'))
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
    </div>

    <div id="payments">
    <div class="card mb-3">
        <div class="card-header">
          <h3><i class="fa fa-fw fa-shopping-cart"></i> Payments</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="/payments">
                <div class="row mb-3">
                    <div class="col-1">
                        @if ($canCreate)
                            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#addModal"><i class="fa fa-fw fa-plus"></i> Add Payment</a>
                        @endif
                    </div>
                    <div class="col-6"></div>
                    <div class="col-5">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search for payments" aria-label="Search" value="{{ $search }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-search"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <form method="GET" action="/payments" class="form-inline">
                        <div class="text-right">
                            <label class="my-1 mr-2" for="per_page">Entries per page: </label>
                            <select class="custom-select my-1 mr-sm-2 col-sm-1" id="per_page" name="per_page">
                                <option value="10" {{ $per_page == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $per_page == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $per_page == '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                    </form>
                    

                    @if ($search != '')
                    <p>
                        Showing {{ $payments->total() }} results for <strong>{{ $search }}</strong>
                    </p>
                    @endif

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center"># <a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Member(s)</th>
                                
                                <th>Payment Date <a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=payment_date&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=payment_date&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>
                               
                                <th>Amount <a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=amount&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=amount&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>
                                
                                <th>Percent Rate <a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=percent_rate&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=percent_rate&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Comment <a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=comment&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=comment&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Created By <a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>
                            
                                <th>Created At <a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>
                                
                                <th>Updated At <a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/payments?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $i=>$payment)
                                <tr>
                                    <th class="text-center">{{ $i + 1 + ($payments->perPage() * ($payments->currentPage() - 1)) }}</th>
                                    <td>
                                        @foreach ($payment->invoice->account->members as $member)
                                            {{ $member->last_name . ', ' . $member->first_name }}<br>
                                            {{-- {{ count($payment->invoice->account->members) > 1 ? '/' : '' }} --}}
                                        @endforeach
                                    </td>
                                    <td style="width:12%;">{{ $payment->payment_date }}</td>
                                    <td style="width:10%;">{{ number_format($payment->amount, 2) }}</td>
                                    <td style="width:11%;">{{ $payment->percent_rate}}</td>
                                    <td style="width:13%;">{{ $payment->comment}}</td>
                                    <td style="width:10%;">{{ $payment->created_by}}</td>
                                    <td style="width:10%;">{{ $payment->created_at}}</td>
                                    <td style="width:11%;">{{ $payment->updated_at}}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{-- Pagination --}}
                    {{ $payments->appends([
                        'search' => $search,
                        'per_page' => $per_page,
                        'sort' => app('request')->input('sort'),
                        'dir' => app('request')->input('dir')
                    ])->links() }}

                </div>
            </form>
        </div>
      </div>
    </div>

@endsection


@section('scripts')
    <script>
         $("#invoice_input").on('input', function () {
            var x = this.value;
            var z = $('#invoices');
            var val = $(z).find('option[value="' + x + '"]');
            var id = val.attr('id');

            $('#invoice').val(id); ///hidden input text
        });
    </script>

      <script>
        $(function() {
            $('#per_page').change(function() {
                this.form.submit();
            });
        });
    </script> 

 
    @if ($errors->any())
        <script>
            $('#addModal').modal('show')
        </script>
    @endif
@endsection