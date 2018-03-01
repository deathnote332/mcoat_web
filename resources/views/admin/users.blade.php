@extends('layouts.admin')

@push('styles')
<style>
    button,select{
        margin: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {

        var base  = $('#base_url').val()

        var supplier = $('#user-list').DataTable({
            ajax: base + '/admin/get-users',
            order: [],
            iDisplayLength: 15,
            bLengthChange: false,
            bInfo: false,
            bDeferRender: true,
            columns: [

                { data: 'name',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return row.first_name+' '+row.last_name;
                    }
                },

                { data: 'user_type',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        var user_type
                        if(data==1){
                            user_type = '<label class="label  label-info">ADMIN</label>'
                        }else if(data==2){
                            user_type = '<label class="label  label-info">IT / SECRETARY</label>'
                        }else{
                            user_type = '<label class="label  label-info">USER</label>'
                        }
                        return user_type;
                    }
                },

                { data: 'status',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        var status
                        if(row.is_remove != 0){
                            if (data == 1) {
                                status = '<label class="label  label-info">APPROVED</label>'
                            } else {
                                status = '<label class="label  label-danger">NEED TO APPROVED</label>'
                            }
                        }else{
                            status = '<label class="label  label-danger">REMOVED</label>'
                        }
                        return status;
                    }
                },
                { data: 'created_at',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return  moment(data).format('ll');
                    }
                },
                { data: 'id',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        var action
                        if(row.is_remove != 0){
                            if (row.status == 0) {
                                action = '<button class="btn  btn-primary" data-id="'+ row.id +'" id="approve">APPROVE</button>'
                            }else{
                                action = '</select>' +
                                    '<select class="form-control" id="change-user-warehouse" data-id="'+ row.id +'">' +
                                    '<option selected disabled>Change warehouse</option>' +
                                    '<option value="1">PASIG WAREHOUSE</option>' +
                                    '<option value="2">ALLIED WAREHOUSE</option>' +
                                    '</select>' +
                                    '<select class="form-control" id="change-user-type" data-id="'+ row.id +'">' +
                                    '<option selected disabled>Change User-type</option>' +
                                    '<option value="1">Admin</option>' +
                                    '<option value="2">IT/Secretary</option>' +
                                    '<option value="3">User</option>' +
                                    '</select>' +

                                    '<button class="btn  btn-warning" data-id="'+ row.id +'" id="disapprove">Disapproved</button>' +
                                    '<button class="btn  btn-danger" data-id="'+ row.id +'" id="remove">REMOVE</button>'
                            }
                        }else{
                            action = '<button class="btn  btn-danger" data-id="'+ row.id +'" id="undo">Undo Remove</button>'
                        }

                        return action
                    }
                }
            ]

        });

        $('body').delegate('#change-user-type','change',function () {
            updateUser($(this).data('id'),3,$(this).val())
        })
        $('body').delegate('#change-user-warehouse','change',function () {
            updateUser($(this).data('id'),6,$(this).val())
        })

        $('body').delegate('#approve','click',function () {
            updateUser($(this).data('id'),1)
        })
        $('body').delegate('#disapprove','click',function () {
            updateUser($(this).data('id'),2)
        })
        $('body').delegate('#remove','click',function () {
            updateUser($(this).data('id'),4)
        })
        $('body').delegate('#undo','click',function () {
            updateUser($(this).data('id'),5)
        })

        function updateUser(id,type,user_type) {
            swal.queue([{
                title: 'Are you sure',
                text: "You want to update this user.",
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
                            url:base+'/admin/update-user' ,
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                id: id,
                                type: type,
                                user_type: user_type,
                                warehouse: user_type,
                            },
                            success: function(data){
                                var user = $('#user-list').DataTable();
                                user.ajax.reload();

                                swal.insertQueueStep(data)
                                resolve()
                            }
                        });
                    })
                }
            }])
        }



        //New error event handling has been added in Datatables v1.10.5
        $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
            console.log(message);
            var user = $('#user-list').DataTable();
            user.ajax.reload();

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

        </h1>

    </section>

    <!-- Main Content -->
    <section id="content">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">USERS LIST</a></li>
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


                    </div>

                    <table id="user-list" class="table table-bordered dt-responsive" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th>Created at</th>
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
