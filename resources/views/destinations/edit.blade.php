@extends('layouts.admin')

@section('title')
    VoucherMS | Edit {{ $destination->destination_name }}
@endsection

@section('content')

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/destinations">Destinations</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Destination</li>
            </ol>
        </nav>

       <div class="container-fluid">
           <h3>Edit Destination</h3>
           <br>

           <div class="row">
               <div class="col-md-6">
                    <form method="POST" action="/destinations/{{ $destination->id }}">
                        @method('PUT')
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="code">Code</label>
                            <input type="text" class="form-control {{ $errors->has('code') ? ' is-invalid' : '' }}" id="code" name="code" placeholder="Code" value="{{ !old('code') ? $destination->code : old('code') }}">
                            @if ($errors->has('code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" placeholder="Name" value="{{ !old('name') ? $destination->destination_name : old('name') }}">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control {{ $errors->has('remarks') ? ' is-invalid' : '' }}" style="resize: none;" id="remarks" name="remarks">{{ $destination->remarks }}</textarea>
                            @if ($errors->has('remarks'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('remarks') }}</strong>
                                </span>
                            @endif
                        </div>
                        <a href="/destinations" class="btn btn-outline-dark"><i class="fa fa-chevron-left"></i> Cancel</a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                    </form>
               </div>
           </div>           
        </div>
@endsection

@section('scripts')
    <script>
        $('.cb-all').change(function () {
            var checked = this.checked;
            var cbAll_id = this.id;
            var splitAll = cbAll_id.split("-");
            var row_id = splitAll[1];

            if (checked){
                $('#read-' + row_id).prop('checked', true);
                $('#create-' + row_id).prop('checked', true);
                $('#update-' + row_id).prop('checked', true);
            } else {
                $('#read-' + row_id).prop('checked', false);
                $('#create-' + row_id).prop('checked', false);
                $('#update-' + row_id).prop('checked', false);
            }
        });

        $('.cb-perm').change(function(){
            var cbPerm_id = this.id;
            var splitAll = cbPerm_id.split("-");
            var row_id = splitAll[1];

            if (!this.checked) {
                $('#all-' + row_id).prop('checked', false);
            } else {
                var read = $('#read-' + row_id).prop('checked');
                var create = $('#create-' + row_id).prop('checked');
                var update = $('#update-' + row_id).prop('checked');

                if (read && create && update){
                    $('#all-' + row_id).prop('checked', true);
                }
            }
        });
    </script>
@endsection