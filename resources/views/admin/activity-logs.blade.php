@extends('layouts.admin')

@push('styles')
<style>

</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {

        var base  = $('#base_url').val()

        var activity  = $('#activity-list').DataTable( {
            ajax: '{{ route('get-logs') }}',
            order: [],
            iDisplayLength: 10,
            processing: true,
            serverSide: true,
            bInfo: false,
            bLengthChange: false,
            bDeferRender: true,
            columns: [
                { data: 'message',"orderable": false },
                { data: 'created_at',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return  moment(data).format('ll');
                    }
                },

            ],
        } );

        $('#search').on('input',function () {
            activity.search(this.value).draw();
        })


        //New error event handling has been added in Datatables v1.10.5
        $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
            console.log(message);
            var activity = $('#activity-list').DataTable();
            activity.ajax.reload();

        };
    })
</script>
@endpush

@section('title')
    ACTIVITY LOGS
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
                <li class="active"><a href="#tab_1" data-toggle="tab">USER ACTIVITY LOGS</a></li>
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


                    <table id="activity-list" class="table table-bordered dt-responsive" cellspacing="0" width="100%">
                        <thead>
                        <th>Action</th>
                        <th class="width_20">Date</th>
                        </thead>
                    </table>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>

    </section>
@endsection
