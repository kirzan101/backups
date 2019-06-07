@extends('layouts.admin')

@section('title')
    VoucherMS | Vouchers
@endsection

@section('content')

    {{-- Add Voucher --}}
    <div id="addVoucher">
        <div class="modal fade bd-example-modal-lg" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action='/vouchers'>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel"><i class="fa fa-fw fa-plus-square"></i>Add Voucher</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">                        
                            {{ csrf_field() }}

                            <div class="form-row">
                                {{-- Member Name --}}
                                <div class="form-group col-md-12">
                                    <label for="account">Account</label> 
                                    <input class="form-control {{ $errors->has('account') || $errors->has('account') ? ' is-invalid' : '' }}" id="account" name="account" list="accounts" placeholder="Begin typing to search for accounts" autocomplete="off" value="{{ old('account') }}">
                                    <datalist id="accounts">
                                        @foreach ($accounts as $account)
                                            <option id="{{ $account->id }}" value="{{ $account->id }}">{{ $account->sales_deck }}</option>
                                        @endforeach
                                    </datalist>
                                    @if ($errors->has('account'))
                                        <span class="invalid-feedback">
                                            <strong>Please select a valid account.</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                {{-- Voucher Number --}}
                                <div class="form-group col-md-6">
                                    <label for="card_number">Voucher No.</label> 
                                    <input type="text" class="form-control {{ $errors->has('card_number') ? ' is-invalid' : '' }}" id="card_number" name="card_number" value="{{ old('card_number') }}" placeholder="Voucher Card No.">
                                    @if ($errors->has('card_number'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('card_number') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                {{-- Date Issued --}}
                                <div class="form-group col-md-6">
                                    <label for="date_issued">Date Issued</label>
                                    <input type="date" class="form-control {{ $errors->has('date_issued') ? ' is-invalid' : '' }}" id="date_issued" name="date_issued" value="{{ old('date_issued') ? old('date_issued') : date('Y-m-d') }}" placeholder="Date Issued">
                                    @if ($errors->has('date_issued'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('date_issued') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>  
                            
                            <div class="form-row">
                                {{-- Valid From --}}
                                <div class="form-group col-md-6">
                                    <label for="valid_from">Valid From</label>
                                    <input type="date" class="form-control {{ $errors->has('valid_from') ? ' is-invalid' : '' }}" id="valid_from" name="valid_from" value="{{ old('valid_from') }}" placeholder="Valid From">
                                    @if ($errors->has('valid_from'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('valid_from') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                {{-- Valid To --}}
                                <div class="form-group col-md-6">
                                    <label for="valid_to">Valid To</label>
                                    <input type="date" class="form-control {{ $errors->has('valid_to') ? ' is-invalid' : '' }}" id="valid_to" name="valid_to" value="{{ old('valid_to') }}" placeholder="Valid To">
                                    @if ($errors->has('valid_to'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('valid_to') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
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
                                    <input type="text" class="form-control {{ $errors->has('remarks') ? ' is-invalid' : '' }}" id="remarks" name="remarks" value="{{ old('remarks') }}" placeholder="Enter information here">
                                    @if ($errors->has('remarks'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('remarks') }}</strong>
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
        </div> <!-- modal -->
    </div>
    {{-- End of Add Voucher --}}

    <div id="vouchers">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Vouchers</li>
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
          <h3>Vouchers</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="/vouchers">
                <div class="row mb-3">
                    <div class="col-1">
                        @if ($canCreate)
                            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#addModal"><i class="fa fa-fw fa-plus"></i> Add Voucher</a>
                        @endif
                    </div>
                    <div class="col-6"></div>
                    <div class="col-5">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search for vouchers" aria-label="Search" value="{{ $search }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-search"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <form method="GET" action="/vouchers" class="form-inline">
                        {{-- sorting of status--}}
                        <div class="row">
                            <div class="col-6">
                                <label class="my-1 mr-2" for="module">Status: </label>
                                <select class="custom-select my-1 mr-sm-2 col-sm-3" id="status" name="status">
                                    <option value="all"  {{ $status == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="unused"  {{ $status == 'unused' ? 'selected' : '' }}>Unused</option>
                                    <option value="redeemed"  {{ $status == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                                    <option value="canceled"  {{ $status == 'canceled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="forfeited"  {{ $status == 'forfeited' ? 'selected' : '' }}>Forfeited</option>
                                </select>
                            </div>
                            <div class="col-6 text-right">
                                <label class="my-1 mr-2" for="per_page">Entries per page: </label>
                                <select class="custom-select my-1 mr-sm-2 col-sm-3" id="per_page" name="per_page">
                                    <option value="10" {{ $per_page == '10' ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $per_page == '25' ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $per_page == '50' ? 'selected' : '' }}>50</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    @if ($search != '')
                    <p>
                        Showing {{ $vouchers->total() }} results for <strong>{{ $search }}</strong>
                    </p>
                    @endif

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#<a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>
                                
                                <th>Member(s)<a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=account_id&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=account_id&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Voucher Number<a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=card_number&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=card_number&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Date Issued <a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=date_issued&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=date_issued&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Valid From <a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=valid_from&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=valid_from&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>
                                
                                <th>Valid To <a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=valid_to&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=valid_to&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Status <a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=status&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=status&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Created By <a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Created At <a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Updated At <a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/vouchers?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $i=>$voucher)
                                <tr>
                                    {{-- $i + 1 + ($vouchers->perPage() * ($vouchers->currentPage() - 1)) --}}
                                    <th><a href="/vouchers/{{ $voucher->id }}" class="link-table">{{ $voucher->id }}</a></th>
                                    <td>
                                        <a href="/vouchers/{{ $voucher->id }}" class="link-table">
                                            @foreach($voucher->account->accountMember as $name)
                                                • {{ $name->member->last_name . ", " . $name->member->first_name}}
                                                <br>
                                            @endforeach
                                        </a>
                                    </td>
                                    <td><a href="/vouchers/{{ $voucher->id }}" class="link-table">{{ $voucher->card_number }}</a></td>
                                    <td><a href="/vouchers/{{ $voucher->id }}" class="link-table">{{ date("d M Y", strtotime($voucher->date_issued)) }}</a></td>
                                    <td><a href="/vouchers/{{ $voucher->id }}" class="link-table">{{ date("d M Y", strtotime($voucher->valid_from)) }}</a></td>
                                    <td><a href="/vouchers/{{ $voucher->id }}" class="link-table">{{ date("d M Y", strtotime($voucher->valid_to)) }}</a></td>
                                    <td><a href="/vouchers/{{ $voucher->id }}" class="link-table">{{ $voucher->status }}</a></td>
                                    <td><a href="/vouchers/{{ $voucher->id }}" class="link-table">{{ $voucher->created_by }}</a></td>
                                    <td><a href="/vouchers/{{ $voucher->id }}" class="link-table">{{ date("d M Y", strtotime($voucher->created_at)) }}</a></td>
                                    <td><a href="/vouchers/{{ $voucher->id }}" class="link-table">{{ date("d M Y", strtotime($voucher->updated_at)) }}</a></td>
                                    <td><a href="/vouchers/{{ $voucher->id }}" class="btn btn-outline-success">Details</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{-- Pagination --}}
                    {{ $vouchers->appends([
                        'search' => $search,
                        'per_page' => $per_page,
                        'sort' => app('request')->input('sort'),
                        'dir' => app('request')->input('dir')
                    ])->links() }}

                </div>
            </form>
        </div>
      </div> {{-- card --}}
    </div> {{-- vouchers --}}

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
    @if ($errors->any())
        <script>
            $('#addModal').modal('show')
        </script>
    @endif

    <script>
        $(function() {
            $('#per_page').change(function() {
                this.form.submit();
            });

            $('#status').change(function() {
                this.form.submit();
            });
        });
    </script>

@endsection