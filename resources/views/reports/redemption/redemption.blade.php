@extends('layouts.admin')

@section('title')
    VoucherMS | Reports - Redemption
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Redemption Reports</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h2> <i class="fa fa-calendar"></i> Redemption</h2>
                        </div>
                        <div class="col-6"></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-10">
                            <form method="GET" action="/reports/redemption">
                                <div class="form-row">
                                    <label class="form-label mr-4">Report Type</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input reportType" type="radio" name="reportType" id="monthly" value="monthly" {{ $report_type == 'monthly' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="monthly">Monthly</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input reportType" type="radio" name="reportType" id="yearly" value="yearly" {{ $report_type == 'yearly' || old('reportType') == 'yearly' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="yearly">Yearly</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input reportType" type="radio" name="reportType" id="byAccount" value="byAccount" {{ $report_type == 'byAccount' || old('reportType') == 'byAccount' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="byAccount">By Account</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input reportType" type="radio" name="reportType" id="byMember" value="byMember" {{ $report_type == 'byMember' || old('reportType') == 'byMember' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="byMember">By Member</label>
                                    </div>
                                </div>

                                <br>

                                <div class="form-row mb-2" id="row-account">
                                    <div class="col-4">
                                        <label for="account_input">Account</label>
                                        <input class="form-control" id="account_input" name="account_input" list="accounts" placeholder="Begin typing to search for accounts" autocomplete="off" value="{{ isset($account_input) && $account_input != null ? $account_input : '' }} ">
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

                                        <input type="hidden" id="account" name="account" value="{{ isset($account_selected) && $account_selected != null ? $account_selected : '' }}">
                                    </div>
                                </div>

                                <div class="form-row mb-2" id="row-member">
                                    <div class="col-4">      
                                        <label for="member_input">Member</label>                              
                                        <input class="form-control {{ $errors->has('member_input') ? ' is-invalid' : '' }}" id="member_input" name="member_input" list="members" placeholder="Begin typing to search for members" autocomplete="off" value="{{ isset($member_input) && $member_input != null ? $member_input : '' }}">
                                        <datalist id="members">
                                            @foreach ($members as $member)
                                                <option id="{{ $member->id }}" value="{{ $member->last_name . ',' . $member->first_name . $member->middle_name }}"></option>
                                            @endforeach
                                        </datalist>
                                        @if ($errors->has('member_input') || $errors->has('member'))
                                            <span class="invalid-feedback">
                                                <strong>Please select a valid member.</strong>
                                            </span>
                                        @endif

                                        <input type="hidden" id="member" name="member" value="{{ isset($member_selected) && $member_selected != null ? $member_selected : '' }}">
                                    </div>
                                </div>

                                <div class="form-row mb-2" id="row-monthly">
                                    <div class="col-2">
                                        <label for="date_from">Year</label>
                                        <select class="form-control" id="year" name="year">
                                            @for ($y = date('Y'); $y >= date('Y') - 50; $y--)
                                                <option value="{{ $y }}"{{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row mb-2" id="row-yearly">
                                    <div class="col-2">
                                        <label for="date_from">From </label>
                                        <select class="form-control {{ $errors->has('year_from') ? ' is-invalid' : '' }}" id="year_from" name="year_from">
                                            @for ($y = date('Y'); $y >= date('Y') - 50; $y--)
                                                <option value="{{ $y }}"{{ $year_from == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                        @if ($errors->has('year_from'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('year_from') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-2">
                                        <label for="date_to">To</label>
                                        <select class="form-control {{ $errors->has('year_to') ? ' is-invalid' : '' }}" id="year_to" name="year_to">
                                            @for ($y = date('Y'); $y >= date('Y') - 50; $y--)
                                                <option value="{{ $y }}"{{ $year_to == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                        @if ($errors->has('year_to'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('year_to') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row mb-2">
                                    <div class="col-2">
                                        <label for="destination">Destination </label>
                                        <select class="form-control" id="destination" name="destination">
                                            <option value="all">ALL</option>
                                            @foreach ($destinations as $des)
                                                <option value="{{ $des->id }}" {{ $destination == $des->id ? 'selected' : '' }}>{{ $des->destination_name }} ({{ $des->remarks }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button class="btn btn-success" type="submit"><i class="fa fa-filter"></i> Filter</button>
                            </form>
                        </div>
                        <div class="col-2 text-right">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-download"></i> Export
                                </button>

                                @if ($report_type == 'monthly' || $report_type == 'yearly')
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="/reports/excel/redemption/{{ $report_type }}/year/{{ $year }}/from/{{ $year_from }}/to/{{ $year_to }}/des/{{ $destination }}">Excel</a>
                                        <a class="dropdown-item" href="/reports/pdf/redemption/{{ $report_type }}/year/{{ $year }}/from/{{ $year_from }}/to/{{ $year_to }}/des/{{ $destination }}">PDF</a>
                                    </div>

                                {{-- By account or member --}}
                                @else
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="/reports/excel/redemption/part/{{ $report_type }}/id/{{ $report_type == 'byAccount' ? $account_selected : $member_selected }}/from/{{ $year_from }}/to/{{ $year_to }}/des/{{ $destination }}">Excel</a>
                                        <a class="dropdown-item" href="/reports/pdf/redemption/part/{{ $report_type }}/id/{{ $report_type == 'byAccount' ? $account_selected : $member_selected }}/from/{{ $year_from }}/to/{{ $year_to }}/des/{{ $destination }}">PDF</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="table-responsive">
                        @yield('table')
                    </div>                   
                </div>
            </div> {{-- card --}}
        </div> {{-- col --}}
    </div> {{-- row --}}

@endsection

@section('scripts')
    <script>
        $(function() {
            var selected = $('input[name=reportType]:checked').val();

            if(selected == 'monthly'){
                $('#row-yearly').hide();
                $('#row-monthly').show();
                $('#row-account').hide();
                $('#row-member').hide();
                $('#year_from').val($('#year_to').val());

            } else if (selected == 'yearly'){
                $('#row-yearly').show();
                $('#row-monthly').hide();
                $('#row-account').hide();
                $('#row-member').hide();

            } else if (selected == 'byAccount'){
                $('#row-account').show();
                $('#row-yearly').show();
                $('#row-monthly').hide();
                $('#row-member').hide();
            
            } else {
                $('#row-member').show();
                $('#row-yearly').show();
                $('#row-monthly').hide();
                $('#row-account').hide();
            }

            $('.reportType').change(function() {
                if ($(this).val() == 'monthly'){
                    $('#row-yearly').hide();
                    $('#row-monthly').show();
                    $('#row-account').hide();
                    $('#row-member').hide();
                    $('#year_from').val($('#year_to').val());

                } else if ($(this).val() == 'yearly') {
                    $('#row-yearly').show();
                    $('#row-monthly').hide();
                    $('#row-account').hide();
                    $('#row-member').hide();
                
                } else if ($(this).val() == 'byAccount'){
                    $('#row-account').show();
                    $('#row-yearly').show();
                    $('#row-monthly').hide();
                    $('#row-member').hide();
                
                } else {
                    $('#row-member').show();
                    $('#row-yearly').show();
                    $('#row-monthly').hide();
                    $('#row-account').hide();
                }
            });

            $("#account_input").on('input', function () {
                var x = this.value;
                var z = $('#accounts');
                var val = $(z).find('option[value="' + x + '"]');
                var id = val.attr('id');

                $('#account').val(id);

                var members = $('#' + id).text();
                $('#account_members').text(members);
            });

            $("#member_input").on('input', function () {
                var x = this.value;
                var z = $('#members');
                var val = $(z).find('option[value="' + x + '"]');
                var id = val.attr('id');

                $('#member').val(id);
            });
        });
    </script>
@endsection