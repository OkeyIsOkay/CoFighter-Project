<?php
    $page = 'reported-cases';
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
                                    <li class="breadcrumb-item active" aria-current="page">Reported Case</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end breadcrumb-->
                    <h6 class="mb-0 text-uppercase">Change Case Status</h6>
				    <hr/>
                    <div class="container">
                        <div class="main-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ url('/CoFighter/doctor/reported-cases-status') }}" method="POST">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="id" value="{{ $case->id }}">
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Status</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <select class="form-control" name="status" required>
                                                            <option value="">Select user Status</option>
                                                            @if($case->status != 'NEGATIVE')
                                                                <option value="NEGATIVE">Negative</option>
                                                            @endif
                                                            @if($case->status != 'POSITIVE')
                                                                <option value="POSITIVE">Positive</option>
                                                            @endif
                                                            @if($case->status != 'DISCHARGED')
                                                                <option value="DISCHARGED">Discharged</option>
                                                            @endif
                                                            @if($case->status != 'DEATH')
                                                                <option value="DEATH">Dead</option>
                                                            @endif
                                                        </select>
                                                        @if ($errors->has('status'))
                                                        <li style="color:red;">{{ $errors->first('status') }}</li>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Comment</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <textarea class="form-control" name="Comment" placeholder="Optional Comment"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="submit" class="btn btn-primary px-4" value="Update" />
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
