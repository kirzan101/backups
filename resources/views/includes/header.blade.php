<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>
    @yield('title')
  </title>

  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.4/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
  
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/dataTables.bootstrap4.css') }}" rel="stylesheet">
  <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">
</head>

{{-- <style>
  .navbar, .btn, .border { background-color: gray !important; border: gray !important; color:white !important; }
</style> --}}
<body class="fixed-nav sticky-footer bg-dark" id="page-top">