@extends('layouts.admin')

@push('styles')
    <style>
        .margin-top{
            margin-top:10px
        }
        .hide{
            display:none
        }
    </style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        var base  = $('#base_url').val()

        $(".datepicker").datepicker( {
            format: "MM yyyy",
            viewMode: "months", 
            minViewMode: "months",
            autoclose: true
        }).datepicker("setDate",'now');

        $('.reports').val(0)
        

        $('.reports').on('change',function(){
            if($(this).val() == 0 || $(this).val() == 1){
                $('.dues').removeClass('hide')
            }else{
                $('.dues').addClass('hide')
                
            }
            if($(this).val() == 0){
                $('.cr').addClass('hide')
            }else{
                $('.cr').removeClass('hide')
            }
        })


        $(document).on('click','.generate',function(){
            var report = $('.reports option:selected').val()
            var branch = $('.branches option:selected').val()
            var _date = $('.datepicker').val()
           var _copy =  "&copy=" + $('.copy').val() ;
           
            var branch_condition = (branch == "Choose Branch")
            var report_condition = (report == "Choose Report")
            

            if(branch_condition || report_condition){
                swal('Please choose branch or report')
                return false;
            }

            var due = $('.due').val()
            

            if(report == 0){
                for(i=1;i<=4; i++){
                    var _due =  (i == 1) ? "?due=" + due : "?due=";
                     var path = base + "/generate/" + i  + "/"+branch+"/"+_date + _due + _copy;
                    window.open(path);
                }
            }else{
                var _due =  (report == 1) ? "?due=" + due : "?due=";
                var path = base + "/generate/" + report  + "/"+branch+"/"+_date + _due + _copy;
                window.open(path);
            }
            
        })
    })
</script>
@endpush

@section('title')
    BRANCH REPORT GENERATOR
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Branch Report Generator
        </h1>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3 margin-top">
                <label for="">Branch</label>
                <select class="branches form-control">
                    <option selected disabled>Choose Branch</option>
                    @foreach(\App\Branches::orderBy('name','asc')->where('status',1)->get() as $key=>$val)
                        <option value="{{$val->name}}" data-address="{{$val->address}}" data-id="{{$val->id}}">{{$val->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 margin-top">
                <label for="">Choose Date</label>
                <input type="text" class="datepicker form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 margin-top">
                <label for="">Report Type</label>
                <select class="reports form-control">
                    <option selected disabled>Choose Report</option>
                    <option value="0">All Report</option>
                   <option value="1">Daily Sales Report</option>
                   <option value="2">Purchase Report</option>
                   <option value="3">Credit Report</option>
                   <option value="4">Stock Report</option>
                </select>
            </div>
        </div>
         <div class="row dues hide">
            <div class="col-md-3 margin-top">
                <label for="">Set Monthly Dues</label>
                <input type="text" class="form-control due" placeholder="P 34,800.00">
            </div>
        </div>
        <div class="row cr hide">
            <div class="col-md-3 margin-top">
                <label for="">Set Control No.</label>
                <input type="text" class="form-control cr" placeholder="CR-{{ date('Y') }}-XXXXXXXX">
                <p style="color:red">*If mistake happened (Copy the previous control no.)</p>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-3 margin-top">
                <label for="">Number of copies</label>
                <input type="number" class="form-control copy" min="1" max="10" value="1">
            </div>
        </div>
        <div class="row ">
            <div class="col-md-3 margin-top">
                <button class="form-control btn btn-primary generate">Generate</button>
            </div>
        </div>
    </section>
@endsection
