@extends('layouts.admin')

@push('styles')
<style>

   .margin-top{
       margin-top: 20px;
   }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {


        var base  = $('#base_url').val()

        $('#stock-brand').on('change',function () {
            $('#category').prop('disabled',true)
            $.ajax({
                url:base+'/get-category',
                type:'POST',
                data: {
                    _token: $('meta[name="csrf_token"]').attr('content'),
                    brand: $('#stock-brand option:selected').val()
                },
                success: function(data){
                    $('#category').prop('disabled',false)

                    $('#category').html(data)
                }
            });
        })


        $('.generate-stocks').on('click',function () {
            if($('#stock-brand option:selected').val() == 'Choose Brand'){
                swal({
                    title: "",
                    text: "Please choose from the field",
                    type:"error"
                })
            }else{
                generatePriceList($('#stock-brand').val(),$('#category option:selected').val())
            }

        })

        function generatePriceList(brand,category) {
            swal({
                title: "Are you sure?",
                text: "You want to generate this pricelist.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: 'Okay',
                closeOnConfirm: false
            }).then(function () {

                var path = '';
                if(category == 'Choose Category') {

                    path= base+'/pricelist?brand='+ brand;
                }else{
                    path= base+'/pricelist?brand='+ brand +'&category=' + category;
                }

                window.open(path);
                swal({
                    title: "",
                    text: "Pricelist successfully generated",
                    type:"success"
                })
            });
        }

        $('#category1').prop('disabled',true)
        $('#stock-brand1').on('change',function () {
            $('#category1').prop('disabled',true)
            $.ajax({
                url:base+'/get-category',
                type:'POST',
                data: {
                    _token: $('meta[name="csrf_token"]').attr('content'),
                    brand: $('#stock-brand1 option:selected').val()
                },
                success: function(data){
                    $('#category1').prop('disabled',false)
                    $('#category1').html(data)
                }
            });
        })


        $('.generate-stocks1').on('click',function () {
             if($('#stock-brand1 option:selected').val() == 'Choose Brand' && $('#category1 option:selected').val() =='Choose Category' && $('#description1 option:selected').val() =='Choose Description' && $('#unit1 option:selected').val() =='Choose Unit' || $('#stocks-type option:selected').val() == 'Choose stocks range' || $('#warehouse option:selected').val() == 'Choose Warehouse') {
                $('#stocks-type').focus();
                swal({
                    title: "",
                    text: "Please choose from the field / Stock range / Warehouse",
                    type: "error"
                })

            }else{
                generateStockReport($('#stock-brand1 option:selected').val(),$('#category1 option:selected').val(),$('#stocks-type option:selected').val());
            }

        })



        function generateStockReport(brand,category,stock) {

            swal({
                title: "Are you sure?",
                text: "You want to generate this stock report.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: 'Okay',
                closeOnConfirm: false
            }).then(function () {

                var path = base + '/stocklist?warehouse=' + $('#warehouse').val() + '&stock=' + $('#stocks-type').val()
                if (brand != 'Choose Brand') {
                    path += '&brand=' + brand
                }
                if (category != 'Choose Category') {
                    path += '&category=' + category
                }
                window.open(path);
                swal({
                    title: "",
                    text: "Stock report successfully generated",
                    type: "success"
                })

            });
        }

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


        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">GENERATE PRICE LIST</a></li>

                        <li >
                            <a href="#tab_2" data-toggle="tab">GENERATE STOCKS REPORT</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="stocks-report1 col-md-6">
                                    <select class="form-control margin-top" id="stock-brand">
                                        <option selected disabled>Choose Brand</option>
                                        @foreach( \App\Product::select('brand')->distinct()->orderBy('brand','asc')->get() as $key=>$val)
                                            <option value="{{ $val->brand }}">{{ $val->brand }}</option>
                                        @endforeach
                                    </select>
                                    <select class="form-control margin-top" id="category" disabled>
                                        <option selected disabled>Choose Category</option>
                                    </select>

                                    <button class="btn btn-primary form-control generate-stocks margin-top">Generate</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="stocks-report">
                                        <select class="form-control margin-top" id="stock-brand1" name="brand">
                                            <option selected disabled>Choose Brand</option>
                                            @foreach( \App\Product::select('brand')->distinct()->orderBy('brand','asc')->get() as $key=>$val)
                                                <option value="{{ $val->brand }}">{{ $val->brand }}</option>
                                            @endforeach
                                        </select>
                                        <select class="form-control margin-top" id="category1" name="category">
                                            <option selected disabled>Choose Category</option>
                                            @foreach( \App\Product::select('category')->distinct()->orderBy('category','asc')->get() as $key=>$val)
                                                <option value="{{ $val->category }}">{{ $val->category }}</option>
                                            @endforeach
                                        </select>

                                        <select class="form-control margin-top" id="stocks-type" name="stocks">
                                            <option selected disabled>Choose stocks range</option>
                                            <option value="0"> OUT OF STOCKS</option>
                                            <option value="1"> STOCKS FROM 1-3</option>
                                            <option value="2"> ALL </option>
                                        </select>
                                        <select class="form-control margin-top" id="warehouse" name="warehouse">
                                            <option selected disabled>Choose Warehouse</option>
                                            <option value="1">MCOAT WAREHOUSE</option>
                                            <option value="2">ALLIED WAREHOUSE</option>

                                        </select>
                                        <button type="button" class="btn btn-primary form-control generate-stocks1 margin-top">Generate</button>
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
