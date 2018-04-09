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

            var product = $('#stock-list').DataTable({

                ajax: {
                    url: base + '/get-purchase',
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

                    { data: 'id',"orderable": false,
                        "render": function ( data, type, row, meta ) {

                            return 'Purchase Order-'+data
                        }},

                    { data: 'supplier_name',"orderable": false,
                        "render": function ( data, type, row, meta ) {

                            return data
                        }
                    },


                    { data: 'branch_name',"orderable": false,
                        "render": function ( data, type, row, meta ) {
                            return data
                        }
                    },
                    { data: 'created_at',"orderable": false,
                        "render": function ( data, type, row, meta ) {
                            return  moment(data).format('ll');
                        }
                    },
                    { data: 'id',"orderable": false,
                        "render": function ( data, type, row, meta ) {
                            return  "<a href='" + "purchase-order?id=" + row.id +"' target='_blank'><label id='view-receipt' class='alert alert-success' >View</label></a>" +
                                "<a href='" + "edit-purchase-receipt?id=" + row.id +"' ><label id='edit-receipt' class='alert alert-warning' >Edit</label></a>" +
                                "<a><label id='delete-receipt' class='alert alert-danger' data-id='"+ row.id +"' data-receipt='" + row.receipt_no +"' data-type='"+ type +"'>Delete</label></a>"


                        }}

                ],
                "createdRow": function ( row, data, index ) {




                }
            });
        }

        $('#search').on('input',function () {
            var receipt = $('#stock-list').DataTable();
            receipt.search(this.value).draw();
        })

        //delete purchase order receipt
        $('body').delegate('#delete-receipt','click',function () {
            var id = $(this).data('id')

            swal.queue([{
                title: 'Are you sure',
                text: "You want to delete this receipt.",
                type:'warning',
                showLoaderOnConfirm: true,
                showCancelButton: true,
                allowOutsideClick: false,
                closeOnConfirm: false,
                confirmButtonText: 'Okay',
                confirmButtonColor: "#DD6B55",
                preConfirm: function () {
                    return new Promise(function (resolve) {
                        $.ajax({
                            url:base+'/delete-purchase-order' ,
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                id: id,
                            },
                            success: function(data){
                                var receipt = $('#receipt-list').DataTable();
                                receipt.ajax.reload(null, false);

                                swal.insertQueueStep('Receipt successfully deleted.')
                                resolve()
                            }
                        });
                    })
                }
            }])
        })


        //New error event handling has been added in Datatables v1.10.5
        $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {

            var receipt = $('#stock-list').DataTable();
            receipt.ajax.reload();

        };

    })
</script>
@endpush

@section('title')
    STOCK EXCHANGE RECEIPTS
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            PURCHASE ORDER RECEIPTS
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



                    <table id="stock-list" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Receipt no.</th>
                            <th>From Supplier</th>
                            <th>DELIVER TO</th>
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
