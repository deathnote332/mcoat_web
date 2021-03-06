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
    .btn.btn-primary{
        background: #3c8dbc;
    }
    .edit{color:#3c8dbc}
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {

         //Date picker
         $('#datepicker,#datepicker1').datepicker({
            autoclose: true
        })

        //disable buttons
        if($('.badge').text() == 0){

            $('#print').prop('disabled',true)
            $('#test-print').prop('disabled',true)
            $('.branches').prop('disabled',true)
            $('.branches1').prop('disabled',true)
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
                        return  "<label id='add-to-cart' class='alert alert-info' data-id='"+ row.id +"' data-brand='"+ row.brand+ "' data-category='"+ row.category +"' data-code='"+ row.code +"' data-description='"+ row.description+"' data-quantity='"+ row.quantity +"' data-quantity_1='" + row.quantity_1 + "' data-unit_price='"+ row.unit_price +"' data-unit='"+ row.unit +"'>Add to Cart</label>"
                    }}
            ],


        });

        //search
        $('#search').on('input',function () {
            product.search(this.value).draw();
        })


        var cart = $('#cart-list').DataTable({
            ajax: base + '/product-cart?id=8' + (($('#inventory_id').val() != 0) ?'&receipt_no='+$('#inventory_id').val() : ''),
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
                { data: 'temp_unit',"orderable": false },
                { data: 'temp_qty',"orderable": false},
                { data: 'temp_price',"orderable": false,
                    "render": function ( data, type, row, meta ) {

                        return  '₱ '+ $.number(data* row.temp_qty,2);
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
            var unit_price = $(this).data('unit_price');
            console.log(unit_price);
            $('#addToCartModal').modal('show');
            $('#brand').text(brand)
            $('#category').text(category)
            $('#code').text(code)
            $('#description').text(description)
            $('#unit').text(unit)
            $('#unit_price').text(unit_price)
            $('#current_qty').text(quantity)

            $('#product_id').val(id);

            parseUnit(unit,unit_price)

        })

        $('#btn-addCart').on('click',function () {

            if(parseInt($('#current_qty').text()) < parseInt($('#add-qty').val()) || parseInt($('#add-qty').val()) <= 0) {
                swal({
                    title: "",
                    text: "Invalid quantity",
                    type: "error"
                });
            }else{
                console.log($('#unit-value option:selected').val());
                addToCart($('#product_id').val(),$('#add-qty').val(),$('#unit-value option:selected').text(),$('#unit-value option:selected').val())

            }

        })


        function addToCart(id,qty,unit,price) {

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
                            url:base+'/add-cart'+(($('#inventory_id').val() != 0) ?'?receipt_no='+$('#inventory_id').val() : ''),
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                id: id,
                                qty: qty,
                                unit:unit,
                                price:price,
                                type: 8,
                                

                                
                            },
                            success: function(data){

                                var product = $('#mcoat-list').DataTable();
                                product.ajax.reload(null, false);

                                var cart = $('#cart-list').DataTable();
                                cart.ajax.reload(null, false);

                                $('#addToCartModal').modal('hide');

                                $('.badge').text(data['count'])
                                $('.total-amount').text('₱ ' + data['total'])

                                //enabled buttons
                                $('#print').prop('disabled',false)
                                $('#test-print').prop('disabled',false)
                                $('.branches').prop('disabled',false)
                                $('.branches1').prop('disabled',false)

                                swal.insertQueueStep(data['message'])
                                resolve()
                            }
                        });

                    })
                }
            }])
        }


        function parseUnit(unit,unit_price) {

            $.ajax({
                url:'{{ url('ajax-exchange') }}',
                type:'get',
                data: {
                    _token: $('meta[name="csrf_token"]').attr('content'),
                    unit:unit,
                    unit_price:unit_price
                },
                success: function(data){
                    console.log(data);
                    $('#unit-div p,#unit-div select').remove()
                    $('#unit-div').append(data)
                }
            });
        }


        $('.btn-print .btn').on('click',function () {
            var branch = $('.branches option:selected');
            var branch1 = $('.branches1 option:selected');
            if(branch.val()=="Choose Location" || branch1.val()=="Choose Location"){
                swal({
                    title: "",
                    text: "Please choose location",
                    type: "error"
                });
            }else{
                printReceipt(branch.data('id'))
            }
        })

        function printReceipt(from) {

            swal.queue([{
                title: "Are you sure?",
                text: "You want to print",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                allowOutsideClick: false,
                closeOnConfirm: false,
                confirmButtonText: 'Okay',
                confirmButtonColor: "#DD6B55",
                preConfirm: function () {
                    return new Promise(function (resolve) {

                        $.ajax({
                            url:base+'/save-inventory',
                            type:'POST',
                            data: {
                                _token: $('meta[name="csrf_token"]').attr('content'),
                                branch: from,
                                from: $('#datepicker').val(),
                                to: $('#datepicker1').val(),
                                inventory_id : $('#inventory_id').val()
                            },
                            success: function(data){
                                var cart = $('#cart-list').DataTable();
                                cart.ajax.reload();

                                
                                 $('#datetimepicker').val('');
                                 $('#datetimepicker1').val('');
                                $('.branches').prop('selectedIndex',0);
                                $('#print').prop('disabled',true)
                               
                                $('.branches').prop('disabled',true)
                            
                                swal({
                                    title: "",
                                    text: "Inventroy successfully saved",
                                    type:"success"
                                })
                                $('.badge.badge-danger').text('0')
                                $('.total-amount').text('₱ 0.00')


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
                                type: 8,
                                receipt_no: $('#inventory_id').val()

                            },
                            success: function(data){
                                var product = $('#mcoat-list').DataTable();
                                product.ajax.reload(null, false );

                                var cart = $('#cart-list').DataTable();
                                cart.ajax.reload(null, false );

                                swal.insertQueueStep('Product successfully removed.')
                                resolve()

                                $('.badge').text(data['count'])
                                $('.total-amount').text('₱ ' + data['total'])



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

        };
    })
</script>
@endpush

@section('title')
    STOCK EXCHANGE
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            TOTAL INVENTORY <span class="edit"> {{ ($inventory_id != '') ? '('.$branch_name.' - '.$from_to.')' : '' }} </span> 
        </h1>
        <input type="hidden" id="inventory_id" value="{{ $inventory_id }}">
    </section>

    <!-- Main Content -->
    <section class="content">



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
                                    @if($inventory_id != 0)
                                       <?php $inventory = DB::table('total_inventory')->where('id',$inventory_id)->first(); ?>
                                        {{ (\App\TempProductout::where('type',8)->where('user_id',$inventory->entered_by)->where('rec_no',$inventory_id)->count() != 0) ? \App\TempProductout::where('type',8)->where('user_id',$inventory->entered_by)->where('rec_no',$inventory_id)->count() : 0 }}
                                    @else
                                        {{ (\App\TempProductout::where('type',8)->where('rec_no',0)->where('user_id',Auth::user()->id)->count() != 0) ? \App\TempProductout::where('type',8)->where('rec_no',0)->where('user_id',Auth::user()->id)->count() : 0 }}
                                    @endif
                                    
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
                                    <label>BRANCH</label>
                                    <select class="branches form-control">
                                        <option selected disabled>Choose Location</option>
                                        @foreach(\App\Branches::orderBy('name','asc')->where('status',1)->get() as $key=>$val)
                                            <option value="{{$val->name}}" data-address="{{$val->address}}" data-id="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>From Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="datepicker">
                                    </div>
                                    <!-- /.input group -->

                                </div>
                                <div class="col-md-2">
                                    <label>To Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="datepicker1">
                                    </div>
                                    <!-- /.input group -->
                                    
                                </div>
                                <div class="col-md-2">
                                    <label></label>
                                    <div class="btn-print">
                                        <button type="button" class="form-control btn btn-primary form-control" id="print">Print</button>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <label> </label>
                                    <div class="total-amount form-control">
                                    @if($inventory_id != 0)
                                       <?php $inventory = DB::table('total_inventory')->where('id',$inventory_id)->first(); ?>
                                        {{ number_format(\App\TempProductout::select(DB::raw('sum(temp_product_out.qty * temp_product_out.price) as total'))->where('rec_no',$inventory_id)->first()->total,2) }}
                                    @else
                                        {{number_format(0,2)}}
                                    @endif
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

        @include('modal.products.addcart_inventory')
    </section>
@endsection
