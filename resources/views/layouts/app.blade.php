@extends('app')

@section('styles')
    <link type="text/css" rel="stylesheet" href="{{url('vendor/font-awesome/4.7.0/css/font-awesome.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/bootstrap/3.3.7/css/bootstrap.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/adminLte/AdminLTE.min.css')}}">
@endsection

@section('scripts')
    <script src="{{url('vendor/jquery/3.2.1/jquery.min.js')}}"></script>
    <script src="{{url('vendor/bootstrap/js/bootstrap.bundle.js')}}"></script>
@endsection

@section('layout')
<body class="hold-transition login-page">
    @yield('content')
</body>
@endsection