<!doctype html>
<html lang="en">


<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
	<!--plugins-->
		<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
		<link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="" rel="stylesheet">
	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />
    <title>Ehime | @yield('title','Admin')</title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--start header -->
		<!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>

                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">

                            <li class="nav-item dropdown dropdown-large">

                                <div class="dropdown-menu dropdown-menu-end">

                                    <div class="header-notifications-list">

                                    </div>

                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-large">

                                <div class="dropdown-menu dropdown-menu-end">

                                    <div class="header-message-list">

                                    </div>

                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('assets/images/avatar.png') }}" class="user-img" alt="user avatar">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0">{{ $user->surname.' '.$user->firstname }}</p>
                                <p class="designattion mb-0">Super Admin</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:;"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>

        <!--end header -->		<!--end header -->
		<!--navigation-->
		<!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">{{APP_NAME}}</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li>
                    <a href="{{ url('/CoFighter/super-admin/dashboard') }}" >
                        <div class="<?php if($page == 'index'){ echo 'parent-icon';} ?>"><i class='bx bx-home-circle'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>

                <li>
                    <a href="{{ url('/CoFighter/super-admin/add-user') }}" >
                        <div class="<?php if($page == 'add-user'){ echo 'parent-icon';} ?>"><i class='bx bx-user-plus'></i>
                        </div>
                        <div class="menu-title">Add user</div>
                    </a>
                </li>

                <li>
                    <a href="{{ url('/CoFighter/logout') }}" >
                        <div><i class='bx bx-log-out'></i>
                        </div>
                        <div class="menu-title">Log out</div>
                    </a>
                </li>
            </ul>
            <!--end navigation-->
        </div>
        
        @if(session()->has('message'))
        <?php
            $message = null;
            $message = session()->get('message');
            Session::forget('message');
        ?>
                <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2" style="z-index: 999;">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-success"><i class='bx bxs-check-circle'></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-success">Success</h6>
                            <div>{{ $message }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
        @endif



        @if(session()->has('error_message'))
        <?php
            $error_message = null;
            $error_message = session()->get('error_message');
            Session::forget('error_message');
        ?>
            <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2" style="z-index: 999;">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-danger"><i class='bx bxs-message-square-x'></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-danger">Error</h6>
                        <div>{{ $error_message }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
