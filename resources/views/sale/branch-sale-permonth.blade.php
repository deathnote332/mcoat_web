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

    /*MODAL STYLE*/
    .margin_top{
        margin-top: 8px;
    }
    .margin_bottom{
        margin-bottom: 8px;
        padding-right: 0;
    }

    .number-ctr{
        padding: 10px;
        font-weight: 600;
    }


    /*steps*/

    #steps{
        padding-bottom: 50px;
    }

    .step-steps{
        width: 30%;
        float: left;
        display: block !important;
    }

    .step-app > .step-content{
        width: 65%;
        float: left;
        border: none;
    }

    .step-footer{
        position: absolute;
        bottom: 0;
        right:30px;
    }



    .step-app > .step-steps > li > a{
        padding: 15px;
        font-size: 14px;
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
            console.log($(this).data('_date'))
            $('#_branch').text($('#branch').val())
            $('#_address').text($('#address').val())

            parseData($(this).data('data'))


            $('#edit-day-modal').modal('show')




        })


        $('#back').on('click',function () {
            var BASEURL = $('#baseURL').val();

            if($('#user_type').val() == 1){

                window.location = BASEURL + '/admin/branch/' + $('#branch_id').val()
            }else{
                window.location = BASEURL + '/user/sales'
            }
        })



        $("#steps").steps({
            onInit: function () {

            },
            onFinish: function () {
                saveDailyAdmin($('#_date').text())

            }
        });

        keyPress()

        addButtons()





        function saveDailyAdmin(day) {

            swal.queue([{
                title: 'Are you sure',
                text: "You want to save this record.",
                type:'warning',
                showLoaderOnConfirm: true,
                showCancelButton: true,
                allowOutsideClick: false,
                closeOnConfirm: false,
                confirmButtonText: 'Okay',
                confirmButtonColor: "#DD6B55",
                preConfirm: function () {
                    return new Promise(function (resolve) {
                        var myform = $('#daily-edit-sale');
                        var disabled = myform.find(':input:disabled').removeAttr('disabled');
                        var data_save = $('#daily-edit-sale').serializeArray();
                        data_save.push({ name : "_token", value: $('meta[name="csrf_token"]').attr('content')})
                        data_save.push({ name : "_date", value: day })
                        data_save.push({ name : "branch_id", value: $('#branch_id').val() })
                        data_save.push({ name : "is_check", value: $('#is_check').val() })
                        $.ajax({
                            url:base+"/edit-daily",
                            type:'POST',
                            data: data_save,
                            success: function(data){
                                swal.insertQueueStep(data)
                                resolve()
                                $('#edit-day-modal').modal('hide')
                                location.reload()
                                disabled.attr('disabled','disabled');
                            }
                        });
                    })
                }
            }])
        }




    });



    function parseData(json){

        if(json != ''){
            $.each(json,function (name,value) {

                $('#'+name).val(value)
                console.log(name + ' ' + value)
                $('[name="'+name+'"]').val(value)
            })



            step1(json)
            step2(json)
            step3(json)
            step4(json)
            step5(json)
            step7(json)
            step8(json)
            cash_total(json)
        }else{
            noData()
            noCashBreakdown(false)
        }


        if($('#is_check').val() == 1){
            noCashBreakdown(true)
            $('#is_check').prop('checked',true)
        }else if($('#is_check').val() == 2 || $('#is_check').val() == 'null'){
            noCashBreakdown(false)
            $('#is_check').prop('checked',false)
        }




        $('#is_check').on('change',function () {

            $(this).val((this.checked == true) ? 1 : 2)
            noCashBreakdown(this.checked)
        })
        //numeric input
        $('#taken-amount,#return-amount,#expense-amount,#credit-amount,#w-amount,#wo-amount,#deposit-amount').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
    }

    function step1(json) {
        var w_total = 0
        $('#step1').find('.margin_top').remove()
        $.each(json['with_receipt'],function (index,value){
            var amount = (value['rec_amount'] == "null" || value['rec_amount'] == null) ? '' : value['rec_amount']
            var rec_no = (value['rec_no'] == "null" || value['rec_no'] == null) ? '' : value['rec_no']
            w_total = w_total + parseFloat((amount != '') ? amount : 0)
            $('#step1').append('<div class="row margin_top">' +
                '<div class="col-md-1 ">' +
                '<div class="number-ctr">'+ (index + 1) +'.</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<input type="text" class="form-control" name="with_receipt['+ index + '][rec_no]" placeholder="Receipt no." value="'+ rec_no +'"></div>' +
                '<div class="col-md-5">' +
                '<input type="text" id="w-amount" class="form-control" name="with_receipt['+ index +'][rec_amount]" placeholder="Amount" value="' + amount +'"></div>' +
                '</div>');

            $('#step1').find('.total').text('P '+w_total)
        })

        $('#step1 div.col-md-6 input:nth-child(1)').on('input',function () {
            $.each($('#step1 div.col-md-6 input'),function (index,value){
                if(index != 0){
                    $(this).val(parseInt(($('#step1 div.col-md-6 input:nth-child(1)').val() == '') ? 0 : $('#step1 div.col-md-6 input:nth-child(1)').val()) + index)
                }
            })
        })
        $.each($('#step1 div.col-md-6 input'),function (index,value){
            if(index != 0){
                $(this).val(parseInt(($('#step1 div.col-md-6 input:nth-child(1)').val() == '') ? 0 : $('#step1 div.col-md-6 input:nth-child(1)').val()) + index)
            }
        })

        //BUTTONS
        $('#step1 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-w-rec">Add more</button></div>')

    }

    function step2(json) {
        //STEP 2
        var wo_total = 0
        $('#step2').find('.margin_top').remove()
        $.each(json['without_receipt'],function (index,value){
            var amount = (value['amount'] == "null" || value['amount'] == null) ? '' : value['amount']
            wo_total = wo_total + parseFloat((amount != '') ? amount : 0)
            $('#step2').append('<div class="row margin_top">' +
                '<div class="col-md-1">' +
                '<div class="number-ctr">'+ (index + 1) +'.</div></div>' +
                '<div class="col-md-11">' +
                '<input type="text" id="wo-amount" class="form-control" name="without_receipt['+ index+'][amount]" placeholder="Amount" value="'+ amount +'"></div>' +
                '</div>')
            $('#step2').find('.total').text('P '+wo_total)

        })
        $('#step2 div.row.margin_top:last-child div:nth-child(2)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-wo-rec">Add more</button></div>')

    }

    function step3(json) {
        var credit_total = 0
        $('#step3').find('.margin_top').remove()
        $.each(json['credit'],function (index,value){

            var company = (value['company'] == "null" || value['company'] == null) ? '' : value['company']
            var bank_name = (value['bank_name'] == "null" || value['bank_name'] == null) ? '' : value['bank_name']
            var bank = (value['bank'] == "null" || value['bank'] == null) ? '' : value['bank']
            var amount = (value['amount'] == "null" || value['amount'] == null) ? '' : value['amount']

            credit_total = credit_total + parseFloat((amount != '') ? amount : 0)

            $('#step3').append('<div class="row margin_top">' +
                '<div class="col-md-1">' +
                '<div class="number-ctr">' + ( index + 1) +'.</div></div>' +
                '<div class="col-md-5"><input type="text" class="form-control" name="credit['+ index +'][company]" placeholder="Company" value="'+ company +'"></div>' +
                '<div class="col-md-6"><input type="text" class="form-control" name="credit['+ index +'][bank_name]" placeholder="Bank Name" value="'+ bank_name +'"></div>' +
                '<div class="col-md-5 margin_top"><input type="text" class="form-control" name="credit['+ index +'][bank]" placeholder="Bank Number" value="'+ bank +'"></div>' +
                '<div class="col-md-6 margin_top"><input type="text" id="credit-amount" class="form-control" name="credit['+ index +'][amount]" placeholder="Amount" value="'+ amount +'"></div>' +
                '</div>')
            $('#step3').find('.total').text('P '+credit_total)
        })
        $('#step3 div.row.margin_top:last-child div:nth-child(5)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-credit">Add more</button></div>')
    }

    function step4(json) {

        var expense = 0
        $('#step4').find('.margin_top').remove()
        $.each(json['expense'],function (index,value){
            var details = (value['details'] == "null" || value['details'] == null) ? '' : value['details']
            var amount = (value['amount'] == "null" || value['amount'] == null) ? '' : value['amount']

            expense = expense +  parseFloat((amount != '') ? amount : 0)

            $('#step4').append('<div class="row margin_top">' +
                '<div class="col-md-1 "><div class="number-ctr">' + ( index + 1) +'.</div></div>' +
                '<div class="col-md-6"><input type="text" class="form-control" name="expense['+ index+'][details]" placeholder="Details" value="'+ details +'"></div>' +
                '<div class="col-md-5"><input type="text" id="expense-amount" class="form-control" name="expense['+ index +'][amount]" placeholder="Amount" value="' + amount +'"></div>' +
                '</div>')
            $('#step4').find('.total').text('P '+expense)
        })
        $('#step4 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-expense">Add more</button></div>')
    }

    function step5(json) {

        var _return = 0
        $('#step5').find('.margin_top').remove()
        $.each(json['return'],function (index,value){
            var name = (value['name'] == "null" || value['name'] == null) ? '' : value['name']
            var amount = (value['amount'] == "null" || value['amount'] == null) ? '' : value['amount']

            _return = _return +  parseFloat((amount != '') ? amount : 0)

            $('#step5').append('<div class="row margin_top">' +
                '<div class="col-md-1 "><div class="number-ctr">' + ( index + 1) +'.</div></div>' +
                '<div class="col-md-6"><input type="text" class="form-control" name="return['+ index+'][name]" placeholder="Name" value="'+ name  +'"></div>' +
                '<div class="col-md-5"><input type="text" id="return-amount" class="form-control" name="return['+ index +'][amount]" placeholder="Amount" value="' + amount +'"></div>' +
                '</div>')

            $('#step5').find('.total').text('P '+_return)
        })
        $('#step5 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-return">Add more</button></div>')
    }

    function step7(json) {

        var taken = 0
        $('#step7').find('.margin_top').remove()
        $.each(json['taken'],function (index,value){
            var name = (value['name'] == "null" || value['name'] == null) ? '' : value['name']
            var amount = (value['amount'] == "null" || value['amount'] == null) ? '' : value['amount']

            taken = taken + parseFloat((amount != '') ? amount : 0)

            $('#step7').append('<div class="row margin_top">' +
                '<div class="col-md-1"><div class="number-ctr">'+ (index + 1) +'.</div></div>' +
                '<div class="col-md-6"><input type="text" class="form-control" name="taken['+ index +'][name]" placeholder="Name" value="'+ name +'"></div>' +
                '<div class="col-md-5"><input type="text" id="taken-amount" class="form-control" name="taken['+ index +'][amount]" placeholder="Amount" value="'+ amount +'"></div>' +
                '</div>')

            $('#step7').find('.total').text('P '+ taken)
        })
        $('#step7 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-taken">Add more</button></div>')
    }

    function step8(json) {
        var deposit = 0
        $('#step8').find('.margin_top').remove()
        $.each(json['deposit'],function (index,value){
            var bank_name = (value['bank_name'] == "null" || value['bank_name'] == null) ? '' : value['bank_name']
            var bank_number = (value['bank_number'] == "null" || value['bank_number'] == null) ? '' : value['bank_number']
            var amount = (value['amount'] == "null" || value['amount'] == null) ? '' : value['amount']

            deposit = deposit + parseFloat((amount != '') ? amount : 0)
            $('#step8').append('<div class="row margin_top">' +
                '<div class="col-md-1"><div class="number-ctr">'+ (index + 1) +'.</div></div>' +
                '<div class="col-md-4"><input type="text" class="form-control" name="deposit['+ index +'][bank_name]" placeholder="Bank Name" value="'+ bank_name +'"></div>' +
                '<div class="col-md-4"><input type="text" class="form-control" name="deposit['+ index +'][bank_number]" placeholder="Bank Number" value="'+ bank_number +'"></div>' +
                '<div class="col-md-3"><input type="text" id="deposit-amount" class="form-control" name="deposit['+ index +'][amount]" placeholder="Amount" value="'+ amount +'"></div>' +
                '</div>')
            $('#step8').find('.total').text('P '+deposit)
        })
        $('#step8 div.row.margin_top:last-child div:nth-child(4)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-deposit">Add more</button></div>')

    }

    function cash_total(json) {
        //total cash
        var _1000 = json['amount_1000'] * 1000
        var _500 = json['amount_500'] * 500
        var _100 = json['amount_100'] * 100
        var _50 = json['amount_50'] * 50
        var _20 = json['amount_20'] * 20
        var _coins = json['amount_coins']
        var cash = parseFloat(_1000) + parseFloat(_500) + parseFloat(_100) +parseFloat( _50) + parseFloat(_20) + parseFloat(_coins)
        $('#step6').find('.total').text('P '+ cash)
    }

    function keyPress() {
        //keypress

        $('body').delegate('#step1 div div:nth-child(3) input','input',function () {
            var w_total = 0
            $.each($('#step1 div div:nth-child(3) input'),function (index,value){
                w_total = w_total + parseFloat(($(this).val() == '') ? 0 : $(this).val())
                $('#step1').find('.total').text('P '+w_total)
            })
        })

        $('body').delegate('#step2 div div:nth-child(2) input','input',function () {
            var w_total = 0
            $.each($('#step2 div div:nth-child(2) input'),function (index,value){
                w_total = w_total + parseFloat(($(this).val() == '') ? 0 : $(this).val())
                $('#step2').find('.total').text('P '+w_total)
            })
        })

        $('body').delegate('#step3 div div:nth-child(5) input','input',function () {
            var w_total = 0
            $.each($('#step3 div div:nth-child(5) input'),function (index,value){
                w_total = w_total + parseFloat(($(this).val() == '') ? 0 : $(this).val())
                $('#step3').find('.total').text('P '+w_total)
            })
        })


        $('body').delegate('#step4 div div:nth-child(3) input','input',function () {
            var w_total = 0
            $.each($('#step4 div div:nth-child(3) input'),function (index,value){
                w_total = w_total + parseFloat(($(this).val() == '') ? 0 : $(this).val())
                $('#step4').find('.total').text('P '+w_total)
            })
        })


        $('body').delegate('#step5 div div:nth-child(3) input','input',function () {
            var w_total = 0
            $.each($('#step5 div div:nth-child(3) input'),function (index,value){
                w_total = w_total + parseFloat(($(this).val() == '') ? 0 : $(this).val())
                $('#step5').find('.total').text('P '+w_total)
            })
        })

        $('body').delegate('#step7 div div:nth-child(3) input','input',function () {
            var w_total = 0
            $.each($('#step7 div div:nth-child(3) input'),function (index,value){
                w_total = w_total + parseFloat(($(this).val() == '') ? 0 : $(this).val())
                $('#step7').find('.total').text('P '+w_total)
            })
        })

        //cash
        $('[name="amount_1000"]').on('input',function () {
            var _1000 = parseFloat(($(this).val() == '') ? 0 : $(this).val() * 1000)
            var _500 = parseFloat(($('[name="amount_500"]').val() == '') ? 0 : $('[name="amount_500"]').val() * 500)
            var _100 =  parseFloat(($('[name="amount_100"]').val() == '') ? 0 :$('[name="amount_100"]').val() * 100)
            var _50 =  parseFloat(($('[name="amount_50"]').val() == '') ? 0 :$('[name="amount_50"]').val() * 50)
            var _20 =  parseFloat(($('[name="amount_20"]').val() == '') ? 0 :$('[name="amount_20"]').val() * 20)
            var _coins =  parseFloat(($('[name="amount_coins"]').val() == '') ? 0 :$('[name="amount_coins"]').val())
            var cash = parseFloat(_1000) + parseFloat(_500) + parseFloat(_100) +parseFloat( _50) + parseFloat(_20) + parseFloat(_coins)
            $('#step6').find('.total').text('P '+ cash)
        })
        $('[name="amount_500"]').on('input',function () {
            var _1000 = parseFloat(($('[name="amount_1000"]').val() == '') ? 0 : $('[name="amount_1000"]').val() * 1000)
            var _500 = parseFloat(($(this).val() == '') ? 0 : $(this).val() * 500)
            var _100 =  parseFloat(($('[name="amount_100"]').val() == '') ? 0 :$('[name="amount_100"]').val() * 100)
            var _50 =  parseFloat(($('[name="amount_50"]').val() == '') ? 0 :$('[name="amount_50"]').val() * 50)
            var _20 =  parseFloat(($('[name="amount_20"]').val() == '') ? 0 :$('[name="amount_20"]').val() * 20)
            var _coins =  parseFloat(($('[name="amount_coins"]').val() == '') ? 0 :$('[name="amount_coins"]').val())
            var cash = parseFloat(_1000) + parseFloat(_500) + parseFloat(_100) +parseFloat( _50) + parseFloat(_20) + parseFloat(_coins)
            $('#step6').find('.total').text('P '+ cash)
        })
        $('[name="amount_100"]').on('input',function () {
            var _1000 = parseFloat(($('[name="amount_1000"]').val() == '') ? 0 : $('[name="amount_1000"]').val() * 1000)
            var _500 = parseFloat(($('[name="amount_500"]').val() == '') ? 0 : $('[name="amount_500"]').val() * 500)
            var _100 = parseFloat(($(this).val() == '') ? 0 : $(this).val() * 100)
            var _50 =  parseFloat(($('[name="amount_50"]').val() == '') ? 0 :$('[name="amount_50"]').val() * 50)
            var _20 =  parseFloat(($('[name="amount_20"]').val() == '') ? 0 :$('[name="amount_20"]').val() * 20)
            var _coins =  parseFloat(($('[name="amount_coins"]').val() == '') ? 0 :$('[name="amount_coins"]').val())
            var cash = parseFloat(_1000) + parseFloat(_500) + parseFloat(_100) +parseFloat( _50) + parseFloat(_20) + parseFloat(_coins)
            $('#step6').find('.total').text('P '+ cash)
        })
        $('[name="amount_50"]').on('input',function () {
            var _1000 = parseFloat(($('[name="amount_1000"]').val() == '') ? 0 : $('[name="amount_1000"]').val() * 1000)
            var _500 = parseFloat(($('[name="amount_500"]').val() == '') ? 0 : $('[name="amount_500"]').val() * 500)
            var _100 =  parseFloat(($('[name="amount_100"]').val() == '') ? 0 :$('[name="amount_100"]').val() * 100)
            var _50 = parseFloat(($(this).val() == '') ? 0 : $(this).val() * 50)
            var _20 =  parseFloat(($('[name="amount_20"]').val() == '') ? 0 :$('[name="amount_20"]').val() * 20)
            var _coins =  parseFloat(($('[name="amount_coins"]').val() == '') ? 0 :$('[name="amount_coins"]').val())
            var cash = parseFloat(_1000) + parseFloat(_500) + parseFloat(_100) +parseFloat( _50) + parseFloat(_20) + parseFloat(_coins)
            $('#step6').find('.total').text('P '+ cash)
        })

        $('[name="amount_20"]').on('input',function () {
            var _1000 = parseFloat(($('[name="amount_1000"]').val() == '') ? 0 : $('[name="amount_1000"]').val() * 1000)
            var _500 = parseFloat(($('[name="amount_500"]').val() == '') ? 0 : $('[name="amount_500"]').val() * 500)
            var _100 =  parseFloat(($('[name="amount_100"]').val() == '') ? 0 :$('[name="amount_100"]').val() * 100)
            var _50 =  parseFloat(($('[name="amount_50"]').val() == '') ? 0 :$('[name="amount_50"]').val() * 50)
            var _20 = parseFloat(($(this).val() == '') ? 0 : $(this).val() * 20)
            var _coins =  parseFloat(($('[name="amount_coins"]').val() == '') ? 0 :$('[name="amount_coins"]').val())
            var cash = parseFloat(_1000) + parseFloat(_500) + parseFloat(_100) +parseFloat( _50) + parseFloat(_20) + parseFloat(_coins)
            $('#step6').find('.total').text('P '+ cash)
        })

        $('[name="amount_coins"]').on('input',function () {
            var _1000 = parseFloat(($('[name="amount_1000"]').val() == '') ? 0 : $('[name="amount_1000"]').val() * 1000)
            var _500 = parseFloat(($('[name="amount_500"]').val() == '') ? 0 : $('[name="amount_500"]').val() * 500)
            var _100 =  parseFloat(($('[name="amount_100"]').val() == '') ? 0 :$('[name="amount_100"]').val() * 100)
            var _50 =  parseFloat(($('[name="amount_50"]').val() == '') ? 0 :$('[name="amount_50"]').val() * 50)
            var _20 =  parseFloat(($('[name="amount_20"]').val() == '') ? 0 :$('[name="amount_20"]').val() * 20)
            var _coins = parseFloat(($(this).val() == '') ? 0 : $(this).val())

            var cash = parseFloat(_1000) + parseFloat(_500) + parseFloat(_100) +parseFloat( _50) + parseFloat(_20) + parseFloat(_coins)
            $('#step6').find('.total').text('P '+ cash)
        })

        $('body').delegate('#step8 div div:nth-child(4) input','input',function () {
            var w_total = 0
            $.each($('#step8 div div:nth-child(4) input'),function (index,value){
                w_total = w_total + parseInt(($(this).val() == '') ? 0 : $(this).val())
                $('#step8').find('.total').text('P '+w_total)
            })
        })

    }

    function addButtons() {
        //add with receipt
        $('body').delegate('#add-w-rec','click',function () {

            var ctr = $('#step1').find('.row.margin_top').length + 1

            $('#step1').append('<div class="row margin_top">' +
                '<div class="col-md-1 ">' +
                '<div class="number-ctr">'+ ctr +'.</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<input type="text" class="form-control" name="with_receipt['+ (ctr - 1)+'][rec_no]" placeholder="Receipt no."></div>' +
                '<div class="col-md-5">' +
                '<input type="text" id="w-amount" class="form-control" name="with_receipt['+ (ctr - 1)+'][rec_amount]" placeholder="Amount"></div>' +
                '</div>');

            $.each($('#step1 div.col-md-6 input'),function (index,value){
                if(index != 0){
                    $(this).val(parseInt(($('#step1 div.col-md-6 input:nth-child(1)').val() == '') ? 0 : $('#step1 div.col-md-6 input:nth-child(1)').val()) + index)
                }
            })

            $(this).remove()

            $('#step1 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-w-rec">Add more</button></div>')

            $('#taken-amount,#return-amount,#expense-amount,#credit-amount,#w-amount,#wo-amount').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

        })




        //add wo receipt
        $('body').delegate('#add-wo-rec','click',function () {

            var ctr = $('#step2').find('.row.margin_top').length + 1
            $('#step2').append('<div class="row margin_top">' +
                '<div class="col-md-1">' +
                '<div class="number-ctr">'+ ctr +'.</div></div>' +
                '<div class="col-md-11">' +
                '<input type="text" class="form-control" id="wo-amount" name="without_receipt['+ (ctr - 1)+'][amount]" placeholder="Amount"></div>' +
                '</div>')

            $(this).remove()
            $('#step2 div.row.margin_top:last-child div:nth-child(2)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-wo-rec">Add more</button></div>')


            //numeric input
            $('#taken-amount,#return-amount,#expense-amount,#credit-amount,#w-amount,#wo-amount').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

        })

        //add credit
        $('body').delegate('#add-credit','click',function () {
            var ctr = $('#step3').find('.row.margin_top').length + 1
            $('#step3').append('<div class="row margin_top">' +
                '<div class="col-md-1">' +
                '<div class="number-ctr">' + ctr +'.</div></div>' +
                '<div class="col-md-5"><input type="text" class="form-control" name="credit['+ (ctr - 1)+'][company]" placeholder="Company"></div>' +
                '<div class="col-md-6"><input type="text" class="form-control" name="credit['+ (ctr - 1)+'][bank_name]" placeholder="Bank Name"></div>' +
                '<div class="col-md-5 margin_top"><input type="text" class="form-control" name="credit['+ (ctr - 1)+'][bank]" placeholder="Bank Number"></div>' +
                '<div class="col-md-6 margin_top"><input type="text" id="credit-amount" class="form-control" name="credit['+ (ctr - 1)+'][amount]" placeholder="Amount"></div>' +
                '</div>')
            $(this).remove()
            $('#step3 div.row.margin_top:last-child div:nth-child(5)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-credit">Add more</button></div>')

            //numeric input
            $('#taken-amount,#return-amount,#expense-amount,#credit-amount,#w-amount,#wo-amount').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

        })

        //add expense
        $('body').delegate('#add-expense','click',function () {
            var ctr = $('#step4').find('.row.margin_top').length + 1
            $('#step4').append('<div class="row margin_top">' +
                '<div class="col-md-1 "><div class="number-ctr">' + ctr +'.</div></div>' +
                '<div class="col-md-6"><input type="text" class="form-control" name="expense['+ (ctr - 1)+'][details]" placeholder="Details"></div>' +
                '<div class="col-md-5"><input type="text" id="expense-amount" class="form-control" name="expense['+ (ctr - 1)+'][amount]" placeholder="Amount"></div>' +
                '</div>')
            $(this).remove()
            $('#step4 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-expense">Add more</button></div>')

            //numeric input
            $('#taken-amount,#return-amount,#expense-amount,#credit-amount,#w-amount,#wo-amount').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});


        })

        //add return
        $('body').delegate('#add-return','click',function () {
            var ctr = $('#step5').find('.row.margin_top').length + 1
            $('#step5').append('<div class="row margin_top">' +
                '<div class="col-md-1 "><div class="number-ctr">' + ctr +'.</div></div>' +
                '<div class="col-md-6"><input type="text" class="form-control" name="return['+ (ctr - 1)+'][name]" placeholder="Name"></div>' +
                '<div class="col-md-5"><input type="text" id="return-amount" class="form-control" name="return['+ (ctr - 1)+'][amount]" placeholder="Amount"></div>' +
                '</div>')

            $(this).remove()
            $('#step5 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-return">Add more</button></div>')

            //numeric input
            $('#taken-amount,#return-amount,#expense-amount,#credit-amount,#w-amount,#wo-amount').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});


        })

        //add taken
        $('body').delegate('#add-taken','click',function () {
            var ctr = $('#step7').find('.row.margin_top').length + 1
            $('#step7').append('<div class="row margin_top">' +
                '<div class="col-md-1"><div class="number-ctr">'+ctr+'.</div></div>' +
                '<div class="col-md-6"><input type="text" class="form-control" name="taken['+ (ctr - 1)+'][name]" placeholder="Name"></div>' +
                '<div class="col-md-5"><input type="text" id="taken-amount" class="form-control" name="taken['+ (ctr - 1)+'][amount]" placeholder="Amount"></div>' +
                '</div>')
            $(this).remove()
            $('#step7 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-taken">Add more</button></div>')


            //numeric input
            $('#taken-amount,#return-amount,#expense-amount,#credit-amount,#w-amount,#wo-amount').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

        })

        //add deposit
        $('body').delegate('#add-deposit','click',function () {
            var ctr = $('#step8').find('.row.margin_top').length + 1

            $('#step8').append('<div class="row margin_top">' +
                '<div class="col-md-1"><div class="number-ctr">'+ ctr +'.</div></div>' +
                '<div class="col-md-4"><input type="text" class="form-control" name="deposit['+ (ctr -1) +'][bank_name]" placeholder="Name" value=""></div>' +
                '<div class="col-md-4"><input type="text" class="form-control" name="deposit['+ (ctr -1) +'][bank_number]" placeholder="Amount" value=""></div>' +
                '<div class="col-md-3"><input type="text" id="deposit-amount" class="form-control" name="deposit['+ (ctr - 1) +'][amount]" placeholder="Amount" value=""></div>' +
                '</div>')
            $(this).remove()
            $('#step8 div.row.margin_top:last-child div:nth-child(4)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-deposit">Add more</button></div>')


            //numeric input
            $('#taken-amount,#return-amount,#expense-amount,#credit-amount,#w-amount,#wo-amount').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

        })

        //numeric input
        $('[name="amount_1000"],[name="amount_500"],[name="amount_100"],[name="amount_50"],[name="amount_20"],[name="amount_coins"]').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

    }

    function noData(){
        $('#step1').find('.total').text('P 0')

        $('#step1').find('.margin_top').remove()
        $('#step1').append('<div class="row margin_top">' +
            '<div class="col-md-1 ">' +
            '<div class="number-ctr">1.</div>' +
            '</div>' +
            '<div class="col-md-6">' +
            '<input type="text" class="form-control" name="with_receipt[0][rec_no]" placeholder="Receipt no." value=""></div>' +
            '<div class="col-md-5">' +
            '<input type="text" id="w-amount" class="form-control" name="with_receipt[0][rec_amount]" placeholder="Amount" value=""></div>' +
            '</div>');
        $('#step1 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-w-rec">Add more</button></div>')


        $('#step2').find('.total').text('P 0')

        $('#step2').find('.margin_top').remove()
        $('#step2').append('<div class="row margin_top">' +
            '<div class="col-md-1">' +
            '<div class="number-ctr">1.</div></div>' +
            '<div class="col-md-11">' +
            '<input type="text" id="wo-amount" class="form-control" name="without_receipt[0][amount]" placeholder="Amount" value=""></div>' +
            '</div>')
        $('#step2 div.row.margin_top:last-child div:nth-child(2)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-wo-rec">Add more</button></div>')

        $('#step3').find('.total').text('P 0')
        $('#step3').find('.margin_top').remove()
        $('#step3').append('<div class="row margin_top">' +
            '<div class="col-md-1">' +
            '<div class="number-ctr">1.</div></div>' +
            '<div class="col-md-3"><input type="text" class="form-control" name="credit[0][company]" placeholder="Company" value=""></div>' +
            '<div class="col-md-3"><input type="text" class="form-control" name="credit[0][bank_name]" placeholder="Bank Name" value=""></div>' +
            '<div class="col-md-3"><input type="text" class="form-control" name="credit[0][bank]" placeholder="Bank Number" value=""></div>' +
            '<div class="col-md-2"><input type="text" id="credit-amount" class="form-control" name="credit[0][amount]" placeholder="Amount" value=""></div>' +
            '</div>')
        $('#step3 div.row.margin_top:last-child div:nth-child(5)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-credit">Add more</button></div>')


        $('#step4').find('.total').text('P 0')
        $('#step4').find('.margin_top').remove()
        $('#step4').append('<div class="row margin_top">' +
            '<div class="col-md-1 "><div class="number-ctr">1.</div></div>' +
            '<div class="col-md-6"><input type="text" class="form-control" name="expense[0][details]" placeholder="Details" value=""></div>' +
            '<div class="col-md-5"><input type="text" id="expense-amount" class="form-control" name="expense[0][amount]" placeholder="Amount" value=""></div>' +
            '</div>')
        $('#step4 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-expense">Add more</button></div>')

        $('#step5').find('.total').text('P 0')
        $('#step5').find('.margin_top').remove()
        $('#step5').append('<div class="row margin_top">' +
            '<div class="col-md-1 "><div class="number-ctr">1.</div></div>' +
            '<div class="col-md-6"><input type="text" class="form-control" name="return[0][name]" placeholder="Name" value=""></div>' +
            '<div class="col-md-5"><input type="text" id="return-amount" class="form-control" name="return[0][amount]" placeholder="Amount" value=""></div>' +
            '</div>')
        $('#step5 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-return">Add more</button></div>')


        $('#step7').find('.total').text('P 0')
        $('#step7').find('.margin_top').remove()
        $('#step7').append('<div class="row margin_top">' +
            '<div class="col-md-1"><div class="number-ctr">1.</div></div>' +
            '<div class="col-md-6"><input type="text" class="form-control" name="taken[0][name]" placeholder="Name" value=""></div>' +
            '<div class="col-md-5"><input type="text" id="taken-amount" class="form-control" name="taken[0][amount]" placeholder="Amount" value=""></div>' +
            '</div>')
        $('#step7 div.row.margin_top:last-child div:nth-child(3)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-taken">Add more</button></div>')

        $('#step8').find('.total').text('P 0')
        $('#step8').find('.margin_top').remove()

        $('#step8').append('<div class="row margin_top">' +
            '<div class="col-md-1"><div class="number-ctr">1.</div></div>' +
            '<div class="col-md-4"><input type="text" class="form-control" name="deposit[0][bank_name]" placeholder="Bank Name" value=""></div>' +
            '<div class="col-md-4"><input type="text" class="form-control" name="deposit[0][bank_number]" placeholder="Bank Number" value=""></div>' +
            '<div class="col-md-3"><input type="text" id="deposit-amount" class="form-control" name="deposit[0][amount]" placeholder="Amount" value=""></div>' +
            '</div>')
        $('#step8 div.row.margin_top:last-child div:nth-child(4)').append('<div class="margin_top text-right"><button type="button" class="btn btn-primary" id="add-deposit">Add more</button></div>')


        $('#step6').find('.total').text('P 0')
    }

    function noCashBreakdown(isCheck){
        //numeric input
        $('[name="amount_1000"],[name="amount_500"],[name="amount_100"],[name="amount_50"],[name="amount_20"],[name="amount_coins"]').prop('disabled',isCheck)
        if(isCheck){
            $('[name="amount_1000"],[name="amount_500"],[name="amount_100"],[name="amount_50"],[name="amount_20"],[name="amount_coins"]').val('')
            $('#step6').find('.total').text('P 0')
        }
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
                $taken = $total['taken_total'];
                $bank = $total['deposit_total'];
                $is_check = $total['is_check'];
                $coh = $total['coh'];

                $_total = (($w_receipt + $wo_receipt) -$coh) - $expense ;
                $loss=0;
                $excess=0;

                $money = $cash + $taken;

            if($_total > $money){
                $loss = $money - $_total;
            }else{

                $excess = $money - $_total ;
            }

            ?>

        <div class="col-lg-4 col-xs-12 col-sm-6 date-sales">
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
                        <td>TAKEN</td>
                        <td>{{ 'P '.number_format($taken,2) }} </td>
                    </tr>
                    <tr>
                        <td>BANK DEPOSIT</td>
                        <td>{{ 'P '.number_format($bank,2) }} </td>
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
                            @if($is_check == 1)
                                    @if($excess == 0)
                                        {{ 'P '.number_format(0,2) }}
                                    @else
                                        <b style="color: blue">{{ 'P '.number_format(0,2) }}</b>
                                    @endif
                                @else
                                    @if($excess == 0)
                                        {{ 'P '.number_format($excess,2) }}
                                    @else
                                        <b style="color: blue">{{ 'P '.number_format($excess,2) }}</b>
                                    @endif
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <td>LOSS</td>
                        <td>
                            @if($is_check == 1)
                                @if($loss == 0)
                                    {{ 'P '.number_format(0,2) }}
                                @else
                                    <b style="color: red">{{ 'P '.number_format(0,2) }}</b>
                                @endif
                                @else
                                @if($loss == 0)
                                    {{ 'P '.number_format($loss,2) }}
                                @else
                                    <b style="color: red">{{ 'P '.number_format($loss,2) }}</b>
                                @endif
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <td style="color: red">{{ ($is_check == 1) ? '**No cash breakdown' :'-' }}</td>
                        <td>

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
