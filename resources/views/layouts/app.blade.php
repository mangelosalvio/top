<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">


    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <!-- Scripts -->
    <script src="/js/app.js"></script>


    <link href="/css/default.min.css" rel="stylesheet">
    <link href="/css/semantic.min.css" rel="stylesheet">
    <link href="/css/alertify.min.css" rel="stylesheet">
    <script src="/js/alertify.min.js"></script>

    <link rel="stylesheet" href="/css/bootstrap-datepicker3.min.css"/>
    <script src="/js/bootstrap-datepicker.min.js"></script>
    <script>
        showLoading = function(){
            $('.loading-screen').show();
        };
        hideLoading = function(){
            $('.loading-screen').hide();
        };
    </script>
</head>
<body id="app">

    <div class="loading-screen"
         style="text-align:center; position:fixed; top:10px; left:0px; width:100%; display:none; z-index:9999;;">
        <span style=" display:inline-block; width:250px; padding:5px; 100px; font-weight:bold; background-color:#FDEF97; ">
          Loading...
        </span>
    </div>

    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'Unlifinance') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                @include('partials.menu')

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                    @else
                        <li><a href="{{ url('/register') }}">Register</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @if( Session::get('flash_message') )
    <div class="container-fluid">
        <div class="row">
            <div id="success-alert" class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data
                        -dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>{{ Session::get('flash_message') }}</strong>
            </div>
        </div>
    </div>
    @endif

    @if( Session::get('error') )
        <div class="container-fluid">
            <div class="row">
                <div id="success-alert" class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data
                            -dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>{{ Session::get('error') }}</strong>
                </div>
            </div>
        </div>
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

    @if( Session::get('flash_message') )
    <script>
        setTimeout(function(){
            $("#success-alert").slideUp(500);
        },2000);
    </script>
    @endif

    <script>
        $('.date').change(function(){
            $(this).val(moment($(this).val()).format('YYYY-MM-DD'))
        });

        $('.datepicker').datepicker({
            format : 'yyyy-mm-dd',
            changeMonth: true,
            changeYear: true,
            autoclose : true
        });
    </script>

</body>
</html>
