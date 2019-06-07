@extends('layouts.admin')

@section('title')
    VoucherMS | Membership Types
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
            <h3 class="display-4">Membership Types</h3>
        </div>
                        
        <br>
        <hr>
        <br>

        @if ($canCreate)
            <div class="row">
                <a class="btn btn-success" href="/member-types/create" role="button"><i class="fa fa-plus"></i> Add New</a>
            </div>
        @endif

        <br>
        
        <div class="row">
            <div class="col-12">
                <table class="table table-hover">
                    <thead class="">
                        <tr class="" >
                            <th scope="col" style="border:none;">ID</th>
                            <th scope="col" style="border:none;">Type</th>
                            <th scope="col" style="border:none;">Created By</th>
                            <th scope="col" style="border:none;">Created At</th>
                            <th scope="col" style="border:none;">Updated By</th>
                            <th scope="col" style="border:none;">Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($member_types as $type)
                        <tr>
                            <td><a class="link-table" href="/member-types/{{$type->id}}/edit">{{$type->id}}</td>
                            <td><a class="link-table" href="/member-types/{{$type->id}}/edit">{{$type->type}}</td>
                            <td><a class="link-table" href="/member-types/{{$type->id}}/edit">{{$type->created_by}}</td>
                            <td><a class="link-table" href="/member-types/{{$type->id}}/edit">{{ date("d M Y", strtotime($type->created_at)) }}</td>
                            <td><a class="link-table" href="/member-types/{{$type->id}}/edit">{{$type->updated_by}}</td>
                            <td><a class="link-table" href="/member-types/{{$type->id}}/edit">{{ date("d M Y", strtotime($type->updated_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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