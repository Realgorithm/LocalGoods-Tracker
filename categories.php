<?php include('db_connect.php'); ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4 mb-3">
				<form action="" id="manage-category" class="needs-validation" novalidate>
					<div class="card">
						<div class="card-header">
							<h5><i class="fa fa-pencil"></i> Category Form</h5>
						</div>
						<div class="card-body">
							<input type="hidden" name="id">
							<div class="mb-3">
								<label class="form-label">Category</label>
								<input type="text" class="form-control" name="name" required>
								<div class="invalid-feedback">
									Please enter the category name.
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-4 offset-md-1 mb-2 me-2">Save</button>
									<button class="btn btn-sm btn-danger col-sm-4 offset-md-1 mb-2" type="button" onclick="$('#manage-category').trigger('reset');">Cancel</button>
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
						<h4><b><i class="fa fa-th-list"></i> Category List</b></h4>
					</div>
					<div class="card-body">
						<div class="table-responsive-sm">
							<table class="table table-striped table-bordered border-warning table-info">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Name</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									$conn->select_db('central_db');
									$cats = $conn->query("SELECT * FROM categories ORDER BY name ASC");
									while ($row = $cats->fetch_assoc()) :
									?>
										<tr>
											<td scope="row"><?php echo $i++ ?></td>
											<td><?php echo $row['name'] ?></td>
											<td>
												<button class="btn btn-sm btn-primary edit_cat mb-2" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>">Edit</button>
												<button class="btn btn-sm btn-danger delete_cat mb-2" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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

<script>
	$(document).ready(function() {
		// Initialize DataTables plugin
		$('table').dataTable();
		'use strict';

		// Handle category form submission
		$('#manage-category').each(function() {
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
					url: 'ajax.php?action=save_category',
					data: new FormData(this),
					cache: false,
					contentType: false,
					processData: false,
					method: 'POST',
					success: function(resp) {
						if (resp == 1) {
							alert_toast("Data successfully added", 'success');
							setTimeout(function() {
								location.reload();
							}, 1500);

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
				});
				form.addClass('was-validated');
				e.preventDefault(); // Prevent default form submission
			});
		});

		// Handle edit button click
		$(document).on('click', '.edit_cat', function() {
			start_load();
			var cat = $('#manage-category');
			cat.trigger('reset');
			cat.find("[name='id']").val($(this).data('id'));
			cat.find("[name='name']").val($(this).data('name'));
			end_load();
		});

		// Handle delete button click
		$(document).on('click', '.delete_cat', function() {
			_conf("Are you sure to delete this category?", "delete_cat", [$(this).data('id')]);
		});
	});

	// Function to delete category
	function delete_cat(id) {
		start_load();
		$.ajax({
			url: 'ajax.php?action=delete_category',
			method: 'POST',
			data: {
				id: id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				} else {
					alert_toast("An error occurred. Please try again.", 'danger')
					setTimeout(function() {
						location.reload()
					}, 1500)
				}
			}
		});
	}
</script>