@extends('layouts.admin')

@push('styles')
<style>

    .fa.fa-shopping-cart{
        color: rgb(66, 103, 178);
    }
    .badge{
        position: relative;
        top: -10px;
        color: #fff;
        background: red
    }
    .btn-print-container{
        padding-top: 30px;
    }

    .total-amount{
        background: #000;
        color: #fff;
        text-align: right;
        font-size: 20px;
        padding: 15px;
        line-height: 0.3;
    }

    .btn.btn-primary[disabled]{
        background: #3c8dbc;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {

        //disable buttons
        if($('.badge').text() == 0){

            $('#print').prop('disabled',true)
            $('#invoice_number').prop('disabled',true)
            $('#suppliers').prop('disabled',true)

        }else{
            $('#print').prop('disabled',false)
            $('#invoice_number').prop('disabled',false)
            $('#suppliers').prop('disabled',false)
        }
        var base  = $('#base_url').val()

        var product = $('#mcoat-list').DataTable({
            processing: true,
            serverSide: true,
            bInfo: false,
            bLengthChange: false,
            bDeferRender: true,
            ajax: "{{ route('getproducts') }}",
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
                        return  "<label id='add-to-cart' class='alert alert-info' data-id="+ row.id +" data-brand="+ row.brand+ " data-category="+ row.category +" data-code="+ row.code +" data-description="+ row.description+" data-quantity="+ row.quantity +" data-quantity_1=" + row.quantity_1 + " data-unit_price="+ row.unit_price +" data-unit="+ row.unit +">Add to Cart</label>"
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


        var cart = $('#cart-list').DataTable({
            ajax: base + '/product-cart?id=' + $('#cart').val(),
            order: [],
            iDisplayLength: 10,
            bLengthChange: false,
            bInfo: false,
            bDeferRender: true,
            columns: [

                { data: 'brand',"orderable": false },
                { data: 'category',"orderable": false},
                { data: 'code',"orderable": false },
                { data: 'description',"orderable": false },
                { data: 'unit',"orderable": false },
                { data: 'temp_qty',"orderable": false},
                { data: 'unit_price',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return '₱ '+ $.number(data,2);
                    }
                },
                { data: 'id',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return  "<label id='remove-cart' class='alert alert-danger' data-id="+ row.temp_id +" data-product_id="+ row.id+ " data-qty="+ row.temp_qty +">Remove</label>"

                    }}
            ],

        });

        //search
        $('#search-cart').on('input',function () {
            cart.search(this.value).draw();
        })

        $('body').delegate('#add-to-cart','click',function () {
            $('#add-qty').val('')
            var id = $(this).data('id');
            var brand = $(this).data('brand');
            var category =$(this).data('category');
            var code = $(this).data('code');
            var description = $(this).data('description');
            var quantity = ($('#warehouse').val() == 1 ? $(this).data('quantity') : $(this).data('quantity_1'));
            var unit = $(this).data('unit');

            $('#addToCartModal').modal('show');
            $('#brand').text(brand)
            $('#category').text(category)
            $('#code').text(code)
            $('#description').text(description)
            $('#unit').text(unit)
            $('#current_qty').text(quantity)

            $('#product_id').val(id);

        })

        $('#btn-addCart').on('click',function () {

            if(parseInt($('#add-qty').val()) <= 0 || $('#add-qty').val() == "") {
                swal({
                    title: "",
                    text: "Invalid quantity",
                    type: "error"
                });
            }else{
                addToCart($('#product_id').val(),$('#add-qty').val(),$('#current_qty').text())
            }

        })


        function addToCart(id,qty,current) {

            swal.queue([{
                title: 'Are you sure',
                text: "You want to add this product to cart.",
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
                            url:base+'/add-cart',
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                id: id,
                                qty: qty,
                                current_qty:current,
                                type:$('#cart').val(),
                            },
                            success: function(data){



                                var cart = $('#cart-list').DataTable();
                                cart.ajax.reload();

                                $('#addToCartModal').modal('hide');

                                $('.badge').text(data['count'])
                                $('.total-amount').text('₱ ' + data['total'])

                                //enabled buttons
                                $('#print').prop('disabled',false)
                                $('#invoice_number').prop('disabled',false)
                                $('#suppliers').prop('disabled',false)


                                swal.insertQueueStep(data['message'])
                                resolve()
                            }
                        });

                    })
                }
            }])
        }

        $('#print').on('click',function () {
            var suppliers = $('#suppliers option:selected');
            var warehouse = $('#warehouse option:selected');
            if(suppliers.val()=="0" || $('#invoice_number').val() == ''){
                swal({
                    title: "",
                    text: "Please fill required fields",
                    type: "error"
                });
            }else{
                addToStocks($('#invoice_number').val(),suppliers.data('id'))
            }
        })


        function addToStocks(receipt_no,supplier_id) {
            swal.queue([{
                title: "Are you sure?",
                text: "You want to update the stokcs",
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
                            url:base+'/save-products',
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                receipt_no: receipt_no,
                                supplier_id: supplier_id,
                                type:$('#cart').val()
                            },
                            success: function(data){
                                var product = $('#mcoat-list').DataTable();
                                product.ajax.reload();

                                var cart = $('#cart-list').DataTable();
                                cart.ajax.reload();

                                $('.badge').text(data['count'])
                                $('#print').prop('disabled',true)
                                $('#invoice_number').prop('disabled',true)
                                $('#suppliers').prop('disabled',true)

                                swal.insertQueueStep('Stocks are now updated.')
                                resolve()

                                $('#invoice_number').val('');
                                $('#suppliers').val('0');



                            }
                        });
                    })
                }
            }])

        }






        $('body').on('click','#remove-cart',function () {
            removeToCart($(this).data('id'),$(this).data('product_id'),$(this).data('qty'))
        })

        function removeToCart(id,product_id,qty) {

            swal.queue([{
                title: 'Are you sure',
                text: "You want to remove this product to cart.",
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
                            url:base+'/remove-cart',
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                temp_id: id,
                                product_id: product_id,
                                qty: qty,
                                type: $('#cart').val()

                            },
                            success: function(data){
                                var product = $('#mcoat-list').DataTable();
                                product.ajax.reload();
                                var cart = $('#cart-list').DataTable();
                                cart.ajax.reload();

                                $('.total-amount').text( '₱ '+data['total'])
                                $('.badge').text(data['count'])

                                swal.insertQueueStep('Product successfully removed.')
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
            var product = $('#mcoat-list').DataTable();
            product.ajax.reload();

            var cart = $('#cart-list').DataTable();
            cart.ajax.reload();
        };

    })
</script>
@endpush

@section('title')
    PRODUCT IN
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ ($warehouse == 1) ? 'MCOAT PRODUCT IN' :  'ALLIED PRODUCT IN'}}
        </h1>

    </section>

    <!-- Main Content -->
    <section class="content">

        <input type="hidden" id="warehouse" value="{{ $warehouse }}">
        <input type="hidden" id="cart" value="{{ $cart }}">

        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">PRODUCT LIST</a></li>

                        <li class="pull-right">
                            <a href="#tab_2" data-toggle="tab" class="text-muted">
                                <i class="fa fa-shopping-cart fa-lg"></i>
                                <span class="badge badge-danger">
                                    {{ (\App\TempProductout::where('type',$cart)->where('user_id',1)->count() != 0) ? \App\TempProductout::where('type',$cart)->where('user_id',1)->count() : 0 }}
                                </span>
                            </a>
                        </li>
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
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <div class="input-group margin col-md-6 no-padding-left">
                                <input type="text" class="form-control" id="search" name="search" class="form-control" placeholder="Search..">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                                </span>
                                <!-- /btn-group -->
                            </div>
                            <!-- /input-group -->
                            <table id="cart-list" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
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

                            <div class="row btn-print-container">
                                <div class="col-md-3">
                                    <select class="form-control" id="suppliers">
                                        <option selected disabled value="0">Choose supplier</option>
                                        @foreach(\App\Supplier::orderBy('name','asc')->get() as $key=>$val)
                                            <option value="{{$val->name}}" data-id="{{$val->id}}" data-address="{{$val->address}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="invoice_number" id="invoice_number" placeholder="Invoice number">
                                </div>
                                <div class="col-md-3 col-md-offset-3">
                                    <div class="btn-print">
                                        <button type="button" class="form-control btn btn-primary form-control" id="print">Save</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>

        @include('modal.products.addtocart')
    </section>
@endsection
