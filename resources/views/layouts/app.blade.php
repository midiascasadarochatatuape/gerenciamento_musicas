<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="icon" href="{{ asset('/assets/img/favicon.webp') }}" type="image/x-icon">

    <!-- Styles -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    @stack('styles')
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-3 mb-4">
            <div class="container">
                <a class="navbar-brand me-lg-5 me-0" href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/logo-horizontal-branco.svg') }}" height="55" alt="">
                </a>
                <button class="navbar-toggler py-2 d-md-none d-flex bg-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="material-symbols-outlined text-white">menu</span>
                </button>

                <div class="collapse navbar-collapse flex-column flex-lg-row" id="navbarSupportedContent">
                    <div class="d-flex justify-content-between flex-md-row flex-column-reverse align-items-center flex-fill">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto flex-grow-1">
                            @auth
                                <li class="nav-item">
                                    <a class="nav-link link-lighter" href="{{ route('home') }}">{{ __('Visão geral') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link link-lighter" href="{{ route('song.index') }}">{{ __('Músicas') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link link-lighter" href="{{ route('schedule.index') }}">{{ __('Escalas') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link link-lighter" href="
                                        @if(in_array(auth()->user()->type_user, ['admin', 'tecnico']))
                                            {{ route('devocionais.index') }}">{{ __('Devocionais') }}
                                        @else
                                            {{ route('devocionais.public.index') }}">{{ __('Devocionais') }}
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link link-lighter" href="{{ route('songs.suggest') }}">{{ __('Sugestões') }}</a>
                                </li>
                                @if(auth()->user()->type_user == 'admin')
                                    <li class="nav-item">
                                        <a class="nav-link link-lighter" href="{{ route('groups.index') }}">{{ __('Grupos') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link link-lighter" href="{{ route('user.index') }}">{{ __('Usuários') }}</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link link-lighter" href="{{ route('user.edit', Auth::user()->id) }}">{{ __('Meus Dados') }}</a>
                                </li>
                            @endauth
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-md-auto me-auto d-flex flex-shrink-0 my-lg-0 my-md-0 my-4">
                            @guest
                                @else
                                <li class="nav-item">
                                    <div class="d-flex justify-content-center gap-md-0 gap-2">
                                        <div class="flex-grow-1 text-lighter">
                                            Olá, {{ Auth::user()->name }}
                                        </div>
                                        <div class="divider mx-2 text-lighter d-md-block d-none"> | </div>
                                        <div class="flex-shrink-0 d-md-block d-none">
                                            <a class="dropdown-item text-lighter" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                {{ __('Sair') }}
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endguest
                        </ul>

                    </div>
                    <div class="d-md-none d-block w-100 text-center py-3 mt-3">
                        <a class="btn btn-secondary d-block w-100 text-white" href="{{ route('logout') }}">{{ __('Desconectar') }}</a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if (session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
