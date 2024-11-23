<!DOCTYPE html>
<html lang="<li>{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{$pageMeta->getTitle()}}</title>
    <meta property="og:title" content="{{$pageMeta->getTitle()}}"/>

    <meta name="description" content="{{$pageMeta->getDescription()}}"/>
    <meta property="og:description" content="{{$pageMeta->getDescription()}}"/>

    <!-- Fonts -->

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css?v=0')}}">
    <link rel="stylesheet" href="{{asset('/css/bootstrap-grid.min.css?v=0')}}">
    <link rel="stylesheet" href="{{asset('/css/bootstrap-reboot.min.css?v=0')}}">

    <link rel="stylesheet" href="{{asset('/css/common.css?v=0')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('/css/headerMenu.css?v=0')}}" type="text/css">

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.6.4.min.js')}}"></script>

    <script src="{{ asset('js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('js/popper.js')}}"></script>

    <!-- Modules -->
    <link rel="stylesheet" href="{{ asset('/modules/font-awesome/font-awesome-css.min.css')}}" type="text/css">
    @yield('head')
</head>

<body>

<header class="">
    <h1>Курсы криптовалют, информация о биржах</h1>
</header>

<nav class="navbar   ">
    <ul class="exo-menu main-menu">
        <li><a class="" href="/admin"><i class=""></i> Админка</a></li>
        <li><a class="" href="/"><i class=""></i> Главная</a></li>
        <li><a class="" href="/exchanges"><i class=""></i> Биржи</a></li>
        <li><a class="" href="/coins"><i class=""></i> Валюты</a></li>
        <!--li><a class="active" href="/coins"><i class="fa fa-home"></i> Валюты</a></li-->

        <!-- li class="drop-down"><a href="#"><i class="fa fa-cogs"></i> Flyout</a>

            <ul class="drop-down-ul animated fadeIn">
            <li class="flyout-right"><a href="#">Flyout Right</a>
        <ul class="animated fadeIn">
            <li><a href="#">Mobile</a></li>
            <li><a href="#">Computer</a></li>
            <li><a href="#">Watch</a></li>
        </ul>
        </li>

        <li class="flyout-left"><a href="#">Flyout Left</a>
            <ul class="animated fadeIn">
                <li><a href="#">Mobile</a></li>
                <li><a href="#">Computer</a></li>
                <li><a href="#">Watch</a></li>
            </ul>
        </li>

        <li><a href="#">No Flyout</a></li>
    </li-->

    </ul>

</nav>

<div class="page-content">

    @yield('content')

</div>


<footer class="text-dark">
    <h4>Вся информация о курсах валют и состоянии бирж предоставляется из сторонних открытых источников</h4><!-- эту строку нельзя удалять, чтобы не возникло проблем https://coinmarketcap.com/ru/disclaimer/ -->
    <a class="text-info" href="/tempDisclaimer.html" target="_blank">Отказ от ответственности</a>
    <spoan>(C) КОПИРАЙТ 2009-2023</spoan>
</footer>

@yield('footerScripts')

</body>
</html>
