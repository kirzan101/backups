@extends('layouts.admin')

@section('title')
    VoucherMS | Add Membership Type
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/member-types">Membership Types</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Membership Type</li>
        </ol>
    </nav>

    @if ($errors->any())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <i class="fa fa-fw fa-exclamation"></i>Your form has error(s). Please check all the fields.
            </div>
        </div>
    </div>
    @endif

    <div class="card mb-3">
        <div class="card-header">
            <h3><i class="fa fa-fw fa-user-plus"></i> Add Membership Type</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/member-types" onsubmit="setFormSubmitting()">
                {{ csrf_field() }}
                <br>
                <div class="form-row">
                    <div class="form-group required col-md-4">
                        <label class="control-label" for="type">Membership Type Name</label>
                        <input type="text" class="form-control {{ $errors->has('type') ? ' is-invalid' : '' }}" id="type" name="type" value="{{ !old('type') ? "": old('type') }}" placeholder="Type">
                        @if ($errors->has('type'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
                    </div>                            
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <a href="/member-types"><button type="button" class="btn btn-outline-dark"><i class="fa fa-chevron-left"></i> Cancel</button></a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script>
        var formSubmitting = false;
        var setFormSubmitting = function() { formSubmitting = true; };

        window.onload = function() {
            window.addEventListener("beforeunload", function (e) {
                if (formSubmitting) {
                    return undefined;
                }

                var confirmationMessage = 'It looks like you have been editing something. '
                                        + 'If you leave before saving, your changes will be lost.';

                (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
            });
        };
    </script> --}}
@endsection