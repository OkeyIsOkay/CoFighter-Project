<?php
    $page = 'discharged-cases';
?>
@include('Doctors.header')
        <!--end sidebar wrapper -->		<!--end navigation-->
		<!--start page wrapper -->
				<!--start page wrapper -->

<?php
$rank = 'Doctor';
?>
		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Users Cases</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Discharged Cases</li>
                            </ol>
						</nav>
					</div>
                    <div class="ms-auto">
						<div class="btn-group">
							<a href="{{url('/CoFighter/doctor/cases/add')}}"><button type="button" class="btn btn-primary">
                                <i class='bx bx-calendar-plus'></i>
                                Add New Case</button></a>

						</div>
					</div>
				</div>
				<!--end breadcrumb-->
				<h6 class="mb-0 text-uppercase">Discharged Cases in {{ $user->center_name }}</h6>
				<hr/>
                <?php
                    $t = 1;
                ?>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered" style="width:100%">
								<thead>
									<tr>
										<th>S/N</th>
                                        <th>Case ID</th>
										<th>Victim</th>
										<th>Phone</th>
										<th>Address</th>
										<th>Case Type</th>
                                        <th>Status</th>
									</tr>
								</thead>
								<tbody>
                                    @foreach($cases as $case)
									<tr>
                                        <td>{{ $t++ }}</td>
                                        <td>{{ $case->case_id }}</td>
                                        <td>{{ $case->surname.' '.$case->firstname }}</td>
                                        <td>{{ $case->phone }}</td>
                                        <td>{{ $case->address }}</td>
                                        <td><?php if($case->case_type == 1){echo 'Very sick';}elseif($case->case_type == 2){echo 'Death';}else{echo 'Mild';} ?></td>
                                        <td><div class="<?php if($case->status == 'DISCHARGED'){ echo 'badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3';}elseif($case->status == 'NEGATIVE'){ echo 'badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3';}elseif($case->status == 'POSITIVE'){echo 'badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3';}elseif($case->status == 'DEAD'){echo 'badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3';}else{echo 'badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3';}?>"><i class='bx bxs-circle me-1'></i><?php if($case->status == 'DISCHARGED'){ echo 'DISCHARGED';}elseif($case->status == 'POSITIVE'){echo 'POSITIVE';}elseif($case->status == 'NEGATIVE'){echo 'NEGATIVE';}elseif($case->status == 'DEAD'){echo 'DEAD';}else{echo 'PENDING';}?></div></td>
                                    </tr>
                                    @endforeach
								</tbody>
								<tfoot>
									<tr>
										<th>S/N</th>
                                        <th>Case ID</th>
										<th>Victim</th>
										<th>Phone</th>
										<th>Address</th>
										<th>Case Type</th>
                                        <th>Status</th>

									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end page wrapper -->
				<!--end page wrapper -->
		<!--start overlay-->
@include('Doctors.footer')
