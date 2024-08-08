<?php include('db_connect.php'); ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4 mb-3">
				<form action="" id="manage-expenses" class="needs-validation" novalidate>
					<div class="card">
						<div class="card-header">
							<h5><i class="fa fa-edit"></i> Expenses Form</h5>
						</div>
						<div class="card-body">
							<input type="hidden" name="id">
							<div class="mb-3">
								<label class="form-label">Expense For</label>
								<input type="text" class="form-control" name="expense" required>
								<div class="invalid-feedback">
									Please provide the expense name.
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Description</label>
								<textarea name="description" class="form-control"></textarea>
							</div>
							<div class="mb-3">
								<label class="form-label">Expense Price</label>
								<input type="text" class="form-control" name="amount" required>
								<div class="invalid-feedback">
									Please Enter the Price of the expense.
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-4 offset-md-1 mb-2 me-2">Save</button>
									<button class="btn btn-sm btn-danger col-sm-4 offset-md-1 mb-2" type="button" onclick="$('#manage-expenses').trigger('reset')">Cancel</button>
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
						<h4><b><i class="fa fa-money-bill-wave"></i> Expense List</b></h4>
					</div>
					<div class="card-body">
						<div class="table-responsive-sm">
							<table class="table table-striped table-bordered border-warning table-info">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Name</th>
										<th scope="col">Description</th>
										<th scope="col">Price</th>
										<th scope="col">Date</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									$expenses = shopConn($dbName)->query("SELECT * FROM expenses ORDER BY expense ASC");
									while ($row = $expenses->fetch_assoc()) :
									?>
										<tr>
											<td scope="row"><?php echo $i++ ?></td>
											<td><?php echo $row['expense'] ?></td>
											<td><?php echo $row['description'] ?></td>
											<td><?php echo $row['amount'] ?></td>
											<td><?php echo date("h:i A | d-M-Y", strtotime($row['date_updated'])) ?></td>
											<td class="expense-btn">
												<button class="btn btn-sm btn-primary edit_exp mb-2" type="button" data-id="<?php echo $row['id'] ?>" data-expense="<?php echo $row['expense'] ?>" data-amount="<?php echo $row['amount'] ?>" data-desc="<?php echo $row['description'] ?>">Edit</button>
												<button class="btn btn-sm btn-danger delete_exp mb-2" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
		$('table').dataTable()

		$('#manage-expenses').each(function() {
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
					url: 'ajax.php?action=save_expenses',
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
		});

		$(document).on('click', '.edit_exp', function() {
			start_load()
			var cat = $('#manage-expenses')
			cat.trigger('reset')
				.find("[name='id']").val($(this).data('id')).end()
				.find("[name='expense']").val($(this).data('expense')).end()
				.find("[name='description']").val($(this).data('desc')).end()
				.find("[name='amount']").val($(this).data('amount')).end()
			end_load()
		});

		$(document).on('click', '.delete_exp', function() {
			_conf("Are you sure to delete this expenses?", "delete_exp", [$(this).data('id')])
		});
	});



	function delete_exp($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_expenses',
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