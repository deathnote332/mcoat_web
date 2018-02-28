@extends('layouts.app')
@push('styles')
<style>
    .login-box{
        width: 600px;
    }
</style>
@endpush

@section('title')
    REGISTER
@endsection

@section('content')
<div class="container">

    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b class="title">MCOAT</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg"></p>


            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <p class="login-box-msg">Register a new membership</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" name="last_name" placeholder="Last name">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" name="first_name" placeholder="First name">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <select name="branch" class="form-control">
                        <option selected disabled>Select Branch</option>
                        @foreach(\App\Branches::all() as $key=>$val)
                            <option value="{{ $val->id }}"> {{ $val->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('branch'))
                        <span class="help-block">
                            <strong>{{ $errors->first('branch') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback">
                    <input type="email" class="form-control" name="email" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Retype password">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                <div class="row">

                    <!-- /.col -->
                    <div class="col-md-4 col-md-offset-8">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>




        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
</div>


@endsection
