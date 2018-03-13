@extends('layouts.admin')

@push('styles')

@endpush

@push('scripts')
<script>
    $(document).ready(function () {

        var base  = $('#base_url').val()

        var supplier = $('#supplier-list').DataTable({
            ajax: base + '/get-supplier',
            order: [],
            iDisplayLength: 15,
            bLengthChange: false,
            bInfo: false,
            bDeferRender: true,
            columns: [

                { data: 'name',"orderable": false },
                { data: 'address',"orderable": false},
                { data: 'created_at',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return  moment(data).format('ll');
                    }
                },

                { data: 'id',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return '<label id="update" class="alert alert-warning" data-id="' + row.id + '" data-name="' +row.name +'" data-address="' + row.address + '">Edit</label>' +
                            '<label id="delete" class="alert alert-danger" data-id="'+ row.id +'">Delete</label>';
                    }
                }
            ]

        });


        //search
        $('#search').on('input',function () {
            supplier.search(this.value).draw();
        })


        $('.add-new').on('click',function () {
            $('#addToCartModal .modal-title').text('Add new supplier')
            $('#addToCartModal').modal('show')
            $('#btn-update').text('Add')
            $('#name').val('')
            $('#address').val('')
        })

        $('body').on('click','#update',function () {
            $('#addToCartModal .modal-title').text('Update')
            $('#addToCartModal').modal('show')
            $('#btn-update').text('Update')
            $('#name').val($(this).data('name'))
            $('#address').val($(this).data('address'))
            $('#supplier_id').val($(this).data('id'))
        })


        $('#btn-update').on('click',function () {
            if($(this).text() == 'Add'){
                addNew()
            }else{
                updateBranch()
            }

        })

        $('body').on('click','#delete',function () {
            deletedItem($(this).data('id'))
        });


        function updateBranch() {

            swal.queue([{
                title: "Are you sure?",
                text: "You want to update this supplier.",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: 'Okay',
                confirmButtonColor: "#DD6B55",
                preConfirm: function () {
                    var data_save = $('#branch').serializeArray();
                    data_save.push({ name : "_token", value: $('meta[name="csrf_token"]').attr('content')})
                    return new Promise(function (resolve) {
                        $.ajax({
                            url:base+'/update-supplier',
                            type:'POST',
                            data: data_save,
                            success: function(data){
                                var supplier = $('#supplier-list').DataTable();
                                supplier.ajax.reload(null, false );

                                $('#addToCartModal').modal('hide');

                                swal.insertQueueStep('Branch updated successfully')
                                resolve()

                            }
                        });

                    })
                }
            }])


        }
        function  deletedItem(id) {

            swal.queue([{
                title: 'Are you sure',
                text: "You want to delete this supplier.",
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
                            url:base+'/delete-supplier',
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                id: id
                            },
                            success: function(data){
                                var supplier = $('#supplier-list').DataTable();
                                supplier.ajax.reload(null,false);

                                swal.insertQueueStep('Branch deleted successfully')
                                resolve()
                            }
                        });
                    })
                }
            }])


        }

        function addNew() {
            swal.queue([{
                title: 'Are you sure',
                text: "You want to add this supplier.",
                type:'warning',
                showLoaderOnConfirm: true,
                showCancelButton: true,
                allowOutsideClick: false,
                closeOnConfirm: false,
                confirmButtonText: 'Okay',
                confirmButtonColor: "#DD6B55",
                preConfirm: function () {

                    return new Promise(function (resolve) {
                        var data_save = $('#branch').serializeArray();
                        data_save.push({ name : "_token", value: $('meta[name="csrf_token"]').attr('content')})
                        $.ajax({
                            url:base+'/add-supplier',
                            type:'POST',
                            data: data_save,
                            success: function(data){
                                var supplier = $('#supplier-list').DataTable();
                                supplier.ajax.reload(null, false );

                                $('#addToCartModal').modal('hide');

                                swal({
                                    title: "",
                                    text: "Branch added successfully",
                                    type:"success"
                                })
                            }
                        });
                    })
                }
            }])



        }


        //New error event handling has been added in Datatables v1.10.5
        $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
            console.log(message);
            var supplier = $('#supplier-list').DataTable();
            supplier.ajax.reload();

        };
    })
</script>
@endpush

@section('title')
    SUPPLIERS
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>

        </h1>

    </section>

    <!-- Main Content -->
    <section id="content">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">SUPPLIER LIST</a></li>
            </ul>
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
                            <div class="btn-add margin no-padding-right ">
                                <button type="button" class="btn btn-primary form-control add-new"> Add new supplier</button>
                            </div>

                        </div>
                    </div>

                    <table id="supplier-list" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Supplier name</th>
                            <th>Address</th>
                            <th>Created at</th>
                            <th>Action</th>

                        </tr>
                        </thead>
                    </table>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>


        <!-- Modal -->
        <div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Update Supplier</h4>
                    </div>
                    <form id="branch">
                        <div class="modal-body">
                            <input type="hidden" id="supplier_id" name="id"  value="">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control" id="name" name="name"/>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input class="form-control" id="address" name="address"/>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btn-update">Update</button>
                        </div>
                    </form>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    </section>
@endsection
