@include('includes.header')

  <div class="content-wrapper">

    @include('includes.nav')

    <div class="container-fluid">
      @yield('content')
    </div>
    
  </div>

  @include('includes.footer')
