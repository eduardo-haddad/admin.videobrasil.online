<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lead Feedback System</title>

    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Date Time Picker -->
    <link href="{{ asset('css/datetimepicker.bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Date Time Range Picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Font Awesome -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('css/nprogress.css') }}" rel="stylesheet">
    <!-- PNotify -->
    <link href="{{ asset('css/pnotify.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pnotify.buttons.css') }}" rel="stylesheet">
    <!-- Datatables -->
    <link href="{{ asset('css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <!-- Switchery -->
    <link href="{{ asset('css/switchery.min.css') }}" rel="stylesheet">
    <!-- jQuery Custom Content Scroller -->
    <link href="{{ asset('css/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet"/>
    <!-- Custom Theme Style -->
    <link href="{{ asset('css/custom.min.css') }}" rel="stylesheet">
    <!-- App Style -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Feedback Style -->
    <link href="{{ asset('feedback/css/app.css') }}" rel="stylesheet">

    @stack('styles')
  </head>
  <body>
    <div class="container body">
      <div class="main_container">
        @include('feedback::layouts.partials.navigation')

        <div role="main">
          @yield('content')
        </div>

        {{--<footer>
          <div>
            © Associação Cultural Videobrasil
          </div>
          <div class="clearfix"></div>
        </footer>--}}
      </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- jQuery custom content scroller -->
    <script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- Bootstrap Date Time Picker -->
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment.pt-br.js') }}"></script>
    <script src="{{ asset('js/datetimepicker.bootstrap.min.js') }}"></script>
    <!-- Bootstrap Date Time Range Picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- NProgress -->
    <script src="{{ asset('js/nprogress.js') }}"></script>
    <!-- PNotify -->
    <script src="{{ asset('js/pnotify.js') }}"></script>
    <script src="{{ asset('js/pnotify.buttons.js') }}"></script>
    <!-- Validator -->
    <script src="{{ asset('js/validator.js') }}"></script>
    <!-- Cleave -->
    <script src="{{ asset('js/cleave.min.js') }}"></script>
    <!-- Datatables -->
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <!-- Switchery -->
    <script src="{{ asset('js/switchery.min.js') }}"></script>
    <!-- Custom Theme Scripts -->
    <script src="{{ asset('js/custom.js' )}}"></script>
    <!-- App Prototype -->
    <script src="{{ asset('js/app.prototype.js') }}"></script>
    <!-- App Tables -->
    <script src="{{ asset('js/app.tables.js') }}"></script>

    <!-- Global variables -->
    <script>
      window.BASE_URL = '{{ url('/') }}';
      window.DATE_FORMAT = 'DD/MM/YYYY';
      window.DATETIME_FORMAT = 'DD/MM/YYYY HH:mm';
    </script>

    @stack('scripts')
  </body>
</html>
