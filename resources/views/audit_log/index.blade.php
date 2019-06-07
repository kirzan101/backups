@extends('layouts.admin')

@section('title')
    VoucherMS | Audit Log
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Audit Log</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-12">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert" id="success-alert">
                    <i class="fa fa-fw fa-check"></i> {{ session()->get('message') }}
                </div>
            @endif
        </div>
    </div>
    
    <div class="card mb-3">
        <div class="card-header">
          <h3><i class="fa fa-fw fa-list"></i> Audit Log</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="/audit-log">
                <div class="row mb-3">
                    <div class="col-7"></div>
                    <div class="col-5">                    
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search for logs" aria-label="Search" value="{{ $search }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-search"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <form method="GET" action="/audit-log" class="form-inline">
                        <div class="row">
                            <div class="col-6">
                                <label class="my-1 mr-2" for="module">Module: </label>
                                <select class="custom-select my-1 mr-sm-2 col-sm-3" id="module" name="module">
                                    <option value="">All</option>
                                    @foreach ($modules as $m)
                                    <option value="{{ $m->id }}" {{ $module == $m->id ? 'selected' : '' }}>{{ ucfirst($m->module_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- <div style="margin-left:10%;"></div> --}}

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
                        Showing {{ $logs->total() }} results for <strong>{{ $search }}</strong>
                    </p>
                    @endif

                    <br>

                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th class="text-center"># <a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Description <a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=description&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=description&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>User <a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=u_name&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=u_name&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Subject Type <a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=subject_type&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=subject_type&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Module <a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=m_name&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=m_name&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Created By <a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Created At <a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/audit-log?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $i=>$log)
                                <tr>
                                    <th class="text-center">{{ $i + 1 + ($logs->perPage() * ($logs->currentPage() - 1)) }}</th>
                                    <td><a href="/audit-log/{{ $log->id }}" class="link-table">{{ $log->description }}</a></td>
                                    <td><a href="/audit-log/{{ $log->id }}" class="link-table">{{ $log->user->username }}</a></td>
                                    <td><a href="/audit-log/{{ $log->id }}" class="link-table">{{ $log->subject_type }}</a></td>
                                    <td><a href="/audit-log/{{ $log->id }}" class="link-table">{{ $log->module->module_name }}</a></td>
                                    <td><a href="/audit-log/{{ $log->id }}" class="link-table">{{ $log->created_by }}</a></td>
                                    <td><a href="/audit-log/{{ $log->id }}" class="link-table">{{ $log->created_at }}</a></td>
                                    <td><a href="/audit-log/{{ $log->id }}" class="btn btn-outline-success">Details</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    {{ $logs->appends([
                        'search' => $search,
                        'per_page' => $per_page,
                        'sort' => app('request')->input('sort'),
                        'dir' => app('request')->input('dir')
                    ])->links() }}

                </div>
            </form>
        </div>
    </div> {{-- card --}}

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

            $('#module').change(function() {
                this.form.submit();
            });
        });
    </script>

@endsection