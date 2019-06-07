<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'VoucherMS') }}</title>

  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Prata" rel="stylesheet">
  
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/dataTables.bootstrap4.css') }}" rel="stylesheet">
  <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">
</head>
<style>
    * {
      margin:0;
      padding:0;
      /* border:red solid 1px; */
    }

    html,body,.centereddiv, .row, .col {
      height: 100%;
    }
    
    #form-con, #word-con {
    }

    #form-con, #word-con {
      width: 70%;
    }

    #form-con {
      margin-top: 10%;
    }

    #word-con {
      margin-top: 10%;
      text-align: center;
    } 

    img {
      margin-bottom: 5%;
    } 

    .layer {
      background-color: rgba(139, 195, 75, 0.8);
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    } 

    h1 {
      text-shadow: 2px 2px 5px #ababab;
      font-size: 60px;
      color: aliceblue;
    }

    p {
      font-size: 25px;
      font-weight: bold;
    }

 @media screen and (max-width: 1000px) {
  #left {
    visibility: hidden;
    clear: both;
    float: left;
    margin: 10px auto 5px 20px;
    width: 28%;
    display: none;
  }

  h1 {
    transform: 50%;
  }

  #image {
    visibility:visible;

  }

  #desc {
    visibility: hidden;
  }

  #text {
    margin:10% 0%;
    visibility: visible;
  }
}

@media screen and (min-width: 1000px) {

  #image {
    visibility:hidden; 
  }

  #text {
    visibility: hidden;
  }
}
</style>

<body> 

  <div class="centereddiv">
    <div class="row">
    <div class="col" id="left" style="background-image:url({{ asset('images/login_page.JPG') }});background-position:center; background-size:covers; background-repeat:no-repeat; ">
        <div class="layer">     
        <div class="container" id="word-con">
          <div>
            <img src="{{ asset('images/astoria.png') }}" style="position:relative; top:10px; width:188px; heght:162px;  ">
          </div>
          <h1 style="margin-top:10%;">
            Voucher<br> Management <br> System
          </h1>  
        </div>
      </div>
      </div>  


      <div class="col">
          <div class="container" id="form-con" style="padding:auto; margin:0 auto;">
            <div class="form-container" style="margin:40% auto;">
              <p id="desc" style="font-size:20px;">Log In</p>
      
            @if(session()->has('error'))            
                <p class="text-danger"><small><i class="fa fa-fw fa-exclamation"></i> {{ session()->get('error') }}</small></p>
              @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

              <div class="form-group">
                <label for="username" class="col-form-label ">{{ __('Username') }}</label>
                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                @if ($errors->has('username'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif            
              </div>

              <div class="form-group">
                <label for="password" class="col-form-label text-left">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group">
                {{-- <div class="form-check"> --}}
                    <label class="form-check-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                    </label>
                {{-- </div> --}}
              </div>
              <button type="submit" class="btn btn-primary btn-block">
                {{ __('Login') }}
              </button>
            </form>
            </div>
          </div>
      </div>
    </div>
  </div>
  {{-- <div class="container text-center">
   <div class="col-lg-4 col-lg-offset-4">
     <div class="col-md">hi</div>
     <div class="col-md">hello</div>
   </div>
  </div>
 --}}
 
  
  
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/jquery.Chart.min.js') }}"></script>
    <script src="{{ asset('js/jquery.jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('js/sb-admin.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-datatables.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-charts.min.js') }}"></script>
</body>
</html>