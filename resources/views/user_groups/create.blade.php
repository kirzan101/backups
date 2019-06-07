@extends('layouts.admin')

@section('title')
    VoucherMS | Add User Group
@endsection

@section('content')

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/user-groups">User Groups</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add User Group</li>
            </ol>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">                    

                    <form method="POST" action="/user-groups">
                        {{ csrf_field() }}
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addModalLabel"><i class="fa fa-fw fa-user-plus"></i> Add New User Group</h5>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="user_group_name">User Group Name</label>
                                        <input type="text" class="form-control {{ $errors->has('user_group_name') ? ' is-invalid' : '' }}" id="user_group_name" name="user_group_name" value="{{ old('user_group_name') }}" placeholder="Group Name">
                                        @if ($errors->has('user_group_name'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('user_group_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>                                                          
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="description">Description</label>
                                        <input type="text" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" id="description" name="description" value="{{ old('description') }}" placeholder="Description of the group">
                                        @if ($errors->has('description'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <hr>

                                @if ($errors->has('modules_access'))
                                    @foreach ($errors->get('modules_access') as $message)                                                
                                        <p class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </p>
                                    @endforeach
                                @endif

                                <div class="form-row" style="margin:0;">                                    
                                    <div class="form-group required col-md-6" style="margin:0;">                                        
                                        <table class="table table-bordered table-striped text-center">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Module</th>
                                                    <th scope="col">All Access</th>
                                                    <th scope="col">Read</th>
                                                    <th scope="col">Create</th>
                                                    <th scope="col">Update</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($modules as $mod)
                                                    <tr>
                                                        <th scope="row">{{ $loop->iteration }}</th>
                                                        <td>{{ ucfirst($mod->module_name) }}</td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input position-static cb-all" type="checkbox" id="all-{{ $mod->id }}" value="{{ $mod->id }}-all" aria-label="..." {{ old('modules_access') != null && in_array('read-' . $mod->id, old('modules_access')) && in_array('create-' . $mod->id, old('modules_access')) && in_array('update-' . $mod->id, old('modules_access')) ? 'checked' : '' }}
                                                                >
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input position-static cb-perm" type="checkbox" id="read-{{ $mod->id }}" name="modules_access[]" value="read-{{ $mod->id }}" aria-label="..." {{ old('modules_access') != null && in_array('read-' . $mod->id, old('modules_access')) ? 'checked' : '' }}
                                                                >
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input position-static cb-perm" type="checkbox" id="create-{{ $mod->id }}" name="modules_access[]" value="create-{{ $mod->id }}" aria-label="..." {{ old('modules_access') != null && in_array('create-' . $mod->id, old('modules_access')) ? 'checked' : '' }}
                                                                >
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input position-static cb-perm" type="checkbox" id="update-{{ $mod->id }}" name="modules_access[]" value="update-{{ $mod->id }}" aria-label="..." {{ old('modules_access') != null && in_array('update-' . $mod->id, old('modules_access')) ? 'checked' : '' }}
                                                                >
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach       
                                            </tbody>
                                        </table>
                                    </div>  
                                    <!-- Report Access -->
                                    <div class="form-group required col-md-6" style="margin:0;">                                        
                                            <table class="table table-bordered table-striped text-center">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4">Reports</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Access</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        @foreach($reports as $rep)
                                                            <tr>
                                                                <th scope="row">{{ $rep->id }}</th>
                                                                <td>{{ ucfirst($rep->name) }}</td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input position-static cb-rep" type="checkbox" id="access-{{ $rep->id }}" name="report_access[]" value="acess-{{ $rep->id }}" aria-label="..." {{ old('report_access') != null && in_array('access-' . $rep->id, old('report_access')) ? 'checked' : '' }}
                                                                        >
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach       
                                                    </tbody>
                                            </table>
                                    </div> 
                                </div>


                            <div class="modal-footer">
                                <a class="btn btn-outline-dark" href="/user-groups" role="button"><i class="fa fa-chevron-left"></i> Cancel</a>
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                            </div>
                        </div>
                    </form>  

                </div> <!-- col -->
            </div> <!-- row -->
        </div> <!-- container -->
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
    <!-- report -->
    <script>       
        $('.cb-rep').change(function(){
            var cbRep_id = this.id;
            var splitAll = cbRep_id.split("-");
            var row_id = splitAll[1];

            var unaccess = $('#access-' + row_id).prop('checked');
            
        });
    </script>
@endsection