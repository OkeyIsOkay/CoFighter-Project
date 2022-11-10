<?php
    $page = 'reported-complications';
?>
@include('Doctors.header')
        <!--end sidebar wrapper -->
        <!--end navigation-->
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
				<h6 class="mb-0 text-uppercase">Complications reported to {{ $user->center_name }}</h6>
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
										<th>Victim</th>
                                        <th>Phone</th>
                                        <th>Address</th>
										<th>Date Vaccinated</th>
										<th>Age</th>
										<th>Symptoms</th>
                                        <th>Action</th>
									</tr>
								</thead>
								<tbody>
                                    @foreach($complications as $complication)
									<tr>
                                        <td>{{ $t++ }}</td>
                                        <td>{{ $complication->surname.' '.$complication->firstname }}</td>
                                        <td>{{ $complication->phone }}</td>
                                        <td>{{ $complication->address }}</td>
                                        <td>{{ date('d/m/Y',strtotime($complication->date_vaccinated)) }}</td>
                                        <td>{{ $complication->age }}</td>
                                        <td>{{ $complication->symptoms }}</td>
                                        <td><a href="{{ url('/CoFighter/doctor/reported-complications/more/'.$complication->id) }}">More</a></td>
									</tr>
                                    @endforeach
								</tbody>
								<tfoot>
									<tr>
										<th>S/N</th>
										<th>Victim</th>
                                        <th>Phone</th>
                                        <th>Address</th>
										<th>Date Vaccinated</th>
										<th>Age</th>
										<th>Symptoms</th>
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
