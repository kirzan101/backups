@extends('layouts.admin')

@section('title')
    VoucherMS | Reports - Collection
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Collection Reports</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h2> <i class="fa fa-credit-card"></i>  Collection</h2>
                        </div>
                        <div class="col-6"></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-10">
                            <form method="GET" action="/reports/collection" class="form-inline">
                                <div class="form-group">
                                    <label for="date_from">Date From</label>
                                    <input type="date" class="form-control mx-sm-3" id="date_from" name="date_from" value="{{ $date_from }}">
                                </div>
                                <div class="form-group">
                                    <label for="date_to">Date To</label>
                                    <input type="date" class="form-control mx-sm-3" id="date_to" name="date_to" value="{{ $date_to }}">
                                </div>
                                <button type="submit" class="btn btn-success mr-sm-2"><i class="fa fa-filter"></i> Filter</button> 
                                <a href="/reports/collection"><button type="button" class="btn btn-outline-dark"><i class="fa fa-undo"></i> Reset</button></a>
                            </form>
                        </div>
                        <div class="col-2 text-right">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-download"></i> Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="/reports/excel/collection/{{ $date_from }}/to/{{ $date_to }}">Excel</a>
                                    <a class="dropdown-item" href="/reports/pdf/collection/{{ $collection}}">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead class="thead-light">
                                <tr class="d-flex">
                                    <th class="col-3 text-center">#</th>
                                    <th class="col-3">Year</th>
                                    <th class="col-3">Month</th>
                                    <th class="col-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="d-flex">
                                    <td class="col-3">&nbsp;</td>
                                    <td class="col-3">&nbsp;</td>
                                    <td class="col-3">&nbsp;</td>
                                    <td class="col-3 text-right"><strong>Total: <u>Php {{ number_format($grand_total, 2) }}</u></strong></td>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($collection as $i=>$c)
                                    <tr class="d-flex">
                                        <td class="col-3 text-center">{{ $i + 1 }}</td>
                                        <td class="col-3">{{ $c->year }}</td>
                                        <td class="col-3">{{ $c->month }}</td>
                                        <td class="col-3 text-right">Php {{ number_format($c->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>                            
                        </table>
                    </div>                   
                </div>
            </div> {{-- card --}}
        </div> {{-- col --}}
    </div> {{-- row --}}

@endsection