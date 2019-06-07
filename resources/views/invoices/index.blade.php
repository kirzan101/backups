@extends('layouts.admin')

@section('title')
    Invoices
@endsection

@section('content')

    {{-- BREADCRUMBS --}}
    <div id="invoices">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Invoices</li>
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
    {{-- END OF BRDCRMBS --}}

    <div class="card mb-3">
        <div class="card-header">
          <h3><i class="fa fa-fw fa-calculator"></i>Invoice</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="/invoices">
                <div class="row mb-4">
                    <div class="col-1">
                        @if ($canCreate)
                            <a class="btn btn-success" href="/invoices/create"><i class="fa fa-fw fa-plus"></i> Add Invoice</a>
                        @endif
                    </div>
                    <div class="col-6"></div>
                    <div class="col-5">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search for invoices" aria-label="Search" value="{{ $search }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-search"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <form method="GET" action="/invoices" class="form-inline">
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
                        Showing {{ $invoices->total() }} results for <strong>{{ $search }}</strong>
                    </p>
                    @endif

                    <table class="table table-hover">
                      <thead>
                            <tr>
                                <th class="text-center"># <a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=invoice_number&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=invoice_number&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Invoice # <a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=invoice_number&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=invoice_number&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Name</th>

                                <th>Amount <a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=principal_amount&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=principal_amount&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Downpayment <a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=downpayment&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=downpayment&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Total Paid <a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=total_paid_amount&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=total_paid_amount&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Balance <a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=remaining_balance&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=remaining_balance&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Status <a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=status&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=status&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Created By <a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Created At <a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Updated At <a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/invoices?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($invoices as $i=>$invoice)
                                <tr>
                                    <th class="text-center">{{ $i + 1 + ($invoices->perPage() * ($invoices->currentPage() - 1)) }}</th>
                                    <th>{{ $invoice->invoice_number }}</th>

                                    <td>
                                        <a href="/invoices/{{ $invoice->id }}" class="link-table">
                                            @foreach($invoice->account->members as $member)
                                            {{$member->last_name . ", " . $member->first_name}} <br>
                                            @endforeach
                                        </a>
                                    </td>
                                    <td><a href="/invoices/{{ $invoice->id }}" class="link-table">{{ number_format($invoice->principal_amount, 2) }}</a></td>
                                    <td><a href="/invoices/{{ $invoice->id }}" class="link-table">{{ number_format($invoice->downpayment, 2) }}</a></td>
                                    <td><a href="/invoices/{{ $invoice->id }}" class="link-table">{{ number_format($invoice->total_paid_amount, 2) }}</a></td>
                                    <td><a href="/invoices/{{ $invoice->id }}" class="link-table">{{ number_format($invoice->remaining_balance, 2) }}</a></td>
                                    <td><strong>
                                        <p class=
                                            <?php 
                                                if ($invoice->status == 'full'){
                                                    echo 'text-success';
                                                } else if ($invoice->status == 'partial') {
                                                    echo 'text-info';
                                                } else {
                                                    echo 'text-warning';
                                                }
                                            ?>>
                                            {{ strtoupper($invoice->status) }}</p>
                                    </strong></td>
                                    <td><a href="/invoices/{{ $invoice->id }}" class="link-table">{{ $invoice->created_by }}</a></td>
                                    <td><a href="/invoices/{{ $invoice->id }}" class="link-table">{{ $invoice->created_at }}</a></td>
                                    <td><a href="/invoices/{{ $invoice->id }}" class="link-table">{{ $invoice->updated_at }}</a></td>
                                    <td><a href="/invoices/{{ $invoice->id }}" class="btn btn-outline-success">Details</a></td>
                                </tr>
                            @endforeach                            
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    {{ $invoices->appends([
                        'search' => $search,
                        'per_page' => $per_page,
                        'sort' => app('request')->input('sort'),
                        'dir' => app('request')->input('dir')
                    ])->links() }}

                </div>
            </form>
        </div>
        {{-- <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> --}}
      </div>

    </div>

    <style>
        .link-table {
            display: block;
            color:#0d0d0d;
        }

        .link-table:hover {
            text-decoration: none;
            color:#0d0d0d;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('#per_page').change(function() {
                this.form.submit();
            });
        });
    </script>
@endsection