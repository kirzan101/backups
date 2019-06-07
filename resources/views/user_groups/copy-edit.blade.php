@extends('layouts.admin')

@section('title')
    VoucherMS | Editing {{ucfirst($usergroup->user_group_name)}}
@endsection

@section('content')

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/user-groups">User Groups</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ucfirst($usergroup->user_group_name)}}</li>
            </ol>
        </nav>

       <div class="container-fluid">
            @if ($errors->has('modules_access'))
                <div class="alert alert-danger text-center" role="alert">
                    {{ $errors->first('modules_access') }}
                </div>
            @endif

            <form method="POST" action="/user-groups/{{ $usergroup->id }}" onsubmit="setFormSubmitting()">
                @method('PUT')
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $usergroup->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel"><i class="fa fa-fw fa-edit"></i> Edit {{ucfirst($usergroup->user_group_name)}}</h5>
                    </div>
                    <div class="modal-body">

                        <div class="form-row">
                            <div class="form-group col-4">
                                <label for="user_group_name">User Group Name</label>
                                <input type="text" class="form-control {{ $errors->has('user_group_name') ? ' is-invalid' : '' }}" id="user_group_name" name="user_group_name" value="{{ !old('user_group_name') ? $usergroup->user_group_name : old('user_group_name') }}" placeholder="Group Name">
                                @if ($errors->has('user_group_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('user_group_name') }}</strong>
                                    </span>
                                @endif
                            </div>                                                          
                        </div>

                        <div class="form-row">
                            <div class="form-group col-4">
                                <label for="description">Description</label>
                                <input type="text" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" id="description" name="description" value="{{ !old('description') ? $usergroup->description : old('description') }}" placeholder="Description of the group">
                                @if ($errors->has('description'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <hr>

                        <div class="form-row" style="margin:0;">
                            <div class="form-group required col-md-6" style="margin:0;">
                                <table class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Modules</th>
                                            <th scope="col">All Access</th>
                                            <th scope="col">Read</th>
                                            <th scope="col">Create</th>
                                            <th scope="col">Update</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($modlist as $mod)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ ucfirst($mod->module_name) }}</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input position-static cb-all" type="checkbox" id="all-{{ $mod->id }}" value="{{ $mod->id }}-all" aria-label="..." {{ old('modules_access') != null && in_array('read-' . $mod->id, old('modules_access')) && in_array('create-' . $mod->id, old('modules_access')) && in_array('update-' . $mod->id, old('modules_access')) ? 'checked' : '' }}
                                                        
                                                        @foreach ($modules_access as $i => $access)
                                                            @if ($access == $mod->id)
                                                                {{ strpos($modules_permissions[$i], 'rcu') !== false ? 'checked' : '' }}
                                                                @break
                                                            @endif
                                                        @endforeach
                                                        >
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input position-static cb-perm" type="checkbox" id="read-{{ $mod->id }}" name="modules_access[]" value="read-{{ $mod->id }}" aria-label="..." {{ old('modules_access') != null && in_array('read-' . $mod->id, old('modules_access')) ? 'checked' : '' }}
                                                        
                                                        @foreach ($modules_access as $i => $access)
                                                            @if ($access == $mod->id)
                                                                {{ strpos($modules_permissions[$i], 'r') !== false ? 'checked' : '' }}
                                                                @break
                                                            @endif
                                                        @endforeach
                                                        >
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input position-static cb-perm" type="checkbox" id="create-{{ $mod->id }}" name="modules_access[]" value="create-{{ $mod->id }}" aria-label="..." {{ old('modules_access') != null && in_array('create-' . $mod->id, old('modules_access')) ? 'checked' : '' }}
                                                        
                                                        @foreach ($modules_access as $i => $access)
                                                            @if ($access == $mod->id)
                                                                {{ strpos($modules_permissions[$i], 'c') !== false ? 'checked' : '' }}
                                                                @break
                                                            @endif
                                                        @endforeach
                                                        >
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input position-static cb-perm" type="checkbox" id="update-{{ $mod->id }}" name="modules_access[]" value="update-{{ $mod->id }}" aria-label="..." {{ old('modules_access') != null && in_array('update-' . $mod->id, old('modules_access')) ? 'checked' : '' }}
                                                        
                                                        @foreach ($modules_access as $i => $access)
                                                            @if ($access == $mod->id)
                                                                {{ strpos($modules_permissions[$i], 'u') !== false ? 'checked' : '' }}
                                                                @break
                                                            @endif
                                                        @endforeach
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
                        <a class="btn btn btn-outline-dark" href="/user-groups" role="button"><i class="fa fa-chevron-left"></i> Cancel</a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </form>
                
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