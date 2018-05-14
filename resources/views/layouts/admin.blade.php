@extends('app')

@section('styles')
    <link type="text/css" rel="stylesheet" href="{{url('vendor/font-awesome/4.7.0/css/font-awesome.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/bootstrap/3.3.7/css/bootstrap.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/adminLte/AdminLTE.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/adminLte/_all-skins.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/Ionicons/css/ionicons.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/datatable/dataTables.bootstrap.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/datatable/responsive.bootstrap.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/metisMenu/metisMenu.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/sweetalert/sweetalert2.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/waitMe/waitMe.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/datetimepicker/datetimepicker.min.css')}}">
@endsection

@section('scripts')
    <script src="{{url('vendor/jquery/3.2.1/jquery.min.js')}}"></script>
    <script src="{{url('vendor/jquery/jquery.validate.min.js')}}"></script>
    <script src="{{url('vendor/metisMenu/metisMenu.js')}}"></script>
    <script src="{{url('vendor/bootstrap/3.3.7/js/bootstrap.min.js')}}"></script>
    <script src="{{url('vendor/adminLte/AdminLTE.min.js')}}"></script>
    <script src="{{url('vendor/adminLte/jquery.slimscroll.min.js')}}"></script>
    <script src="{{url('vendor/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('vendor/datatable/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('vendor/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('vendor/datatable/responsive.bootstrap.min.js')}}"></script>
    <script src="{{url('vendor/sweetalert/sweetalert2.min.js')}}"></script>
    <script src="{{url('vendor/jquery/jquery.number.min.js')}}"></script>
    <script src="{{url('vendor/jquery/moment.min.js')}}"></script>
    <script src="{{url('vendor/waitMe/waitMe.min.js')}}"></script>
    <script src="{{url('vendor/datetimepicker/datetimepicker.min.js')}}"></script>
@endsection
@push('styles')
<style>
    body{
        font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
      
    }

    .wrapper{height:100%;position:relative;overflow-x:hidden;overflow-y:hidden}
  
    
    .skin-black .wrapper, .skin-black .main-sidebar, .skin-black .left-side {
        background-color: rgb(66, 103, 178);
    }
    @media (min-width: 768px){
        .sidebar-mini.sidebar-collapse .main-header .logo>.logo-mini {
            font-weight: bold;
            font-size: 12px;
            color: #fff;
        }


    }

    .small-box h3{
        font-size: 30px !important;
    }
    /*table*/
    tr th{background: #337ab7; color: #fff;text-transform: uppercase;}
    div.dataTables_wrapper div.dataTables_filter{display: none}
    .table-search-input{padding-top: 10px;padding-bottom: 10px}
    table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before{top:5px !important;line-height: 16px !important;}
    .width_20{width: 20% !important;}
    @media only screen and (max-width : 992px) {
        .width_20 {
            width: 40% !important;
        }

        #page-wrapper {
            padding: 0px !important;
        }

        td {
            font-size: 11px
        }

        table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td {
            padding-right: 2px
        }

        .page-wrapper{
            padding: 20px !important;
        }

    }

    /*alert*/
    .alert{padding:8px;margin-bottom: 1px;margin-right: 8px;color:#fff;border: none;text-transform: uppercase;font-size: 11px;border-radius: 0}
    .alert-info{background: #31708f !important;}
    .alert-danger{background: #a94442 !important;}
    .alert-success{background: #3c763d !important;}
    .alert-warning{background: #8a6d3b !important;}

    .no-padding-left{
        padding-left: 0;
        margin-left: 0;
    }

    .no-padding-right{
        padding-right: 0;
        margin-right: 0;
    }

    .padding-top-20{
        padding-top:  20px;

    }

    body.skin-black.skin-blue.sidebar-mini {
        padding-right: 0 !important;
    }

    body.skin-black.skin-blue.sidebar-mini.sidebar-collapse {
        padding-right: 0 !important;
    }



    body.skin-black .wrapper{
        background-color: #ecf0f5 !important;
    }
</style>
@endpush
@push('scripts')

@endpush

@section('layout')
<body class="skin-blue hold-transition  sidebar-mini">
<input type="hidden" id="base_url" value="{{ url('') }}">
<div class="wrapper">
    @include('partials.admin.header')
    @include('partials.admin.sidebar')

        <div class="content-wrapper">
            @yield('content')
        </div>
    @include('partials.admin.footer')
</div>
    
</body>
@endsection