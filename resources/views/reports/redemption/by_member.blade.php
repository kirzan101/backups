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
                    <th>#</th>
                    <th>Card Number</th>
                    <th>Date Issued</th>
                    <th>Status</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                </tr>
            </thead>                         
            <tbody>
                @foreach ($vouchers as $i=>$v)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $v->card_number }}</td>
                        <td>{{ date('M d, Y' , strtotime($v->date_issued)) }}</td>
                        <td>
                            <p class="
                                    @if ($v->status == 'redeemed')
                                        {{ 'text-success' }}
                                    @elseif ($v->status == 'canceled' || $v->status == 'forfeited')
                                        {{ 'text-danger' }}
                                    @else
                                        {{ '' }}
                                    @endif
                                ">
                                {{ strtoupper($v->status) }}
                            </p>
                        </td>
                        <td>{{ date('M d, Y' , strtotime($v->valid_from)) }}</td>
                        <td>{{ date('M d, Y' , strtotime($v->valid_to)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

@endsection