@extends('layouts.admin')

@section('title')
    VoucherMS | Reports - Voucher Validity
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Voucher Validity Reports</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h2> <i class="fa fa-envelope"></i> Voucher Validity</h2>
                        </div>
                        <div class="col-6"></div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="/reports/validity">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="control-label" for="account_input">Account</label>
                                <input class="form-control" id="account_input" name="account_input" list="accounts" placeholder="Begin typing to search for accounts" autocomplete="off" value="{{ $account_input != null ? $account_input : '' }} ">
                                <small id="accountHelp" class="form-text text-muted">
                                Leave blank to display all vouchers from all accounts.
                                </small>
                                <datalist id="accounts">
                                    @foreach ($accounts as $account)
                                        <option id="{{ $account->id }}" value="{{ $account->sales_deck }}">
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

                                <input type="hidden" id="account" name="account" value="{{ $account_selected != 'all' ? $account_selected : '' }}">
                            </div> 
                            {{-- end of accounts --}}
                            <div class="form-group col-md-6">
                                <div class="row">                        
                                    <div class="form-group col-md-6">
                                        <label for="date_from">Date Issued From</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $date_from }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="date_to">Date Issued To</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $date_to }}">
                                    </div>
                                </div>
                            </div>
                            {{-- end of dates --}}
                         
                            <div class="form-group col-md-1">
                                <label for="date_from" style="color:white;">Date Start</label>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-filter"></i> Filter</button>
                            </div>
                            <div class="form-group col-md-1 align-bottom">
                                <div class="dropdown">
                                    <label for="date_from" style="color:white;">Date Start</label>
                                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-download"></i> Export
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="/reports/excel/validity/{{ $account_selected }}/from/{{ $date_from }}/to/{{ $date_to }}">Excel</a>
                                        <a class="dropdown-item" href="/reports/pdf/validity/{{ $account_selected }}/from/{{ $date_from }}/to/{{ $date_to }}">PDF</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-1">
                                <label for="per_page">Entries per page: </label>
                                <select class="form-control" id="per_page" name="per_page">
                                    <option value="10" {{ $per_page == '10' ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $per_page == '25' ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $per_page == '50' ? 'selected' : '' }}>50</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    @if (isset($vouchers))
                        <p>Showing <strong>{{ number_format($vouchers->total(), 0) }}</strong> total vouchers.</p>

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Member(s)</th>
                                        <th>Voucher Number</th>
                                        <th>Status</th>
                                        <th>Destination</th>
                                        <th>Agreement No.</th>
                                        <th>Issued On</th>
                                        <th>Redeemed On</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Valid Until</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach ($vouchers as $voucher)
                                        <tr>
                                            <td>{{ $voucher->id }}</td>
                                            <td>
                                                @foreach ($voucher->account->members as $m)
                                                    {{ $m->first_name . ' ' . $m->last_name }}

                                                    @if (!$loop->last)
                                                        {{ '/ ' }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $voucher->card_number }}</td>
                                            <td>
                                                <p class="
                                                    @if ($voucher->status == 'redeemed')
                                                        {{ 'text-success' }}
                                                    @elseif ($voucher->status == 'canceled' || $voucher->status == 'forfeited')
                                                        {{ 'text-danger' }}
                                                    @else
                                                        {{ '' }}
                                                    @endif
                                                ">
                                                    {{ strtoupper($voucher->status) }}
                                                </p>
                                            </td>
                                            <td>{{ $voucher->destination->destination_name }}</td>
                                            <td>{{ $voucher->account['sales_deck'] }}</td>
                                            <td>{{ date('j-M-y', strtotime($voucher->date_issued)) }}</td>
                                            <td>{{ $voucher->date_redeemed != null ? date('j-M-y', strtotime($voucher->date_redeemed)) : '-' }}</td>
                                            <td>{{ $voucher->check_in != null ? date('j-M-y h:i a', strtotime($voucher->check_in)) : '-' }}</td>
                                            <td>{{ $voucher->check_out != null ? date('j-M-y h:i a', strtotime($voucher->check_out)) : '-' }}</td>
                                            <td>{{ date('j-M-y', strtotime($voucher->valid_to)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Pagination --}}
                            {{ $vouchers->appends([
                                'account' => $account_selected,
                                'date_from' => $date_from,
                                'date_to' => $date_to,
                                'per_page' => $per_page,
                            ])->links() }}

                        </div>
                    @endif
                </div>
            </div> {{-- card --}}
        </div> {{-- col --}}
    </div> {{-- row --}}

@endsection

@section('scripts')
    <script>
        $("#account_input").on('input', function () {
            var x = this.value;
            var z = $('#accounts');
            var val = $(z).find('option[value="' + x + '"]');
            var id = val.attr('id');

            $('#account').val(id);

            var members = $('#' + id).text();
            $('#account_members').text(members);
        });
    </script>
@endsection