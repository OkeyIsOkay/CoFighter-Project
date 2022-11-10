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
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
	<title>DAP - Manage User Verification</title>
</head>
<?php
$message = null;
if(!empty($_GET['message']) && isset($_GET['message'])){
    $message = my_decode($_GET['message']);
}
?>
<body class="bg-lock-screen">
<!-- wrapper -->
<div class="wrapper">
    <div class="authentication-lock-screen d-flex align-items-center justify-content-center">
        <div class="card shadow-none bg-transparent">
            <div class="card-body p-md-5 text-center">
                <h2 class="text-white">{{ $time }}</h2>
                <h5 class="text-white">{{ $date }}</h5>
                <div class="">
                    <img src="{{ asset('assets/images/icons/user.png') }}" class="mt-5" width="120" alt="" />
                </div>
                <p class="mt-2 text-white">Administrator</p>
                @if (count($errors) > 0)
                    <div class="error">
                        <ul>
                            <li style="color:red;">There are errors. Fix them and try again.</li>
                        </ul>
                    </div>
                @elseif($message)
                <div class="error">
                    <ul>
                        <li style="color:red;">{{ $message }}</li>
                    </ul>
                </div>
                @endif
                <form action="{{ url('/kyc/loginCheck') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="mb-3 mt-3">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required />
                        @if ($errors->has('email'))
                        <li style="color:red;">{{ $errors->first('email') }}</li>
                        @endif
                    </div>
                    <div class="mb-3 mt-3">
                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password" required />
                        @if ($errors->has('password'))
                        <li style="color:red;">{{ $errors->first('password') }}</li>
                        @endif
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-white">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end wrapper -->
</body>


<!-- Mirrored from creatantech.com/demos/codervent/rocker/vertical/authentication-lock-screen by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 03 Nov 2021 01:01:56 GMT -->
</html>
