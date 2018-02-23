<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf_token" content="{{csrf_token()}}" />
    <!-- Google Font -->
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <base href="/">
    @stack('metas')

    <title>MCOAT - @yield('title')</title>
    @yield('styles')@stack('styles')
    @yield('scripts')@stack('scripts')
</head>
    @yield('layout')
</html>