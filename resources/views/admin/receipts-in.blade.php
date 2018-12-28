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

                           return (data == 2) ? "MCOAT WAREHOUSE" : "DAGUPAN WAREHOUSE"
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
                            var transfer =  "<a href='" + "receipt-in?id=" + row.id +"' target='_blank'><label id='view-receipt' class='alert alert-info' >View</label></a>";
                            return  transfer + "<a><label id='transfer-receipt' class='alert alert-success' data-rec_no="+ row.receipt_no +" data-receipt_id="+ row.id +" >Transfer</label></a>";

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

        $(document).on('click','#transfer-receipt',function () {

            $('#rec-no').text($(this).data('rec_no'))
            $('#branch-modal').modal('show')
            $('#receipt_id').val($(this).data('receipt_id'))
        })

        $(document).on('click','#print-receipt',function(){

            swal.queue([{
                title: 'Are you sure',
                text: "You want to transfer to product out",
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
                            url:base+'/transfer-po',
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                id: $('#receipt_id').val(),
                                branch: $('#branch-select option:selected').val(),
                               
                            },
                            success: function(data){

                                var path = base+'/invoice?id='+ data['receipt'] + '&warehouse='+data['warehouse'];
                                window.open(path);
                                $('#branch-modal').modal('hide')
                                swal.insertQueueStep(data['message'])
                                resolve()
                            }
                        });

                    })
                }
            }])
        })

        //New error event handling has been added in Datatables v1.10.5
        $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {

            var receipt = $('#receipt-list').DataTable();
            receipt.ajax.reload();

        };



    })
</script>
@endpush

@section('title')
    RECEIPTS IN
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            RECEIPTS IN
        </h1>

    </section>

    <!-- Main Content -->
    <section class="content">

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
                            <th width="20%">Action</th>
                        </tr>
                        </thead>

                    </table>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>
       <!-- Modal -->
    <div class="modal fade" id="branch-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <input type="hidden" id="receipt_id">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Transfer to Product Out ( <span id="rec-no"></span> )</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-groupx">
                            <label>Choose Branch</label>
                            <?php  use App\Branches; ?>
                            <select name="" id="branch-select" class="form-control">
                                <option value="" disabled>-- select branch --</option>
                                @foreach(Branches::select('id','name')->get() as $key => $val)
                                <option value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="print-receipt">Print to Product Out</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

    </section>
@endsection
