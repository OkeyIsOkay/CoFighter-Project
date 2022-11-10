<?php
    $page = 'booked-vaccination';
?>
@include('Doctors.header')
        <!--end sidebar wrapper -->		<!--end navigation-->
		<!--start page wrapper -->
				<!--start page wrapper -->
<?php
$message = 'Doctor';

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
				<h6 class="mb-0 text-uppercase">Booked Vaccination for {{ $user->center_name }}</h6>
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
                                        <th>Vaccination ID</th>
										<th>Name</th>
										<th>Phone</th>
										<th>Prefered Date</th>
                                        <th>Previously Vaccinated</th>
                                        <th>Sick Status</th>
                                        <th>Approval Status</th>
                                        <th>Action</th>
									</tr>
								</thead>
								<tbody>
                                    @foreach($bookings as $booking)
									<tr>
                                        <td>{{ $t++ }}</td>
                                        <td>{{ $booking->vaccination_id }}</td>
                                        <td>{{ $booking->surname.' '.$booking->firstname }}</td>
                                        <td>{{ $booking->phone }}</td>
                                        <td>{{ date('Y-m-d', strtotime($booking->preferred_date)) }}</td>
                                        <td><?php if($booking->is_vaccinated){echo 'Yes';}else{echo 'No';} ?></td>
                                        <td><?php if($booking->is_sick){echo 'Sick';}else{echo 'Not Sick';} ?></td>
                                        <td>{{ $booking->approval_status }}</td>
                                        <td>
                                            @if($booking->approval_status == 'Pending')
                                            <a href="{{ url('/CoFighter/doctor/booked-vaccination/action/approve/'.$booking->id) }}" onclick="return confirm('Are you sure you want to perform this action?')">Approve</a>
                                            <p><a href="{{ url('/CoFighter/doctor/booked-vaccination/action/disapprove/'.$booking->id) }}" onclick="return confirm('Are you sure you want to perform this action?')">Disapprove</a></p>
                                            @endif

                                            @if($booking->approval_status == 'Approved')
                                            <a href="{{ url('/CoFighter/doctor/booked-vaccination/action/disapprove/'.$booking->id) }}" onclick="return confirm('Are you sure you want to perform this action?')">Disapprove</a>
                                            @endif

                                            @if($booking->approval_status == 'Disapproved')
                                            <a href="{{ url('/CoFighter/doctor/booked-vaccination/action/approve/'.$booking->id) }}" onclick="return confirm('Are you sure you want to perform this action?')">Approve</a>
                                            @endif
                                        </td>
									</tr>
                                    @endforeach
								</tbody>
								<tfoot>
									<tr>
										<th>S/N</th>
                                        <th>Vaccination ID</th>
										<th>Name</th>
										<th>Phone</th>
										<th>Prefered Date</th>
                                        <th>Vaccination Status</th>
                                        <th>Sick Status</th>
                                        <th>Approval Status</th>
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
