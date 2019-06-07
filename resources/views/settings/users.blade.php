@extends('settings.index')

@section('insidepage')
{{-- BREADCRUMBS --}}
{{--   <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Settings</li>
    </ol>
  </nav> --}}

{{--  --}}
    <div class="col-10">
        <div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-5">
        <h3><i class="fa fa-fw fa-users"></i>Users</h3>
      </div>
      <div class="col-2"></div>
      <div class="col-5 text-right">
         <a class="btn btn-primary" href="/setting-create-group" role="button">Add New User</a>
      </div>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-hover">
      <thead class="">
        <tr class="" >
          <th scope="col" style="border:none;">User Name</th>
          <th scope="col" style="border:none;">User Group Name</th>
          <th scope="col" style="border:none;">Users</th>
          <th scope="col" style="border:none;">Actions</th>
        </tr>
      </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>Admin</td>
        <td>1</td>
        <td>
          <a class="btn btn-primary text-light" :href="'/members/' + member.id" class="btn btn-primary">  Edit </a>
          <a class="btn btn-danger text-light" :href="'/members/' + member.id" class="btn btn-primary">Delete</a>
        </td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Accounting</td>
        <td>5</td>
        <td>
          <a class="btn btn-primary text-light" :href="'/members/' + member.id" class="btn btn-primary">  Edit </a>
          <a class="btn btn-danger text-light" :href="'/members/' + member.id" class="btn btn-primary">Delete</a>
        </td>
      </tr>
      <tr>
        <th scope="row">3</th>
        <td>Redemptions</td>
        <td>3</td>
        <td>
          <a class="btn btn-primary text-light" :href="'/members/' + member.id" class="btn btn-primary">  Edit </a>
          <a class="btn btn-danger text-light" :href="'/members/' + member.id" class="btn btn-primary">Delete</a>
        </td>
      </tr>
    </tbody>
  </table>
  </div>
</div>
    </div>
@endsection

<style>
  .table-borderless td,
  .table-borderless th {
    border: 0;
}
</style>