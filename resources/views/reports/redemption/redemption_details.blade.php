@extends('layouts.admin')

@section('title')
    VoucherMS | Reports - Redemption Details
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/reports/redemption">Redemption Reports</a></li>
            <li class="breadcrumb-item active" aria-current="page">Redemption Details</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h2> <i class="fa fa-calendar"></i> Redemption Details</h2>
                        </div>
                        <div class="col-6"></div>
                    </div>
                </div>
                <div class="card-body">

                    <p>Showing <strong>{{ $vouchers->total() }}</strong> vouchers with <strong>{{ strtoupper($status) }}</strong> status</p> 
                    <p>for the month of <strong>{{ $month . ' ' . $year }}</strong></p>
                    <p>at <strong>{{ $destination->destination_name }}</strong> ({{ $destination->remarks }})</p>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Member(s)</th>
                                    <th>Voucher Number</th>

                                    @if ($status == 'all')
                                        <th>Status</th>
                                    @endif

                                    <th>Date Issued</th>
                                </tr>
                            </thead>                         
                            <tbody>
                                @foreach ($vouchers as $i=>$v)
                                    <tr>
                                        <th>{{ $i + 1 + ($vouchers->perPage() * ($vouchers->currentPage() - 1)) }}</th>
                                        <td>
                                            @foreach($v->account->accountMember as $name)
                                                {{ $name->member->last_name . ", " . $name->member->first_name}}
                                                 
                                                @if (!$loop->last)
                                                    {{ ' / ' }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $v->card_number }}</td>

                                        @if ($status == 'all')
                                            <td>{{ strtoupper($v->status) }}</td>
                                        @endif

                                        <td>{{ $v->date_issued }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        {{ $vouchers->links() }}

                    </div>                   
                </div>
            </div> {{-- card --}}
        </div> {{-- col --}}
    </div> {{-- row --}}

@endsection