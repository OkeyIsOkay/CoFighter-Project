<?php
    $page = 'reported-cases';
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
					<div class="breadcrumb-title pe-3">Users</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Manage Users</li>
                            </ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				<h6 class="mb-0 text-uppercase">Cases reported to {{ $user->center_name }}</h6>
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
                                        <th>Reporter Name</th>
                                        <th>Reporter Phone</th>
                                        <th>Action</th>
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
                                        <td>{{ $case->reporter_name }}</td>
                                        <td>{{ $case->reporter_phone }}</td>
                                        <td><a href="{{ url('/CoFighter/doctor/reported-cases/more/'.$case->id) }}">More</a></td>

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
                                        <th>Reporter Name</th>
                                        <th>Reporter Phone</th>
                                        <th>Action</th>

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
