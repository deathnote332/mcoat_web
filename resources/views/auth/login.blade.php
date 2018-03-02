@extends('layouts.app')
@push('styles')
<style>
    .help-block{
        color: red;
    }
    .login-logo a{
        font-size: 42px;

    }
    .title{
        color: #232d48;

    }

    .login-page, .register-page {
        background: url(/../img/mcoat-bg.jpg) top center fixed;

    }

</style>
@endpush

@section('title')
    LOGIN
@endsection

@section('content')
<div class="container">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('') }}"><b class="title">MCOAT </b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg"></p>

            @if (Session::has('message'))

                <div class="alert alert-danger">
                    <ul>
                        <li>{{ Session::get('message')[0] }}</li>
                        <li>{{ Session::get('message')[1] }}</li>
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group has-feedback">

                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback">
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">

                    <!-- /.col -->
                    <div class="col-md-4 col-md-offset-8">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>



            <a  href="{{ route('password.request') }}">I forgot my password</a><br>
            <a href="{{ route('register') }}" class="text-center">Register a new membership</a>

        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

</div>
@endsection
