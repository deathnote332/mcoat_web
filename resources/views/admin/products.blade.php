@extends('layouts.admin')

@push('styles')

@endpush

@push('scripts')
<script>
    $(document).ready(function () {

        var base  = $('#base_url').val()

        var product = $('#mcoat-list').DataTable({
            ajax: base + '/getproducts',
            order: [],
            iDisplayLength: 15,
            bLengthChange: false,
            bInfo: false,
            bDeferRender: true,
            // responsive: {
            //     details: {
            //         display: $.fn.dataTable.Responsive.display.childRowImmediate,
            //     }
            // },
            columns: [

                { data: 'brand',"orderable": false },
                { data: 'category',"orderable": false},
                { data: 'code',"orderable": false },
                { data: 'description',"orderable": false },
                { data: 'unit',"orderable": false },
                { data: 'quantity',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                           return  ($('#warehouse').val() == 1 ? row.quantity : row.quantity_1);
                    }
                },
                { data: 'unit_price',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return '₱ '+ $.number(data,2);
                    }
                }
            ],
            "createdRow": function ( row, data, index ) {

                if($('#warehouse').val() == 1){
                    if (data.quantity == 0) {
                        $('td', row).eq(7).find('label#add-to-cart').css({'visibility':'hidden'});
                        $(row).css({
                            'background-color': '#3498db',
                            'color': '#fff'
                        });

                    }else if (data.quantity <= 3 && data.quantity >= 1){
                        $(row).css({
                            'background-color': '#95a5a6',
                            'color': '#fff'
                        });
                    }
                }else{
                    if (data.quantity_1 == 0) {
                        $('td', row).eq(7).find('label#add-to-cart').css({'visibility':'hidden'});
                        $(row).css({
                            'background-color': '#3498db',
                            'color': '#fff'
                        });

                    }else if (data.quantity_1 <= 3 && data.quantity_1 >= 1){
                        $(row).css({
                            'background-color': '#95a5a6',
                            'color': '#fff'
                        });
                    }
                }


            }
        });


        //search
        $('#search').on('input',function () {
            product.search(this.value).draw();
        })


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
    DASHBOARD
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           {{ ($warehouse == 1) ? 'MCOAT WAREHOUSE STOCKS' :  'ALLIED WAREHOUSE STOCKS'}}
        </h1>

    </section>

    <!-- Main Content -->
    <section id="content">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">PRODUCT LIST</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">


                    <div class="input-group margin col-md-6 no-padding-left">
                        <input type="text" class="form-control" id="search" name="search" class="form-control" placeholder="Search..">

                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                        </span>

                        <!-- /btn-group -->
                      </div>
                    <!-- /input-group -->

                    <table id="mcoat-list" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>

                        </tr>
                        </thead>
                    </table>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>


    </section>
@endsection
