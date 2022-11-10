<?php
    $page = 'index';
?>
@include('OverallSuperAdmins.header')
        <!--end sidebar wrapper -->		<!--end navigation-->
		<!--start page wrapper -->
				<!--start page wrapper -->
<?php
$rank = 'Admin';
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
				<h6 class="mb-0 text-uppercase">All Users</h6>
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
										<th>Name</th>
										<th>Email</th>
										<th>Phone</th>
                                        <th>Rank</th>
										<th>Status</th>
                                        <th>Activity</th>
                                        <th>Action</th>
									</tr>
								</thead>
								<tbody>
                                    @foreach($doctors as $doctor)
									<tr>
                                        <td>{{ $t++ }}</td>
                                        <td>{{ $doctor->surname.' '.$doctor->firstname }}</td>
                                        <td>{{ $doctor->email }}</td>
                                        <td>{{ $doctor->phone }}</td>
                                        <td>@if($doctor->rank == 1) Doctor @endif @if($doctor->rank == 5) Admin @endif</td>
                                        <td><?php if($doctor->verify){echo 'Verified';}else{echo 'Unverified';} ?></td>
                                        <td><?php if($doctor->active){echo 'Active';}else{echo 'Disabled';} ?></td>
                                        <td>
                                            @if($doctor->active)
                                                <a href="{{ url('/CoFighter/super-admin/users/edit/'.$doctor->id) }}" onclick="return confirm('Are you sure you want to perform this action?')">Disable</a>
                                            @else
                                            <a href="{{ url('/CoFighter/super-admin/users/edit/'.$doctor->id) }}" onclick="return confirm('Are you sure you want to perform this action?')">Enable</a>
                                            @endif
                                        </td>
									</tr>
                                    @endforeach
								</tbody>
								<tfoot>
									<tr>
										<th>S/N</th>
										<th>Name</th>
										<th>Email</th>
										<th>Phone</th>
                                        <th>Rank</th>
										<th>Status</th>
                                        <th>Activity</th>
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
@include('OverallSuperAdmins.footer')
