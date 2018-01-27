@extends('app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{url('vendor/bootstrap/3.3.7/bootstrap.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('vendor/sbadmin/css/sb-admin-2.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('vendor/fontawesome/font-awesome.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('vendor/morris/morris.css')}}"/>

    {{--globals--}}
    <link rel="stylesheet" type="text/css" href="{{url('css/style.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('css/global.css')}}"/>

    {{--datatables--}}
    <link rel="stylesheet" type="text/css" href="{{url('vendor/dataTables/dataTables.bootstrap4.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('vendor/dataTables/responsive.bootstrap4.min.css')}}"/>


@endsection


@section('scripts')
    <script  src="{{url('vendor/jquery/3.2.1/jquery.min.js')}}"></script>
    <script  src="{{url('vendor/bootstrap/3.3.7/bootstrap.min.js')}}"></script>
    <script  src="{{url('vendor/sbadmin/js/sb-admin-2.min.js')}}"></script>
    <script  src="{{url('vendor/metisMenu/metisMenu.min.js')}}"></script>
    <script  src="{{url('vendor/metisMenu/metisMenu.min.js')}}"></script>
    <script  src="{{url('vendor/morris/morris.min.js')}}"></script>
    <script  src="{{url('vendor/raphael/raphael.min.js')}}"></script>


    {{--datatables--}}
    <script  src="{{url('vendor/dataTables/jquery.dataTables.min.js')}}"></script>
    <script  src="{{url('vendor/dataTables/dataTables.bootstrap4.min.js')}}"></script>
    <script  src="{{url('vendor/dataTables/dataTables.responsive.min.js')}}"></script>
    <script  src="{{url('vendor/dataTables/responsive.bootstrap4.min.js')}}"></script>

@endsection



@section('layout')
    @include('partials.sidebar')
@endsection