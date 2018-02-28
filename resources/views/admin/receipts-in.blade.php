@extends('layouts.admin')

@push('styles')

@endpush

@push('scripts')
<script>
    $(document).ready(function () {

        var base  = $('#base_url').val()

        loadReceipts()


        $('#range').on('change',function () {
            loadReceipts($(this).val())
        })


        function loadReceipts(range) {
            var _range = (range == null) ? 'today' : range

            var product = $('#receipt-list').DataTable({

                ajax: {
                    url: base + '/get-receipts-in',
                    type: "POST",
                    data:{
                        _range: _range,
                        _token: $('meta[name="csrf_token"]').attr('content'),
                    }
                },
                destroy: true,
                order: [],
                iDisplayLength: 12,
                bLengthChange: false,
                bDeferRender: true,
                columns: [

                    { data: 'receipt_no',"orderable": false },
                    { data: 'delivered_to',"orderable": false,
                        "render": function ( data, type, row, meta ) {
                            return row.name;
                        }
                    },
                    { data: 'warehouse',"orderable": false,
                        "render": function ( data, type, row, meta ) {
                            return (data == 2) ? 'PASIG WAREHOUSE' : 'ALLIED WAREHOUSE' ;
                        }
                    },


                    { data: 'created_by',"orderable": false,
                        "render": function ( data, type, row, meta ) {
                            return row.first_name+ ' '+row.last_name;
                        }
                    },
                    { data: 'created_at',"orderable": false,
                        "render": function ( data, type, row, meta ) {
                            return  moment(data).format('ll');
                        }
                    },
                    { data: 'id',"orderable": false,
                        "render": function ( data, type, row, meta ) {
                            return  "<a href='" + "receipt-in?id=" + row.id +"' target='_blank'><label id='view-receipt' class='alert alert-success' >View</label></a>"

                        }}

                ],
                "createdRow": function ( row, data, index ) {




                }
            });
        }

        $('#search').on('input',function () {
            var receipt = $('#receipt-list').DataTable();
            receipt.search(this.value).draw();
        })

        //New error event handling has been added in Datatables v1.10.5
        $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
            console.log(message);
            var receipt = $('#receipt-list').DataTable();
            receipt.ajax.reload();

        };

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
            RECEIPTS IN
        </h1>

    </section>

    <!-- Main Content -->
    <section id="content">

        <div class="nav-tabs-custom">

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="form-group">
                        <div class="col-md-6 no-padding-left">
                            <div class="input-group margin  no-padding-left ">
                                <input type="text" class="form-control" id="search" name="search" class="form-control" placeholder="Search..">
                                <span class="input-group-btn">
                                        <button type="button" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                                    </span>
                                <!-- /btn-group -->
                            </div>
                        </div>

                        <div class=" col-md-4 col-md-offset-2 no-padding-right ">

                            <div class="range-selection margin">
                                <select id="range" class="form-control">
                                    <option selected value="today">Today</option>
                                    <option  value="week">Week</option>
                                    <option  value="month">Month</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <table id="receipt-list" class="table table-bordered dt-responsive" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Receipt no.</th>
                            <th>Delivery from</th>
                            <th>To Warehouse</th>
                            <th>Created by</th>
                            <th>Date created</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                    </table>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>


    </section>
@endsection
