<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/tryIt.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- jQuery Jcrop -->
    <link href="{{ asset('css/jquery.Jcrop.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery.Jcrop.min.js') }}" defer></script>

    <!-- Font Awesome-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    
    <!-- sweetalert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- autoSize -->
    <script src="{{ asset('js/autosize.min.js') }}" defer></script>

    <!-- Swiper Slider -->
    <link href="{{ asset('css/swiper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/swiper.min.js') }}" defer></script>

    <!-- jQuery UI -->
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery-ui.js') }}" defer></script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('登入') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('註冊') }}</a>
                                </li>
                            @endif
                        @else
                        	@auth
			                    <ul class="navbar-nav mr-auto">
			                        <form class="form-inline my-2 my-lg-0" action="{{ route('search') }}" method="POST">
			                        	@csrf
			                            <div class="input-group">
			                                <div class="input-group-prepend">
			                                    <div class="input-group-text"><i class="fas fa-search"></i></div>
			                                </div>
			                                <input class="form-control mr-2" id="search" name="search" type="search" placeholder="Search" aria-label="Search">
			                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			                            </div>
			                        </form>
			                    </ul>
		                    @endauth
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if ($smallSource != null)
                                        <img class="smallSource" src="{{ asset($smallSource) }}" />
                                    @endif
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('stories', ['whoYouAre' => Auth::user()->id]) }}">
                                        {{ __('Your Stories') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        {{ __('Profile') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
	    $(function() {
        	$("#search").autocomplete({
        		source: '{!! URL::route('autocomplete') !!}',
        		minLength: 1,
        		autoFocus:true,
        	});
        });
    </script>
</body>
</html>
