<?php include('db_connect.php'); ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<div class="card">
					<div class="card-header">
						Customer Form
					</div>
					<div class="card-body">
						<form action="" id="manage-customer">
							<input type="hidden" name="id">
							<div class="mb-3">
								<label class="form-label">Customer Name</label>
								<input type="text" class="form-control" name="name" required>
							</div>
							<div class="mb-3">
								<label class="form-label">Contact</label>
								<input type="text" class="form-control" name="contact" required>
							</div>
							<div class="mb-3">
								<label class="form-label">Address</label>
								<textarea class="form-control" cols="30" rows="3" name="address" required></textarea>
							</div>

							<div class="mb-3">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3 mb-2"> Save</button>
								<button class="btn btn-sm btn-danger col-sm-3 mb-2" type="button" onclick="$('#manage-customer').get(0).reset()"> Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="table-responsive-sm">
					<table class="table table-striped table-bordered border-warning table-info table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Customer</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$customer = shop_conn($dbName)->query("SELECT * FROM customer_list order by id asc");
							while ($row = $customer->fetch_assoc()) :
							?>
								<tr>
									<td scope="row"><?php echo $i++ ?></td>
									<td class="">
										<p>Name : <b><?php echo $row['name'] ?></b></p>
										<p><small>Contact : <b><?php echo $row['contact'] ?></b></small></p>
										<p><small>Address : <b><?php echo $row['address'] ?></b></small></p>
									</td>
									<td scope="row">
										<button class="btn btn-sm btn-primary edit_customer mb-2" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-contact="<?php echo $row['contact'] ?>" data-address="<?php echo $row['address'] ?>">Edit</button>
										<button class="btn btn-sm btn-danger delete_customer mb-2" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
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
	$('table').dataTable()
	$('#manage-customer').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_customer',
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

				}
			}
		})
	})
	$('.edit_customer').click(function() {
		start_load()
		var cat = $('#manage-customer')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='name']").val($(this).attr('data-name'))
		cat.find("[name='contact']").val($(this).attr('data-contact'))
		cat.find("[name='address']").val($(this).attr('data-address'))
		end_load()
	})
	$('.delete_customer').click(function() {
		_conf("Are you sure to delete this customer?", "delete_customer", [$(this).attr('data-id')])
	})

	function delete_customer($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_customer',
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

				}
			}
		})
	}
</script>