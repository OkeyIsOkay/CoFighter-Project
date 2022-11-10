<!doctype html>
<html lang="en">
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
		<titleCoFighter</title>
	</head>
<?php
$message = null;
if(Session::has('message')){
    $message = Session::get('message');
    Session::forget('message');
}
?>
<body class="bg-login">
	<!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="mb-4 text-center">
                            <img src="{{ asset('assets/images/logo-icon.png') }}" width="180" alt="" />
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">Sign in</h3>
                                        <div class="login-separater text-center mb-4">
                                            <hr/>
                                        </div>
                                        @if (count($errors) > 0)
                                            <div class="error">
                                                <span style="color:red;">There are errors. Fix them and try again.</span>
                                            </div>
                                            <div class="login-separater text-center mb-4">
                                                <hr/>
                                            </div>
                                        @elseif($message)
                                        <div class="error">
                                                <span style="color:red;">{{ $message }}</span>
                                        </div>
                                        <div class="login-separater text-center mb-4">
                                            <hr/>
                                        </div>
                                        @endif

                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" action="{{ url('/CoFighter/loginCheck') }}" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Email Address</label>
                                                <input type="email" class="form-control" id="inputEmailAddress" name="email" value="{{ old('email') }}" placeholder="Email Address" required>
                                                @if ($errors->has('email'))
                                                <li style="color:red;">{{ $errors->first('email') }}</li>
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0" id="inputChoosePassword" value="{{ old('password') }}" name="password" placeholder="Enter Password" required> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                                    @if ($errors->has('password'))
                                                    <li style="color:red;">{{ $errors->first('password') }}</li>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Sign in</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
	<!--end wrapper-->

	<!--plugins-->
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
</body>
</html>
