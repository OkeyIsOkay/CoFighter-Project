<?php
    $page = 'index';
?>
@include('OverallSuperAdmins.header')
<?php
$rank = 'Admin';
?>
        <!--end sidebar wrapper -->		<!--end navigation-->
		<!--start page wrapper -->
		            <div class="page-wrapper">
                <div class="page-content">
                    <!--breadcrumb-->
                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">Super Admin</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Add user</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end breadcrumb-->
                    <div class="container">
                        <div class="main-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ url('/CoFighter/super-admin/users/add') }}" method="POST">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">State</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <select class="form-select" id="state" name="state" required>
                                                            <option value="">Select State</option>
                                                            @foreach($states as $state)
                                                                <option value="{{$state->name}}">{{$state->name}}</option>
                                                            @endforeach

                                                        </select>
                                                        @if ($errors->has('state'))
                                                            <li style="color:red;">{{ $errors->first('state') }}</li>
                                                        @endif
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Center</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                    <select class="form-select" id="center" name="center" required>
                                                        <option value="">Select Center</option>
                                                        @foreach($centers as $center)
                                                            <option value="{{$center->id}}">{{$center->name}}</option>
                                                        @endforeach

                                                    </select>
                                                    @if ($errors->has('center'))
                                                        <li style="color:red;">{{ $errors->first('center') }}</li>
                                                    @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Rank</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                    <select class="form-select" id="rank" name="rank" required>
                                                        <option value="">Select Rank</option>
                                                        <option value="5">Admin</option>
                                                        <option value="1">Doctor</option>

                                                    </select>
                                                    @if ($errors->has('rank'))
                                                        <li style="color:red;">{{ $errors->first('rank') }}</li>
                                                    @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Surname</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" name="surname" required/>
                                                        @if ($errors->has('surname'))
                                                        <li style="color:red;">{{ $errors->first('surname') }}</li>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Firstname</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" name="firstname" required/>
                                                        @if ($errors->has('firstname'))
                                                        <li style="color:red;">{{ $errors->first('firstname') }}</li>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Phone</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="phone" class="form-control" name="phone" required/>
                                                        @if ($errors->has('phone'))
                                                        <li style="color:red;">{{ $errors->first('phone') }}</li>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Email</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="email" class="form-control" name="email" required/>
                                                        @if ($errors->has('email'))
                                                        <li style="color:red;">{{ $errors->first('email') }}</li>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="submit" class="btn btn-primary px-4" value="Add User" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
				<!--end page wrapper -->
		<!--start overlay-->
		@include('OverallSuperAdmins.footer')
