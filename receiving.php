<?php include 'db_connect.php' ?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<button type="button" class="btn btn-outline-primary w-100" id="new_receiving"><i class="fa fa-plus"></i> New Receiving</button>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
					<h4><b>Receiving</b></h4>
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
									$supplier = shop_conn($dbName)->query("SELECT * FROM supplier_list order by supplier_name asc");
									while ($row = $supplier->fetch_assoc()) :
										$sup_arr[$row['id']] = $row['supplier_name'];
									endwhile;
									$i = 1;
									$receiving = shop_conn($dbName)->query("SELECT * FROM receiving_list r order by date(date_added) desc");
									while ($row = $receiving->fetch_assoc()) :
									?>
										<tr>
											<td scope="row"><?php echo $i++ ?></td>
											<td><?php echo date("M d, Y", strtotime($row['date_added'])) ?></td>
											<td><?php echo $row['ref_no'] ?></td>
											<td><?php echo isset($sup_arr[$row['supplier_id']]) ? $sup_arr[$row['supplier_id']] : 'N/A' ?></td>
											<td scope="row">
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
		_conf("Are you sure to delete this data?", "delete_receiving", [$(this).attr('data-id')])
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

				}
			}
		})
	}
</script>