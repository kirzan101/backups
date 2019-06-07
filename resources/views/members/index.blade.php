@extends('layouts.admin')

@section('title')
    VoucherMS | Members
@endsection

@section('content')
    <div id="members">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Members</li>
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
          <h3><i class="fa fa-fw fa-users"></i> Members</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="/members">
                <div class="row mb-3">
                    <div class="col-2">
                        <a class="btn btn-success" href="/members/create"><i class="fa fa-fw fa-user-plus"></i> Add Member</a>
                    </div>                        
                    <div class="col-5"></div>
                    <div class="col-5">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Search for members" aria-label="Search" value="{{ $search }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-search"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <div class="text-right" >
                    <form method="GET" action="/members" class="form-inline">
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
                        Showing {{ $members->total() }} results for <strong>{{ $search }}</strong>
                    </p>
                    @else
                    <p>
                        Showing <strong>{{ number_format($members->total()) }}</strong> total member records.
                    </p>
                    @endif

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center"># <a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Name <a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=last_name&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=last_name&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Email</th>

                                <th>Status <a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=status&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=status&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>
                                
                                <th>Created By <a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=created_by&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Created At <a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=created_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Updated At <a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/members?search={{ $search }}&per_page={{ $per_page }}&sort=updated_at&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $i=>$member)
                                <tr>
                                    {{-- $i + 1 + ($members->perPage() * ($members->currentPage() - 1)) --}}
                                     <th class="text-center">{{ $member->id }}</th>  
                                     <td><a href="/members/{{ $member->id }}" class="link-table">{{ $member->last_name . ', ' . $member->first_name }}</a></td>
                                     <td><a href="/members/{{ $member->id }}" class="link-table">{{ $member->email['email_address'] }}</a></td>
                                     <td><a href="/members/{{ $member->id }}" class="link-table">{{ ucwords($member->status) }}</a></td>
                                     <td><a href="/members/{{ $member->id }}" class="link-table">{{ $member->created_by }}</a></td>
                                     <td><a href="/members/{{ $member->id }}" class="link-table">{{ date("d M Y", strtotime($member->created_at)) }}</a></td>
                                     <td><a href="/members/{{ $member->id }}" class="link-table">{{ date("d M Y", strtotime($member->updated_at)) }}</a></td>
                                     <td><a href="/members/{{ $member->id }}" class="btn btn-outline-success">Details</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    {{ $members->appends([
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