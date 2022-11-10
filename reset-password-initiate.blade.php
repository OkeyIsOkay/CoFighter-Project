<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
	<!-- loader-->
	<link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="" rel="stylesheet">
	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
	<title>CoFighter</title>
</head>
<?php
$message = null;
if(Session::has('message')){
    $message = Session::get('message');
    Session::forget('message');
}
?>
<body class="bg-forgot">
<!-- wrapper -->
<div class="wrapper">
    <div class="authentication-forgot d-flex align-items-center justify-content-center">
        <div class="card forgot-box">
            <div class="card-body">
                <div class="p-4 rounded  border">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/icons/forgot-2.png') }}" width="120" alt="" />
                    </div>
                    <h5 class="mt-5 font-weight-bold">You must reset your password to continue.</h5>
                    @if (count($errors) > 0)
                        <p style="color:red;" >There are errors. Kindly fix them.</p>
                    @elseif($message)
                        <p style="color:red;" >{{ $message }}</p>
                    @else
                        <p style="color:red;" >Enter your registered email to reset the password</p>
                    @endif
                    <form action="{{ url('/CoFighter/reset/initiate') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="my-4">
                            <label class="form-label">Email id</label>
                            <input type="email" class="form-control form-control-lg" placeholder="email@user.com" name="email" value="{{ old('password') }}" required />
                            @if ($errors->has('email'))
                            <li style="color:red;">{{ $errors->first('email') }}</li>
                            @endif
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Send OTP</button>
                            <a href="{{ url('/CoFighter/login') }}" class="btn btn-light btn-lg"><i class='bx bx-arrow-back me-1'></i>Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end wrapper -->
</body>
</html>
