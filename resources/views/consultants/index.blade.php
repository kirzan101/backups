@extends('layouts.admin')

@section('title')
    VoucherMS | Consultants
@endsection

@section('content')

    <div class="container-fluid">
        <form method="GET" action="/consultants">
        {{-- Message --}}
        <div class="row">
            <div class="col-12">
                @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert" id="success-alert">
                        <i class="fa fa-fw fa-check"></i> {{ session()->get('message') }}
                    </div>
                @endif
            </div>
        </div> 

        {{-- TITLE --}}
        <div class="">
            <h3 class="display-4">Consultants</h3>
        </div>

        <hr>
                        
         <div class="row mb-3">
            <div class="col-1">
                @if ($canCreate)
                     <a class="btn btn-success" href="/consultants/create" role="button"><i class="fa fa-plus"></i> Add New</a>
                @endif
            </div>
            <div class="col-6"></div>
            <div class="col-5">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search for consultant" aria-label="Search" value="{{ $search }}">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-search"></i> Search</button>
                    </div>
                </div>
            </div>
        </div>
    
        {{-- PER PAGE FILTER --}}
        <div class="text-right">
            <form method="GET" action="/consultants" class="form-inline"></form>
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
                Showing {{ $consultants->total() }} results for <strong>{{ $search }}</strong>
            </p>
        @endif

        <table class="table table-hover table-sm">
            <thead class="">
                <tr class="" >
                    <th scope="col" style="border:none;">#</th>
                    <th scope="col" style="border:none;">Name</th>
                    <th scope="col" style="border:none;">Created By</th>
                    <th scope="col" style="border:none;">Created At</th>
                    <th scope="col" style="border:none;">Updated By</th>
                    <th scope="col" style="border:none;">Updated At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultants as $i=>$cons)
                    <tr>
                        <th scope="row"><a class="link-table" href="/consultants/{{ $cons->id }}/edit">{{ $i+1 }}</a></th>
                        <td><a class="link-table" href="/consultants/{{ $cons->id }}/edit">{{ $cons->name }}</td>
                        {{-- <td><a class="link-table" href="/consultants/{{ $cons->id }}/edit">{{ $cons->destination_name }}</td> --}}
                        <td><a class="link-table" href="/consultants/{{ $cons->id }}/edit">{{ $cons->created_by }}</td>
                        <td><a class="link-table" href="/consultants/{{ $cons->id }}/edit">{{ date("d M Y", strtotime($cons->created_at)) }}</td>
                        <td><a class="link-table" href="/consultants/{{ $cons->id }}/edit">{{ $cons->updated_by }}</td>
                        <td><a class="link-table" href="/consultants/{{ $cons->id }}/edit">{{ date("d M Y", strtotime($cons->updated_at)) }}</td>
                        <td>
                            <a class="btn btn-outline-success" href="/consultants/{{ $cons->id }}/edit" class="btn btn-primary">Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

         {{-- Pagination --}}
            {{ $consultants->appends([
                'search' => $search,
                'per_page' => $per_page,
                // 'sort' => app('request')->input('sort'),
                // 'dir' => app('request')->input('dir')
            ])->links() }}
        </form>
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