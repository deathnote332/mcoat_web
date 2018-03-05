@extends('layouts.admin')

@push('styles')

<link type="text/css" rel="stylesheet" href="{{url('vendor/jquery/jquery-steps.css')}}">
<style>
    .info-box-icon{
        float: none;
        width: 100% !important;
        height: 40px;
        line-height: 2;
        font-size: 20px;
    }
    .info-box-content{
        margin-left: 0;
        font-size: 14px;
    }
    .margin-bottom{
        margin-bottom: 30px;
    }
    .padding-icon{
        padding: 0 50px;
        cursor: pointer;
    }

    /*paginate*/
    a.page.current{
        z-index: 3;
        color: #fff;
        cursor: default;
        background-color: #337ab7;
        border-color: #337ab7;
    }

    .easyPaginateNav{
        text-align: right;
        padding: 20px 20px;
        width: 100% !important;
    }
    a.page,a.first,a.prev,a.next,a.last {
        padding: 8px 12px;
        background-color: #fff;
        border: 1px solid #ddd;
        /* margin: 1px; */

        color: #337ab7;
    }

    a.prev{
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    a.next{
        margin-right: 0;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }

    a.last,a.first{
        margin-right: 0;

        display: none;
    }


    span.ion.ion-edit.pull-right {
        line-height: 2.5;
        font-size: 13px;
        padding: 4px 10px;
        color: #fff;
        background: darkcyan;
    }
</style>
@endpush

@push('scripts')

<script src="{{url('vendor/jquery/jquery.easyPaginate.js')}}"></script>
<script src="{{url('vendor/jquery/jquery-steps.min.js')}}"></script>
<script>
    $(document).ready(function () {

        var base  = $('#base_url').val()

        $('#paginate').easyPaginate({
            paginateElement: 'div.date-sales',
            elementsPerPage: 12,
        });


        $('body').on('click','#edit-modal',function () {

            $('#daily-edit-sale')[0].reset()
            var _date = $(this).data('year') +'-'+$(this).data('month')+'-'+$(this).data('day')
            $('#_date').text($(this).data('_date'))
            $('#_branch').text($('#branch').val())
            $('#_address').text($('#address').val())

            parseData($(this).data('data'))


            $('#edit-day-modal').modal('show')

            $("#steps").steps().destroy


        })


        $('#back').on('click',function () {
            var BASEURL = $('#baseURL').val();

            if($('#user_type').val() == 1){

                window.location = BASEURL + '/admin/branch/' + $('#branch_id').val()
            }else{
                window.location = BASEURL + '/user/sales'
            }
        })

    });



    function parseData(json){

            if(json != ''){
                $.each(json,function (name,value) {
                    $('[name="'+name+'"]').val(value)
                })


                var w_total = 0
                $('#step1').find('.margin_top').remove()
                $.each(json['with_receipt'],function (index,value){
                    var vals = 0
                    if(value['rec_amount'] == "null" || value['rec_amount'] == null){
                        vals = 0
                    }else{
                        vals =parseFloat(value['rec_amount'])
                    }

                    w_total = w_total + vals
                    $('#step1').append('<div class="row margin_top">' +
                        '<div class="col-md-1 ">' +
                        '<div class="number-ctr">'+ (index + 1) +'.</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                        '<input type="text" class="form-control" name="with_receipt['+ index + '][rec_no]" placeholder="Receipt no." value="'+ parseFloat(value['rec_no']) +'"></div>' +
                        '<div class="col-md-5">' +
                        '<input type="text" id="w-amount" class="form-control" name="with_receipt['+ index +'][rec_amount]" placeholder="Amount" value="' + vals +'"></div>' +
                        '</div>');

                    $('#step1').find('.total').text('P '+w_total)
                })



            }else {


            }


            //numeric input
            $('#taken-amount,#return-amount,#expense-amount,#credit-amount,#w-amount,#wo-amount').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

}

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
            <h1>{{ \App\Branches::find($branch)->name }} - {{date('F', mktime(0, 0, 0, $month, 1))}} {{$year}}  Sales</h1>
            <input type="hidden" id="branch" value="{{ \App\Branches::find($branch)->name }}">
            <input type="hidden" id="address" value="{{ \App\Branches::find($branch)->address }}">
        </div>
    </div>

    <input type="hidden" id="user_type" value="{{ Auth::user()->user_type }}">
    <input type="hidden" id="branch_id" value="{{ $branch }}">

    <div class="row" id="paginate">
        @for($i = 1; $i<=$end_date;$i++)
            <?php $result = \App\Http\Controllers\SaleController::getSalePerDay($i,$month,$year,$branch);
                 $total = json_decode($result,TRUE);
                $w_receipt = $total['with_receipt_total'];
                $wo_receipt = $total['with_out_receipt_total'];
                $credit = $total['credit_total'];
                $expense = $total['expense_total'];
                $cash = $total['amount_total'];
                $_total = ($w_receipt + $wo_receipt + $credit ) - $expense;
                $loss=0;
                $excess=0;

            if($_total > $cash){
                $loss = $cash - $_total;
            }else{

                $excess = $cash- $_total ;
            }

            ?>

                <div class="col-md-4 date-sales">
            <div class="info-box">
                <span class="info-box-icon bg-aqua">{{date('F', mktime(0, 0, 0, $month, 1))}} {{$i}}, {{$year}} <span class="ion ion-edit pull-right" id="edit-modal" data-data="{{ json_decode($result,TRUE)['data'] }}" data-_date="{{json_decode($result,TRUE)['date']}}" data-year="{{ $year }}" data-month="{{ $month }}" data-day="{{ $i }}"> EDIT</span></span>

                <div class="info-box-content">
                <table width="100%" >
                    <tbody>
                    <tr >
                        <td>WITH RECEIPT</td>
                        <td>{{ 'P '.number_format($w_receipt,2) }} (+)</td>
                    </tr>
                    <tr>
                        <td>WITHOUT RECEIPT</td>
                        <td>{{ 'P '.number_format($wo_receipt,2) }} (+)</td>
                    </tr>
                    <tr>
                        <td>CREDIT COLLECTION</td>
                        <td>{{ 'P '.number_format($credit,2) }} (+)</td>
                    </tr>
                    <tr>
                        <td>EXPENSES</td>
                        <td>{{ 'P '.number_format($expense,2) }} (-)</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td>{{ 'P '.number_format($_total,2) }}</td>
                    </tr>
                    <tr>
                        <td>CASH COMPUTATION</td>
                        <td>{{ 'P '.number_format($cash,2) }}</td>
                    </tr>
                    <tr>
                        <td>EXCESS</td>
                        <td>
                            @if($excess == 0)
                                {{ 'P '.number_format($excess,2) }}
                            @else
                                <b style="color: blue">{{ 'P '.number_format($excess,2) }}</b>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>LOSS</td>
                        <td>
                            @if($loss == 0)
                                {{ 'P '.number_format($loss,2) }}
                            @else
                                <b style="color: red">{{ 'P '.number_format($loss,2) }}</b>
                            @endif
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endfor
</div>
    @include('modal.editsale')
</section>
@endsection
