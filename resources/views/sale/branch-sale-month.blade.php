@extends('layouts.admin')

@push('styles')
<style>
    .info-box-icon{
        float: none;
        width: 100% !important;
        height: 50px;
        line-height: 1.5;
        font-size: 32px;
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

</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {

        var base  = $('#base_url').val()


        loadMonths()


        var current_date = $('#year').text();
        var now = new Date(current_date);
        var date_now = new Date()


        $('#previous-month').click(function(){

            if($('#year').text() > 2014){
                var past = now.setFullYear(now.getFullYear() -1);
                $('#year').text(now.getFullYear());
                loadMonths()
            }
        });


        $('#next-month').click(function(){
            if(($('#year').text() < date_now.getFullYear()) ){
                var future = now.setFullYear(now.getFullYear() +1);
                $('#year').text(now.getFullYear());
                loadMonths()
            }
        });

        function  loadMonths() {
            $('#content').waitMe({})
            $.ajax({
                url:base+'/branch-sale/ajax',
                type:'POST',
                data: {
                    _token: $('meta[name="csrf_token"]').attr('content'),
                    branch: $('#branch').val(),
                    year: $('#year').text(),
                },
                success: function(data){
                    $('.data-month').html(data)
                    $('#content').waitMe('hide')
                }
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
    <section class="content">
        <input  type="hidden" id="branch" value=" {{ $branch_id }}" />
        <div class="row">
            <div class="col-md-12">
                <div class="info-box-icon bg-aqua margin-bottom">
                    <span class="ion ion-arrow-left-a padding-icon" id="previous-month"> </span>
                    <span class="">{{ \App\Branches::find($branch_id)->name }} <year id="year">2018</year></span>
                    <span class="ion ion-arrow-right-a padding-icon" id="next-month"> </span>
                </div>
            </div>
        </div>

        <div class="row data-month">


        </div>


    </section>
@endsection
