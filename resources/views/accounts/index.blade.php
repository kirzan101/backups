@extends('layouts.admin')

@section('title')
    VoucherMS | Accounts
@endsection

@section('content')

    <div id="accounts">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Accounts</li>
        </ol>
    </nav>

    <div class="card mb-3">
        <div class="card-header">
            <h3><i class="fa fa-fw fa-credit-card"></i> Accounts</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="/accounts">
                <div class="row mb-3">
                    <div class="col-7"></div>
                    <div class="col-5">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search for accounts" aria-label="Search" value="{{ $search }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-search"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div class="text-right">
                        <form method="GET" action="/accounts" class="form-inline">
                            <label class="my-1 mr-2" for="per_page">Entries per page: </label>
                            <select class="custom-select my-1 mr-sm-2 col-sm-1" id="per_page" name="per_page">
                                <option value="10" {{ $per_page == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $per_page == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $per_page == '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </form>
                    </div>

                    @if ($search != '')
                    <p>
                        Showing {{ $accounts->total() }} results for <strong>{{ $search }}</strong>
                    </p>
                    @else
                    <p>
                        Showing <strong>{{ number_format($accounts->total()) }}</strong> total account records.
                    </p>
                    @endif

                    <table class="table table-hover">
                        <thead>
                           <tr>
                            <th># <a href="/accounts?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/accounts?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Member(s)</th>

                            <th>Consultant <a href="/accounts?search={{ $search }}&per_page={{ $per_page }}&sort=consultant_id&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/accounts?search={{ $search }}&per_page={{ $per_page }}&sort=consultant_id&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>
                            
                            <th>Created By <a href="/accounts?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/accounts?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Created At <a href="/accounts?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/accounts?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Updated At <a href="/accounts?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/accounts?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                            <th>Action</th>

                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $i=>$account)
                               <tr>
                                    <td><a href="/accounts/{{ $account->id }}" class="link-table">{{ $i + 1 + ($accounts->perPage() * ($accounts->currentPage() - 1)) }}</a></td>
                                    <td style="width:15%;">
                                        <a href="/accounts/{{ $account->id }}" class="link-table">
                                            @foreach($account->members as $member)
                                               • {{$member->last_name}}, {{$member->first_name}}<br>
                                            @endforeach
                                        </a>
                                    </td>
                                    <td><a href="/accounts/{{ $account->id }}" class="link-table">{{ $account->consultant->name }}</a></td>
                                    <td><a href="/accounts/{{ $account->id }}" class="link-table">{{ $account->created_by }}</a></td>
                                    <td><a href="/accounts/{{ $account->id }}" class="link-table">{{ date("d M Y", strtotime($account->created_at)) }}</a></td>
                                    <td><a href="/accounts/{{ $account->id }}" class="link-table">{{ date("d M Y", strtotime($account->updated_at)) }}</a></td>
                                    <td><a class="btn btn-outline-success" href="/accounts/{{ $account->id }}" class="link-table">Details</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{-- Pagination --}}
                    {{ $accounts->appends([
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