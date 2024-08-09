<?php include('db_connect.php'); ?>

<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4 mb-3">
				<form action="" id="manage-supplier" class="needs-validation" novalidate>
					<div class="card">
						<div class="card-header">
							<h5><i class="fa fa-pencil-alt"></i> Supplier Form</h5>
						</div>
						<div class="card-body">
							<input type="hidden" name="id">
							<div class="mb-3">
								<label class="form-label">Supplier</label>
								<input type="text" class="form-control" name="name" required>
								<div class="invalid-feedback">
									Please enter the supplier name.
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Contact</label>
								<input type="text" class="form-control" name="contact" pattern="\d{10}" required>
								<div class="invalid-feedback">
									Please enter a valid contact number.
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Address</label>
								<textarea class="form-control" cols="30" rows="3" name="address" required></textarea>
								<div class="invalid-feedback">
									Please enter the supplier Address.
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-4 offset-md-1 mb-2 me-2"> Save</button>
									<button class="btn btn-sm btn-danger col-sm-4 offset-md-1 mb-2" type="button" onclick="$('#manage-supplier').trigger('reset');"> Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<h4><b><i class="fa fa-parachute-box"></i> Supplier List</b></h4>
					</div>
					<div class="card-body">
						<div class="table-responsive-sm">
							<table class="table table-striped table-bordered border-warning table-info table-hover">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Supplier Info</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									$cats = shopConn($dbName)->query("SELECT * FROM suppliers order by id asc");
									while ($row = $cats->fetch_assoc()) :
									?>
										<tr>
											<td scope="row"><?php echo $i++ ?></td>
											<td>
												<p>Name : <b><?php echo $row['name'] ?></b></p>
												<p><small>Contact : <b><?php echo $row['contact'] ?></b></small></p>
												<p><small>Address : <b><?php echo $row['address'] ?></b></small></p>
											</td>
											<td class="supplier-btn">
												<button class="btn btn-sm btn-primary edit_supplier mb-2" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-contact="<?php echo $row['contact'] ?>" data-address="<?php echo $row['address'] ?>">Edit</button>
												<button class="btn btn-sm btn-danger delete_supplier mb-2" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
			<!-- Table Panel -->
		</div>
	</div>

</div>
<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset;
	}
</style>
<script>
	$(document).ready(function() {
		$('table').dataTable()

		'use strict';

		$('#manage-supplier').each(function() {
			var form = $(this);
			form.on('submit', function(e) {
				e.preventDefault()
				start_load()
				// Check if the form is valid
				if (form[0].checkValidity() === false) {
					e.preventDefault();
					e.stopPropagation();
					end_load();
					form.addClass('was-validated');
					return false;
				}
				$.ajax({
					url: 'ajax.php?action=save_supplier',
					data: new FormData($(this)[0]),
					cache: false,
					contentType: false,
					processData: false,
					method: 'POST',
					type: 'POST',
					success: function(resp) {
						if (resp == 1) {
							alert_toast("Data successfully added", 'success')
							setTimeout(function() {
								location.reload()
							}, 1500)

						} else if (resp == 2) {
							alert_toast("Data successfully updated", 'success')
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
				form.addClass('was-validated');
				e.preventDefault(); // Prevent default form submission
			})
		})
		$(document).on('click', '.edit_supplier', function() {
			start_load()
			var cat = $('#manage-supplier')
			cat.trigger('reset')
				.find("[name='id']").val($(this).data('id')).end()
				.find("[name='name']").val($(this).data('name')).end()
				.find("[name='contact']").val($(this).data('contact')).end()
				.find("[name='address']").val($(this).data('address')).end()
			end_load()
		})
		$(document).on('click', '.delete_supplier', function() {
			_conf("Are you sure to delete this supplier?", "delete_supplier", [$(this).data('id')])
		})
	});

	function delete_supplier($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_supplier',
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