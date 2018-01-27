@extends('layouts.admin')

@push('styles')

@endpush
@push('scripts')
<script type="text/javascript" >
    $(document).ready(function(){
        var BASEURL = $('#baseURL').val();


        var product = $('#mcoat-list').DataTable({
            ajax: BASEURL + '/admin/getproducts',
            order: [],
            iDisplayLength: 10,
            bLengthChange: false,
            bInfo: false,
            bDeferRender: true,
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.childRowImmediate,
                }
            },
            columns: [

                { data: 'brand',"orderable": false },
                { data: 'category',"orderable": false},
                { data: 'code',"orderable": false },
                { data: 'description',"orderable": false },
                { data: 'unit',"orderable": false },
                { data: 'quantity',"orderable": false },
                { data: 'unit_price',"orderable": false }
            ],
            "createdRow": function ( row, data, index ) {
                if($('#user_type').val() != 3){
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
                }
            }
        });
    })
</script>
@endpush
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <table id="mcoat-list" class="table table-bordered dt-responsive" cellspacing="0" width="100%">
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
@endsection