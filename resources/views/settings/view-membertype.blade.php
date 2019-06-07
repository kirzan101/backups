@extends('layouts.admin')

@section('title')
    Settings
@endsection

@section('content')
 <nav aria-label="breadcrumb">
     <ol class="breadcrumb">
            @foreach($types as $type)
            <li class="breadcrumb-item"><a href="/settings">Settings</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $type->type}}</li>
        </ol>
    </nav>

   <div class="container-fluid">
       <div class="card">
 {{--  <h5 class="card-header">{{ $usergroup->user_group_name}}</h5>
  <div class="card-body">
    <h5 class="card-title">Module Access</h5>

    <p class="card-text">
        <ul>
        @foreach($modules as $key=>$module)
            <li>{{ucfirst($modules[$key])}}</li>
        @endforeach
        </ul>
    </p>
 --}}
    <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h5> <i class="fa fa-user"></i> Membership Type</h5>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4 text-right">
                        <a class="btn btn-outline-success" href="/member-type/{{ $type->id }}/edit"><i class="fa fa-edit"></i> Edit Membership Type</a>
                    </div>
                </div>
            </div>
    <div class="card-body">
    <p class="card-text">
        <table class="table">
            <thead>
                <tr>
                <th scope="col">ID #</th>
                <th scope="col">Type</th>
                <th scope="col">Created_at</th>
                </tr>
            </thead>
            <tbody>
                
                <tr>
                <th scope="row">{{ $type->id}}</th>
                <td>{{$type->type}}</td>
                <td>{{$type->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </p>
    </div>
    {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
  </div>
</div>
      
   </div>

@endsection