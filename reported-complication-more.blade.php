<?php
    $page = 'reported-complications';
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
                                    <li class="breadcrumb-item active" aria-current="page">Reported Complication</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <h6 class="mb-0 text-uppercase">Send Medical Advice</h6>
				    <hr/>
                    <!--end breadcrumb-->
                    <div class="container">
                        <div class="main-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ url('/CoFighter/doctor/reported-complications') }}" method="POST">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="id" value="{{ $complication->id }}">

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Doctor Advice/Prescribtion</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <textarea class="form-control" name="Comment" placeholder="Enter Doctor's prescribtion or Advice" required></textarea>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="submit" class="btn btn-primary px-4" value="Send" />
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
