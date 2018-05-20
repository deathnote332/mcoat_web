@extends('layouts.admin')

@push('styles')


@endpush

@push('scripts')
<script>
    $(document).ready(function () {


        var base  = $('#base_url').val()

       
        var inventory = $('#inventory-list').DataTable({

            ajax: {
                url: base + '/get-inventory',
                type: "POST",
                data:{
                    _token: $('meta[name="csrf_token"]').attr('content'),
                }
            },
            destroy: true,
            order: [],
            iDisplayLength: 12,
            bLengthChange: false,
            bDeferRender: true,
            columns: [

                { data: 'name',name:'branches.name',"orderable": false },
                { data: 'from_date',name:'total_inventory.from_date',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return moment(data).format('LL')
                    }
                },
                { data: 'to_date',name:'total_inventory.to_date',"orderable": false,
                    "render": function ( data, type, row, meta ) {

                       return moment(data).format('LL')
                    }
                },
                { data: 'id',name:'total_inventory.id',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                       return row.first_name+' '+row.last_name;
                    }
                },
                { data: 'created_at',name:'total_inventory.created_at',"orderable": false,
                    "render": function ( data, type, row, meta ) {

                       return moment(data).format('LL')
                    }
                },
                { data: 'id',name:'total_inventory.id',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return  "<a href='" + "print-inventory?id=" + row.id +"' target='_blank'><label id='view-receipt' class='alert alert-success' >View</label></a>" +
                                 "<a href='" + "branch-total-inventory?&id="+ row.id+"' ><label id='edit-receipt' class='alert alert-warning' >Edit</label></a>" +
                                "<a><label id='delete-receipt' class='alert alert-danger' data-id='"+ row.id +"' >Delete</label></a>"

                    }
                },
            ],
        });
      

        $('#search').on('input',function () {
            inventory.search(this.value).draw();
        })



        $('body').delegate('#delete-receipt','click',function () {
            var id = $(this).data('id')

            swal.queue([{
                title: 'Are you sure',
                text: "You want to delete this inventory.",
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
                            url:base+'/delete-inventory' ,
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                id: id,
                            },
                            success: function(data){
                                var receipt = $('#inventory-list').DataTable();
                                receipt.ajax.reload();

                                swal.insertQueueStep('Inventory successfully removed.')
                                resolve()
                            }
                        });
                    })
                }
            }])
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
    RECEIPTS OUT
@endsection

@section('content')
    <!-- Content Header (Page header) -->


    <section class="content-header">
        <h1>
            INVENTORY TOTAL LIST
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

                    <table id="inventory-list" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Branch name</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Entered By</th>
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
