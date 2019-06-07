@extends('layouts.admin')

@section('title')
    VoucherMS | Audit Log - {{ $log->id }}
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/audit-log">Audit Log</a></li>
        <li class="breadcrumb-item active" aria-current="page">View Log</li>
    </ol>
</nav>
<div class="col-10">
    <div class="card mb-3">
        <div class="card-header">
            <h3><i class="fa fa-fw fa-list"></i> Audit Log</h3>
        </div>
        <div class="card-body">
            <form>
                <fieldset disabled>
                    <div class="form-group row">
                        <label for="id" class="font-weight-bold col-sm-2 col-form-label text-right">Log ID: </label>
                        <div class="col-sm-1">
                            <input type="text" readonly class="form-control-plaintext" id="id" value="{{ $log->id }}">
                        </div>

                        <div class="col-sm-2"></div>

                        <label for="date" class="font-weight-bold col-sm-2 col-form-label text-right">Log Date: </label>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control-plaintext" id="date" value="{{ date('M d, Y \a\t h:i:s a', strtotime($log->created_at)) }}">
                        </div>
                    </div>
                   

                    <div class="form-group row text-right">
                        <label for="user" class="font-weight-bold col-sm-2 col-form-label text-right">User: </label>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control-plaintext" id="user" value="{{ $user->username }}">
                        </div>
                    
                        <label for="user_name" class="font-weight-bold col-sm-2 col-form-label text-right">User Name: </label>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control-plaintext" id="user_name" value="{{ $user->first_name . ' ' . $user->last_name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                         <label for="subject" class="font-weight-bold col-sm-2 col-form-label text-right">PC Name: </label>
                        <div class="col-sm-2">
                            <input type="text" readonly class="form-control-plaintext" id="subject" value="{{ $log->subject_type }}">
                        </div>
                    </div>

                    <br><hr><br>
                    
                    <div class="form-group row">
                        <label for="module" class="font-weight-bold col-sm-2 col-form-label text-right">Module: </label>
                        <div class="col-sm-2">
                            <input type="text" readonly class="form-control-plaintext text-align-left" id="module" value="{{ $module->module_name }}">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="description" class="font-weight-bold col-sm-2 col-form-label text-right">Description: </label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="description" value="{{ $log->description }}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="properties" class="font-weight-bold col-sm-2 col-form-label text-right">Properties: </label>
                        <div class="col-sm-8">
                            <textarea class="form-control-plaintext" id="properties" rows="8">
                                {{ $log->properties }}
                            </textarea>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>


@endsection