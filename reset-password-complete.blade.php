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
$reset_code = null;
$email = null;
if(Session::has('message')){
    $message = Session::get('message');
    Session::forget('message');
}

if(Session::has('email')){
    $email = Session::get('email');
    Session::forget('email');
}

if(Session::has('reset_code')){
    $reset_code = Session::get('reset_code');
    Session::forget('reset_code');
}
?>
<body>
<!-- wrapper -->
<div class="wrapper">
    <div class="authentication-reset-password d-flex align-items-center justify-content-center">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-lg-5 border-end">
                            <div class="card-body">
                                <div class="p-5">
                                    <div class="text-start">
                                        <img src="{{ asset('assets/images/logo-img.png') }}" width="180" alt="">
                                    </div>
                                    <h4 class="mt-5 font-weight-bold">Genrate New Password</h4>
                                    @if (count($errors) > 0)
                                        <p style="color:red;" >There are errors. Kindly fix them.</p>
                                    @elseif($message)
                                        <p style="color:red;" >{{ $message }}</p>
                                    @else
                                        <p style="color:red;" >We received your reset password request. Please enter your new password!</p>
                                    @endif
                                    <form class="row g-3" action="{{ url('/CoFighter/reset/complete') }}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="email" value="{{ $email }}">
                                        <input type="hidden" name="reset_code" value="{{ $reset_code }}">
                                        <div class="mb-3 mt-5">
                                            <label class="form-label">New Password</label>
                                            <input type="password" class="form-control" name="password" placeholder="Enter new password" required/>
                                            @if ($errors->has('password'))
                                            <li style="color:red;">{{ $errors->first('password') }}</li>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" required />
                                            @if ($errors->has('password_confirmation'))
                                            <li style="color:red;">{{ $errors->first('password_confirmation') }}</li>
                                            @endif
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Change Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <img src="{{ asset('assets/images/login-images/forgot-password-frent-img.jpg') }}" class="card-img login-img h-100" alt="...">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end wrapper -->
</body>
</html>
