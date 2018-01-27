@extends('app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{url('vendor/bootstrap/3.3.7/bootstrap.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('vendor/datatable/1.10.16/datatables.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('vendor/sweetalert/sweetalert2.min.css')}}"/>
@endsection


@section('scripts')
    <script  src="{{url('vendor/jquery/3.2.1/jquery.min.js')}}"></script>
    <script  src="{{url('vendor/bootstrap/3.3.7/bootstrap.min.js')}}"></script>
    <script  src="{{url('vendor/datatable/1.10.16/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('vendor/sweetalert/sweetalert2.min.js')}}"></script>
    <script src="{{url('vendor/jquery/3.2.1/jquery.validate.min.js')}}"></script>
@endsection

@section('layout')

    @yield('content')
@endsection