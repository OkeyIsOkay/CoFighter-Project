<?php
    $page = 'manage-user';
?>
@include('Doctors.header')
<?php
$rank = 'Doctor';
?>
        <!--end sidebar wrapper -->		<!--end navigation-->
		<!--start page wrapper -->
		            <div class="page-wrapper">
                <div class="page-content">
                    <!--breadcrumb-->
                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">Doctor</div>
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
                    <h6 class="mb-0 text-uppercase">Add Case</h6>
				    <hr/>
                    <!--end breadcrumb-->
                    <div class="container">
                        <div class="main-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ url('/CoFighter/doctor/cases/add') }}" method="POST">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Surame</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" name="surname" value="{{ old('surname') }}" required/>
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
                                                        <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required/>
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
                                                        <input type="phone" class="form-control" name="phone" value="{{ old('phone') }}" required/>
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
                                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required/>
                                                        @if ($errors->has('email'))
                                                        <li style="color:red;">{{ $errors->first('email') }}</li>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Address</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" name="address" value="{{ old('address') }}" required/>
                                                        @if ($errors->has('address'))
                                                        <li style="color:red;">{{ $errors->first('address') }}</li>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">City</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" name="city" value="{{ old('city') }}" required/>
                                                        @if ($errors->has('city'))
                                                        <li style="color:red;">{{ $errors->first('city') }}</li>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Sickness Severity</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <select class="form-select" id="sickness_type" name="sickness_type" required>
                                                            <option value="">Select</option>
                                                            <option value="0">Mild</option>
                                                            <option value="1">Very Sick</option>
                                                            <option value="2">Dead</option>

                                                        </select>
                                                        @if ($errors->has('sickness_type'))
                                                            <li style="color:red;">{{ $errors->first('sickness_type') }}</li>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Vaccination</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <select class="form-select" id="is_vaccinated" name="is_vaccinated" required>
                                                            <option value="">Select Vaccination status</option>
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>

                                                        </select>
                                                        @if ($errors->has('is_vaccinated'))
                                                            <li style="color:red;">{{ $errors->first('is_vaccinated') }}</li>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="submit" class="btn btn-primary px-4" value="Register" />
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
		@include('Doctors.footer')
