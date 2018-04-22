@extends('layouts.admin')

@push('styles')
<link type="text/css" rel="stylesheet" href="{{url('vendor/morris/morris.css')}}">
<style>
    .small-box:hover .icon {
        font-size: 80px;
    }

    .small-box .icon {
        font-size: 75px;
    }
</style>
@endpush

@push('scripts')
<script src="{{url('vendor/morris/morris.min.js')}}"></script>
<script src="{{url('vendor/raphael/raphael.min.js')}}"></script>
<script>
    $(document).ready(function () {

        var base  = $('#base_url').val()

        var activity  = $('#activity-list').DataTable( {
            ajax: '{{ route('get-logs').'?type=1' }}',
            order: [],
            iDisplayLength: 10,
            bLengthChange: false,
            bInfo: false,
            deferRender: true,
            columns: [
                { data: 'message',"orderable": false },
                { data: 'created_at',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return  moment(data).format('ll');
                    }
                },

            ],
        } );



        var chart = Morris.Bar({
            element: 'bar-chart',
            data:[0,0],
            xkey: ['label'],
            ykeys: ['value'],
            ymax: 100,
            labels: ['Order Percentage'],
            barColors: ['#00a65a', '#f56954'],
            hideHover: 'auto',
            resize: true
        });

        $.ajax({
            url:base + '/sales-report',
            type: 'GET',
            success: function (data){

                chart.setData(data);
            }
        });


    })
</script>
@endpush

@section('title')
    DASHBOARD
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard


        </h1>
    </section>

    <!-- Main Content -->
    <section class="content">

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-6  col-md-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{'₱ '.number_format( \App\Product::select(DB::raw('sum(quantity * unit_price) as total'))->first()->total, 2) }}</h3>

                        <p>MCOAT TOTAL INVENTORY</p>
                    </div>
                    <div class="icon">

                            <i class="fa fa-calculator"></i>

                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6  col-md-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{'₱ '.number_format( \App\Product::select(DB::raw('sum(quantity_1 * unit_price) as total'))->first()->total, 2) }}</h3>

                        <p>ALLIED TOTAL INVENTORY</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-calculator"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->

        </div>

        <!-- BAR CHART -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Sales</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    {{--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div class="chart" id="bar-chart" style="height: 300px;"></div>
            </div>
            <!-- /.box-body -->
        </div>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">USER ACTIVITY LOGS</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">


                    <table id="activity-list" class="table table-bordered dt-responsive" cellspacing="0" width="100%">
                        <thead>
                        <th>Action</th>
                        <th class="width_20">Date</th>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
