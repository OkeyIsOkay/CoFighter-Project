@include('verify.header')
        <!--end sidebar wrapper -->		<!--end navigation-->
		<!--start page wrapper -->
		            <div class="page-wrapper">
                <div class="page-content">
                    <!--breadcrumb-->
                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">User Verification</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">User Verification</li>
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
                                            <form action="{{ url('/kyc/do-kyc') }}" method="POST">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="id" value="{{ $user->id }}">
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">System Name</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" value="{{ $user->name }}" disabled/>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Account Name</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" value="{{ $user->verified_bank_name }}" disabled/>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Address</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" value="{{ $user->address }}" disabled/>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">ID Type</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" value="{{ $user->is_type }}" disabled/>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">ID Number</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" value="{{ $user->id_number }}" disabled/>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">ID file</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <?php $image = ResolvePicPath('kyc',$user->id_file);?>
                                                        <a href="{{ asset($image) }}" target="_blank"><img src="{{ asset($image) }}" alt="id file" class="rounded-circle p-1 bg-primary" width="110"></a>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Action</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input class="form-check-input" type="radio" name="action" value="1" id="invalidCheck"> Verify
                                                        <br>
                                                        <input class="form-check-input" type="radio" name="action" value="2" id="invalidCheck"> Unverify
                                                    </div>

                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Comment</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <textarea class="form-control" placeholder="If not verified, give reasons here." name="comment"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="submit" class="btn btn-primary px-4" value="Done" />
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
		@include('verify.footer')
