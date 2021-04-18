<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Videobrasil Online</title>

    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('css/nprogress.css') }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ asset('css/custom.min.css') }}" rel="stylesheet">
    <!-- App Style -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  </head>
  <body class="login">
    <body class="login">
      <div>
        <div class="login_wrapper">
          @yield('content')

          @admin
            <div class="clearfix"></div>

            <div class="text-center">
              <a href="{{ route('home') }}"><img style="width:70%" src="{{ asset('images/ai.png') }}" /></a>

              <p class="mt-15">© Associação Cultural Videobrasil</p>
            </div>
          @endadmin
        </div>
      </div>

    </body>
  </body>
</html>
