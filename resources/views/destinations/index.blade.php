@extends('layouts.admin')

@section('title')
    VoucherMS | Destinations
@endsection

@section('content')

    <div class="container-fluid">

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
            <h3 class="display-4">Destinations</h3>
        </div>
                        
        <br>
        <hr>
        <br>

        @if ($canCreate)
            <div class="row">
                <a class="btn btn-success" href="/destinations/create" role="button"><i class="fa fa-plus"></i> Add New</a>
            </div>
        @endif

        <br>
            
        <table class="table table-hover">
            <thead class="">
                <tr class="" >
                    <th scope="col" style="border:none;">#</th>
                    <th scope="col" style="border:none;">Code</th>
                    <th scope="col" style="border:none;">Destination Name</th>
                    <th scope="col" style="border:none;">Remarks</th>
                    <th scope="col" style="border:none;">Created By</th>
                    <th scope="col" style="border:none;">Created At</th>
                    <th scope="col" style="border:none;">Updated By</th>
                    <th scope="col" style="border:none;">Updated At</th>
                    <th scope="col" style="border:non;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($destinations as $i=>$des)
                    <tr>
                        <th scope="row"><a class="link-table" href="/destinations/{{ $des->id }}/edit">{{ $i+1 }}</a></th>
                        <td><a class="link-table" href="/destinations/{{ $des->id }}/edit">{{ $des->code }}</td>
                        <td><a class="link-table" href="/destinations/{{ $des->id }}/edit">{{ $des->destination_name }}</td>
                        <td><a class="link-table" href="/destinations/{{ $des->id }}/edit">{{ $des->remarks }}</td>
                        <td><a class="link-table" href="/destinations/{{ $des->id }}/edit">{{ $des->created_by }}</td>                            
                        <td><a class="link-table" href="/destinations/{{ $des->id }}/edit">{{ date("d M Y", strtotime($des->created_at)) }}</td>
                        <td><a class="link-table" href="/destinations/{{ $des->id }}/edit">{{ $des->updated_by }}</td>                            
                        <td><a class="link-table" href="/destinations/{{ $des->id }}/edit">{{ date("d M Y", strtotime($des->updated_at)) }}</td>                            
                        <td>
                            <a class="btn btn-outline-success" href="/destinations/{{ $des->id }}/edit" title="Edit"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-outline-danger open-deleteModal" href="#" title="Delete" data-toggle="modal" data-target="#deleteModal" data-id="{{ $des->id }}"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="/destinations/destroy">
                    @method('DELETE')
                    {{ csrf_field() }}

                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Destination</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this destination?</p>
                        <input type="hidden" name="des_id" id="des_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i class="fa fa-fw fa-chevron-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-check"></i> Yes</button>
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
        $(document).on("click", ".open-deleteModal", function () {
            var destination = $(this).data('id');
            $(".modal-body #des_id").val( destination );
        });
    </script>
@endsection