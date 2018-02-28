@extends('layouts.admin')

@push('styles')
<style>
    .info-box-content {
        padding: 30px 10px;
        font-size: 24px;
        font-weight: 600;

    }
    .small-box p {

        font-size: 13px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {

        var base  = $('#base_url').val()


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
            @foreach(\App\Branches::all() as $key=>$val)
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{$val->name}}</h3>

                            <p>{{ $val->address }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-people-outline"></i>
                        </div>
                        <a href="{{ url('/admin/branch-sale/'.$val->id) }}" class="small-box-footer">View more <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endforeach

        </div>


    </section>
@endsection
