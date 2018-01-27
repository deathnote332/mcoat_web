@extends('layouts.admin')

@push('styles')

@endpush
@push('scripts')
<script type="text/javascript" >
    $(document).ready(function(){

        var chart = Morris.Bar({
            element: 'morris-bar-chart',
            data:[0,0],
            xkey: ['label'],
            ykeys: ['value'],
            ymax: 100,
            labels: ['Order Percentage'],
            hideHover: 'auto',
            resize: true
        });

    })
</script>
@endpush
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-calculator fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $mcoat_total }}</div>
                            <div>Total Mcoat Inventory</div>
                        </div>
                    </div>
                </div>
                <a href={{ URL('/admin/mcoat') }}>
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-calculator fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $allied_total }}</div>
                            <div>Total Dagupan Inventory</div>
                        </div>
                    </div>
                </div>
                <a href={{ URL('/admin/allied') }}>
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    BRANCH ORDER GRAPH FOR THE MONTH OF <span class="date">{{ date('M') }}</span>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div id="morris-bar-chart"></div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
@endsection