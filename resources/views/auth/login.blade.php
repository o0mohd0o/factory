<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('adminlte/plugins/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('adminlte/plugins/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('adminlte/plugins/Ionicons/css/ionicons.min.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('adminlte/dist/css/AdminLTE.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('adminlte/plugins/iCheck/square/blue.css')}}">

    <link rel="stylesheet" href="{{asset('adminlte/style.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/rtl.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo" style="margin-bottom: 10px;">
        <img src="{{asset('images/factory-icon.png')}}" width="100px" alt="factory">
    </div>
    <div class="login-logo">
        {{trans('popup.login')}}
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{trans('popup.login_start_session')}}</p>

        <form action="{{route('login')}}" method="POST">
            {{ csrf_field() }}
            <div class="form-group has-feedback">
                <input type="text" name="user_code" class="form-control{{ $errors->has('user_code') ? ' is-invalid' : '' }}" placeholder="{{trans('popup.user_code')}}" value="{{old('user_code')}}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('user_code'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('user_code') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{trans('popup.password')}}" value="{{old('password')}}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-6 {{app()->getLocale() == 'ar' ? 'pull-right' : 'pull-left'}} ">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> {{trans('popup.remeber_me')}}
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-6 pull-left">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{trans('popup.login')}}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <!-- <a class="btn btn-link" href="#">
            {{ __('نسيت كلمة المرور ؟') }}
        </a> -->
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{asset('adminlte/plugins/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('adminlte/plugins/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('adminlte/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });
</script>
</body>
</html>
