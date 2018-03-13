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

        //MCOAT
        $('#category').prop('disabled',true)
        $('#brand').on('change',function () {
            $('#category').prop('disabled',true)
            $.ajax({
                url:base+'/get-category',
                type:'POST',
                data: {
                    _token: $('meta[name="csrf_token"]').attr('content'),
                    brand: $('#brand option:selected').val()
                },
                success: function(data){
                    $('#category').prop('disabled',false)
                    $('#category').html(data)
                }
            });
        })

        $('.reset-specific').on('click',function () {
            if($('#brand option:selected').val() == 'Choose Brand' && $('#category option:selected').val() == 'Choose Category'){
                swal({
                    title: "",
                    text: "Please choose from the field",
                    type:"error"
                })
            }else{
                resetProducts($('#brand option:selected').val(),$('#category option:selected').val(),1,1)

            }

        })

        $('.reset-all').on('click',function () {
            resetProducts('Choose Brand','Choose Category',2,1)
        })





        function resetProducts(brand,category,type,warehouse) {

            var message = (type == 1) ? 'this specific' : 'all this';
            swal.queue([{
                title: 'Are you sure',
                text: "You want to reset "+ message +" product.",
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
                            url:base+'/admin/reset-products',
                            type:'POST',
                            data: {
                                _token : $('meta[name="csrf_token"]').attr('content'),
                                brand: brand,
                                category: category,
                                quantity: (warehouse == 1) ? 'quantity' : 'quantity_1',
                                warehouse: warehouse
                            },
                            success: function(data){

                                var user = $('#reset-list').DataTable();
                                user.ajax.reload(null,false);

                                swal.insertQueueStep(data)
                                resolve()
                                $('#brand').prop('selectedIndex',0);
                                $('#category').prop('selectedIndex',0);
                                $('#brand1').prop('selectedIndex',0);
                                $('#category1').prop('selectedIndex',0);

                                $.each(JSON.parse($('#category-list').val()),function (index,val) {
                                    console.log(val.category)
                                    $('#category').append($('<option value="'+ val.category +'">' + val.category +'</option>'))
                                    $('#category1').append($('<option value="'+ val.category +'">' + val.category +'</option>'))
                                })

                            }
                        });
                    })
                }
            }])

        }

        $('#brand1').on('change',function () {
            $.ajax({
                url:base+'/get-category',
                type:'POST',
                data: {
                    _token: $('meta[name="csrf_token"]').attr('content'),
                    brand: $('#brand1 option:selected').val()
                },
                success: function(data){
                    $('#category1').html(data)
                }
            });
        })

        $('.reset-specific1').on('click',function () {
            if($('#brand1 option:selected').val() == 'Choose Brand' && $('#category1 option:selected').val() == 'Choose Category'){
                swal({
                    title: "",
                    text: "Please choose from the field",
                    type:"error"
                })
            }else{
                resetProducts($('#brand1 option:selected').val(),$('#category1 option:selected').val(),1,2)

            }

        })

        $('.reset-all1').on('click',function () {
            resetProducts('Choose Brand','Choose Category',2,2)
        })


        var reset = $('#reset-list').DataTable({
            ajax: base + '/admin/get-reset',
            order: [],
            iDisplayLength: 5,
            bLengthChange: false,
            bDeferRender:    true,
            columns: [
                { data: 'reset_by',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return row.first_name+ " " + row.last_name;

                    }
                },
                { data: 'message',"orderable": false},
                { data: 'created_at',"orderable": false},
                { data: 'id',"orderable": false,
                    "render": function ( data, type, row, meta ) {
                        var undo = (row._undo == 0) ? '<label id="undo" class="alert alert-info" data-id="'+ row.id +'">Undo</label>' : ''
                        return undo;

                    }
                },

            ]
        });


        $('body').on('click','#undo',function () {
            var id = $(this).data('id');
            swal.queue([{
                title: 'Are you sure',
                text: "You want undo this product quantity.",
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
                            url:base+'/admin/undo-reset',
                            type: 'POST',
                            data:{
                                _token : $('meta[name="csrf_token"]').attr('content'),
                                id: id
                            },
                            success: function (data){
                                var reset = $('#reset-list').DataTable();
                                reset.ajax.reload();
                                swal.insertQueueStep(data)
                                resolve()
                            }
                        });

                    })
                }
            }])


        })

    })
</script>
@endpush

@section('title')
    RESET
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
                        <li class="active"><a href="#tab_1" data-toggle="tab">RESET MCOAT</a></li>

                        <li >
                            <a href="#tab_2" data-toggle="tab">RESET ALLIED</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control" id="brand">
                                        <option selected disabled>Choose Brand</option>
                                        @foreach( \App\Product::select('brand')->where('status',1)->distinct()->orderBy('brand','asc')->get() as $key=>$val)
                                            <option value="{{ $val->brand }}">{{ $val->brand }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="category-list" value="{{ json_encode(\App\Product::select('category')->where('status',1)->distinct()->orderBy('category','asc')->get()) }}">
                                    <select class="form-control" id="category" style="margin: 15px 0">
                                        <option selected disabled>Choose Category</option>
                                        @foreach( \App\Product::select('category')->where('status',1)->distinct()->orderBy('category','asc')->get() as $key=>$val)
                                            <option value="{{ $val->category }}">{{ $val->category }}</option>
                                        @endforeach
                                    </select>

                                    <button class="btn btn-primary form-control reset-specific">Reset</button>
                                </div>
                                <div class="col-md-6">
                                    <button class="form-control btn-primary btn reset-all">RESET ALL MCOAT</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control" id="brand1">
                                        <option selected disabled>Choose Brand</option>
                                        @foreach( \App\Product::select('brand')->where('status',1)->distinct()->orderBy('brand','asc')->get() as $key=>$val)
                                            <option value="{{ $val->brand }}">{{ $val->brand }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="category-list" value="{{ json_encode(\App\Product::select('category')->where('status',1)->distinct()->orderBy('category','asc')->get()) }}">
                                    <select class="form-control" id="category1" style="margin: 15px 0">
                                        <option selected disabled>Choose Category</option>
                                        @foreach( \App\Product::select('category')->where('status',1)->distinct()->orderBy('category','asc')->get() as $key=>$val)
                                            <option value="{{ $val->category }}">{{ $val->category }}</option>
                                        @endforeach
                                    </select>

                                    <button class="btn btn-primary form-control reset-specific1">Reset</button>
                                </div>
                                <div class="col-md-6">
                                    <button class="form-control btn-primary btn reset-all1">RESET ALL ALLIED</button>

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

        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">RESET LIST</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">


                        </span>

                                <!-- /btn-group -->
                            </div>
                            <!-- /input-group -->

                            <table id="reset-list" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Reset By</th>
                                    <th>Message</th>
                                    <th>Date resetted</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>

        @include('modal.products.addtocart')
    </section>
@endsection
