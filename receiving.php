<?php include 'db_connect.php' ?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<button type="button" class="btn btn-outline-primary w-100" id="new_receiving"><i class="fa fa-plus"></i> New Purchase</button>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4><b><i class="fa fa-shopping-basket"></i> Purchases</b></h4>
					</div>
					<div class="card-body">
						<div class="table-responsive-sm">
							<table class="table table-striped table-bordered border-warning table-info">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Date</th>
										<th scope="col">Reference #</th>
										<th scope="col">Supplier</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$supplier = shopConn($dbName)->query("SELECT * FROM suppliers order by name asc");
									while ($row = $supplier->fetch_assoc()) :
										$sup_arr[$row['id']] = $row['name'];
									endwhile;
									$i = 1;
									$receiving = shopConn($dbName)->query("SELECT * FROM receiving r order by date(date_updated) desc");
									while ($row = $receiving->fetch_assoc()) :
									?>
										<tr>
											<td scope="row"><?php echo $i++ ?></td>
											<td><?php echo date("h:i A | d-M-Y", strtotime($row['date_updated'])) ?></td>
											<td><?php echo $row['ref_no'] ?></td>
											<td><?php echo isset($sup_arr[$row['supplier_id']]) ? $sup_arr[$row['supplier_id']] : 'N/A' ?></td>
											<td scope="row" class="receiving-btn">
												<a class="btn btn-sm btn-primary mb-2" href="index.php?page=manage_receiving&id=<?php echo $row['id'] ?>">Edit</a>
												<a class="btn btn-sm btn-danger delete_receiving mb-2" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
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
	$('#new_receiving').click(function() {
		location.href = "index.php?page=manage_receiving"
	})
	$(document).on('click', '.delete_receiving', function() {
		_conf("Are you sure to delete this receiving data?", "delete_receiving", [$(this).data('id')])
	})

	function delete_receiving($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_receiving',
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
					alert_toast("An error occurred. Please try again.", 'danger');
					setTimeout(function() {
						location.reload()
					}, 1500)
				}
			}
		})
	}
</script>