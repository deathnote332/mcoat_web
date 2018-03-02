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

        var orders  = $('#orders').DataTable( {
            ajax: base + '/getorders',
            order: [],
            iDisplayLength: 10,
            bLengthChange: false,
            bInfo: false,
            deferRender: true,
            columns: [
                { data: 'id',"orderable": false },
                { data: 'created_at',"orderable": false },
                { data: 'amount',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return 'P '+ data
                    }
                },
                { data: 'status',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        var status;
                        if(data == 0){
                            status = '<span class="label label-info">Awaiting confirmatiion from you</span>'
                        }else if(data == 1){
                            status = '<span class="label label-primary">Order Confirmed</span>'
                        }else if(data == 2){
                            status = '<span class="label label-warning">Preparing for meal now</span>'
                        }else if (data == 3){
                            status = '<span class="label label-warning">For Pickup</span>'
                        }else if( data == 4){
                            status = '<span class="label label-danger">Cancelled order</span>'
                        }else if( data == 5){
                            status = '<span class="label label-danger">Order disapproved</span>'
                        }else if( data == 6){
                            status = '<span class="label label-success">Paid</span>'
                        }
                        return  status;
                    }
                },
                { data: 'id',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        if(row.status == 1 || row.status == 5 || row.status == 2 || row.status == 4 || row.status == 6){
                            return  '<button class="view_order btn btn-primary" data-id="'+row.id+'">View</button>';
                        }
                        if(row.status == 3){
                            return  '<button class="pay_order btn btn-primary" data-id="'+row.id+'" data-total="'+row.total+'">Pay</button>' + '<button class="view_order btn btn-primary" data-id="'+row.id+'">View</button>';
                        }
                        return  '<button class="approve_order btn btn-warning" data-id="'+row.id+'">Confirm</button>' + '<button class="disapproved_order btn btn-danger" data-id="'+row.id+'">Disapprove</button>' + '<button class="view_order btn btn-primary" data-id="'+row.id+'">View</button>';

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
    <section id="content">



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
    </div>
</section>
@endsection
