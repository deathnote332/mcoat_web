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
                },
                { data: 'id',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return  "<label id='add-to-cart' class='alert alert-info' data-id="+ row.id +" data-brand="+ row.brand+ " data-category="+ row.category +" data-code="+ row.code +" data-description="+ row.description+" data-quantity="+ row.quantity +" data-quantity_1=" + row.quantity_1 + " data-unit_price="+ row.unit_price +" data-unit="+ row.unit +">Update</label>" +
                            "<label id='delete' class='alert alert-danger' data-id=" + row.id +" >Delete</label>";
                }}
            ],
            "createdRow": function ( row, data, index ) {

                if($('#warehouse').val() == 1){
                    if (data.quantity == 0) {

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

        $('#product-modal').on('hidden.bs.modal', function(){

            $("#form-products")[0].reset();
        });


        $('body').delegate('#add-to-cart','click',function () {
            $('#product-modal').modal('show')
            $('.modal-title').text('Update product');
            $('#product-modal').find('#btn-update').text('Update')

            var id = $(this).data('id');
            var brand = $(this).data('brand');
            var category =$(this).data('category');
            var code = $(this).data('code');
            var description = $(this).data('description');
            var unit_price = $(this).data('unit_price');
            var quantity = ($('#warehouse').val() == 1 ? $(this).data('quantity') : $(this).data('quantity_1'));
            var unit = $(this).data('unit');

            $('#addToCartModal').modal('show');
            $('#brand').val(brand)
            $('#category').val(category)
            $('#unit_price').val(unit_price)
            $('#code').val(code)
            $('#description').val(description)
            $('#unit').val(unit)
            $('#quantity').val(quantity)
            $('#product_id').val(id);
        })




        $('#btn-update').on('click',function () {
            var form = $('#form-products');
            if(form.valid()){
                var action = ($(this).text() == 'Add') ? addNewProduct(): updateProduct();
            }
        })

        $('body').on('click','#delete',function () {
            deleteProduct($(this).data('id'))
        });


        $('.add-new').on('click',function () {
            $('#product-modal').modal('show')
            $('.modal-title').text('Add new product');
            $('#product-modal').find('#btn-update').text('Add')
        })

        function updateProduct() {
            swal.queue([{
                title: 'Are you sure',
                text: "You want to update this product.",
                type:'warning',
                showLoaderOnConfirm: true,
                showCancelButton: true,
                allowOutsideClick: false,
                closeOnConfirm: false,
                confirmButtonText: 'Okay',
                confirmButtonColor: "#DD6B55",
                preConfirm: function () {
                    return new Promise(function (resolve) {
                        var data_save = $('#form-products').serializeArray();
                        data_save.push({ name : "_token", value: $('meta[name="csrf_token"]').attr('content')})
                        data_save.push({ name : "type", value: $('#warehouse').val()})
                        $.ajax({
                            url:base+'/update-product',
                            type:'POST',
                            data: data_save,
                            success: function(data){
                                var productout = $('#mcoat-list').DataTable();
                                productout.ajax.reload(null,false);
                                $("#form-products")[0].reset()
                                $('#product-modal').modal('hide');
                                swal.insertQueueStep(data)
                                resolve()
                            }
                        });
                    })
                }
            }])

        }



        function  deleteProduct(id) {
            swal.queue([{
                title: 'Are you sure',
                text: "You want to delete this product.",
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
                            url:base+'/delete-product',
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                id: id,
                            },
                            success: function(data){
                                var productout = $('#mcoat-list').DataTable();
                                productout.ajax.reload(null,false);
                                swal.insertQueueStep(data)
                                resolve()
                            }
                        });
                    })
                }
            }])

        }


        function addNewProduct() {

            swal.queue([{
                title: 'Are you sure',
                text: "You want to add this product.",
                type:'warning',
                showLoaderOnConfirm: true,
                showCancelButton: true,
                allowOutsideClick: false,
                closeOnConfirm: false,
                confirmButtonText: 'Okay',
                confirmButtonColor: "#DD6B55",
                preConfirm: function () {
                    return new Promise(function (resolve) {
                        var data_save = $('#form-products').serializeArray();
                        data_save.push({ name : "_token", value: $('meta[name="csrf_token"]').attr('content')})
                        data_save.push({ name : "type", value: $('#warehouse').val()})
                        $.ajax({
                            url:base+'/add-product',
                            type:'POST',
                            data: data_save,
                            success: function(data){
                                $('#product-modal').modal('hide');
                                var productout = $('#mcoat-list').DataTable();
                                productout.ajax.reload(null,false);
                                $("#form-products")[0].reset()
                                var type = (data =='Product existed') ? 'error': 'success';
                                swal.insertQueueStep(data)
                                resolve()
                            }
                        });
                    })
                }
            }])

        }

        //numeric input
        $('#quantity,#unit_price').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});



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
            MANAGE  {{ ($warehouse == 1) ? 'MCOAT WAREHOUSE' : 'ALLIED WAREHOUSE' }}
        </h1>
    </section>

    <!-- Main Content -->
    <section id="content">
        <div class="">
            <input type="hidden" id="warehouse" value="{{ $warehouse }}">


            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">PRODUCT LIST</a></li>
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
                                    <button type="button" class="btn btn-primary form-control add-new"> Add new product</button>
                                </div>

                            </div>
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
                                <th>Action</th>

                            </tr>
                            </thead>
                        </table>
                    </div>

                </div>
                <!-- /.tab-content -->
            </div>




        </div>
    </section>

    @include('modal.products.manageproduct')
@endsection
