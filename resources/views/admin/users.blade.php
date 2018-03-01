@extends('layouts.admin')

@push('styles')

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
                { data: 'email',"orderable": false},
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
                        return '<label id="update" class="alert alert-warning" data-id="' + row.id + '" data-name="' +row.name +'" data-address="' + row.address + '">Edit</label>' +
                            '<label id="delete" class="alert alert-danger" data-id="'+ row.id +'">Delete</label>';
                    }
                },
                { data: 'created_at',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return  moment(data).format('ll');
                    }
                },
            ]

        });




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
                            <th>Email</th>
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
