@extends('layouts.admin')

@section('title')
    VoucherMS | Edit {{ $consultant->name }}
@endsection

@section('content')

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/consultants">Consultants</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Consultant</li>
            </ol>
        </nav>

        <div class="container-fluid">
           <h3>Edit Consultant</h3>
           <hr>

            <div class="row">
                <div class="col-md-6">
                    <form method="POST" action="/consultants/{{ $consultant->id }}">
                        @method('PUT')
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" placeholder="Name" value="{{ !old('name') ? $consultant->name : old('name') }}">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <a href="/consultants" class="btn btn-outline-dark"><i class="fa fa-chevron-left"></i> Cancel</a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                    </form>
                </div>
            </div>           
        </div>
@endsection