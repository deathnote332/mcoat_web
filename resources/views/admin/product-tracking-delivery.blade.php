@extends('layouts.admin')

@push('styles')

@endpush

@push('scripts')
<script>
    $(document).ready(function () {

        var base  = $('#base_url').val()

        loadData(1)

        $(document).on('change','#warehouse',function(){
            loadData($(this).val())
        })

        function loadData(warehouse_id){
            var product = $('#mcoat-list').DataTable({
                processing: true,
                serverSide: true,
                bInfo: false,
                destroy: true,
                bLengthChange: false,
                bDeferRender: true,
                ajax: base + '/get-product-tracking-delivery/'+ warehouse_id,
                // responsive: {
                //     details: {
                //         display: $.fn.dataTable.Responsive.display.childRowImmediate,
                //     }
                // },
                columns: [
                    { data: 'created_at',name :'po.created_at',"orderable": false,
                        "render": function ( data, type, row, meta ) {
                            return  moment(data).format('ll');
                        }
                    },
                    { data: 'receipt_no',name :'po.receipt_no',"orderable": false,
                        "render": function ( data, type, row, meta ) {
                            var warehouse = ''
                            if(row.type == 1){
                                warehouse = 'PASIG WAREHOUSE'
                            }else{
                                warehouse = 'DAGUPAN WAREHOUSE'
                            }
                            return  warehouse;
                        }
                    },
                    { data: 'branch_name',name :'b.name',"orderable": false},
                    { data: 'receipt_no',name :'po.receipt_no',"orderable": false },
                    { data: 'total',name :'po.total',"orderable": false },
                
                ]
            });

              //search
            $('#search').on('input',function (e) {
                product.search(this.value).draw();
            })

        }


      

        //New error event handling has been added in Datatables v1.10.5
        $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
            console.log(message);
            var product = $('#mcoat-list').DataTable();
            product.ajax.reload();

        };
    })
</script>
@endpush

@section('title')
    PRODUCTS
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Product Tracking
        </h1>

    </section>

    <!-- Main Content -->
    <section class="content">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">PRODUCT LIST</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">


                    <div class="input-group margin col-md-12 no-padding-left">
                        <input type="text" class="form-control" id="search" name="search" class="form-control" placeholder="Search..">

                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                        </span>

                        <div class="col-md-6 col-md-offset-6">
                            <select name="warehouse" id="warehouse" class=" form-control">
                                <option value="1">MCOAT PASIG WAREHOUSE</option>
                                <option value="3">DAGUPAN WAREHOUSE</option>
                            </select>
                        </div>

                        <!-- /btn-group -->
                      </div>
                    <!-- /input-group -->

                    <table id="mcoat-list" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Warehouse</th>
                            <th>Outlet to be delivered</th>
                            <th>Receipt Number</th>
                            <th>Total Amount</th>
                        </tr>
                        </thead>
                    </table>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>


    </section>
@endsection
