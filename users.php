<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<button class="btn btn-outline-primary w-100" id="new_user"><i class="fa fa-plus"></i> New user</button>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4><b><i class="fas fa-user-shield"></i> User Info</b></h4>
					</div>
					<div class="card-body">
						<div class="table-responsive-sm">
							<table class="table table-striped table-bordered border-warning table-info">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Name</th>
										<th scope="col">Username</th>
										<th scope="col">User Type</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									include 'db_connect.php';
									$users = shopConn($dbName)->query("SELECT * FROM users order by name asc");
									$i = 1;
									while ($row = $users->fetch_assoc()) :
									?>
										<tr>
											<td scope="row">
												<?php echo $i++ ?>
											</td>
											<td>
												<?php echo $row['name'] ?>
											</td>
											<td>
												<?php echo $row['username'] ?>
											</td>
											<td>
												<?php if ($row['type']  == 1)
													echo "Admin";
												else
													echo "Cashier";
												?>
											</td>
											<td>
												<center>
													<div class="btn-group">
														<button type="button" class="btn btn-primary">Action</button>
														<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															<span class="sr-only">Toggle Dropdown</span>
														</button>
														<div class="dropdown-menu">
															<a class="dropdown-item edit_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Edit</a>
															<div class="dropdown-divider"></div>
															<a class="dropdown-item delete_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Delete</a>
														</div>
													</div>
												</center>
											</td>
										</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="card-footer"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('table').dataTable()
	})

	$(document).on('click', '#new_user', function() {
		uni_modal('<i class="fas fa-users-cog"></i> New User', 'manage_user.php')

	})
	$(document).on('click', '.edit_user', function() {
		uni_modal('<i class="fas fa-user-cog"></i> Edit User', 'manage_user.php?id=' + $(this).data('id'))
	})
	$(document).on('click', '.delete_user', function() {
		_conf("Are you sure to delete this user?", "delete_user", [$(this).data('id')])
	})

	function delete_user($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_user',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				} else {
					alert_toast("An error occurred. Please try again.", 'danger')
					setTimeout(function() {
						location.reload()
					}, 1500)
				}
			}
		})
	}
</script>