@extends('reports.redemption.redemption')

@section('table')

    @if ($vouchers->count() == 0)
        <div class="alert alert-warning" role="alert">
            No vouchers found.
        </div>
    @else
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th>Month</th>
                    <th>Destination</th>
                    <th>Total Vouchers</th>
                    <th>Unused</th>
                    <th>Redeemed</th>
                    <th>Canceled</th>
                    <th>Forfeited</th>
                </tr>
            </thead>                         
            <tbody>
                @foreach ($vouchers as $v)
                    <tr>
                        <td>{{ $v->month }} {{ $report_type != 'monthly' ? $v->year : '' }}</td>
                        <td>{{ $v->destination->destination_name }}</td>
                        <td><a href="/reports/redemption/details/{{ $report_type != 'monthly' ? $v->year : $year }}/{{ $v->month }}/{{ $v->destination->id }}/all">{{ $v->vouchers }}</a></td>
                        <td><a href="/reports/redemption/details/{{ $report_type != 'monthly' ? $v->year : $year }}/{{ $v->month }}/{{ $v->destination->id }}/unused">{{ $v->unused }}</a></td>
                        <td><a href="/reports/redemption/details/{{ $report_type != 'monthly' ? $v->year : $year }}/{{ $v->month }}/{{ $v->destination->id }}/redeemed">{{ $v->redeemed }}</a></td>
                        <td><a href="/reports/redemption/details/{{ $report_type != 'monthly' ? $v->year : $year }}/{{ $v->month }}/{{ $v->destination->id }}/canceled">{{ $v->canceled }}</a></td>
                        <td><a href="/reports/redemption/details/{{ $report_type != 'monthly' ? $v->year : $year }}/{{ $v->month }}/{{ $v->destination->id }}/forfeited">{{ $v->forfeited }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection