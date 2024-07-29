<?php include 'db_connect.php' ?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
					<h4><b>Udhaar List</b></h4>
					</div>
					<div class="card-body">
						<div class="table-responsive-sm">
							<table class="table table-striped table-bordered border-warning table-info table-hover">
								<thead>
									<th scope="col">#</th>
									<th scope="col">Date</th>
									<th scope="col">Customer</th>
									<th scope="col">Amount</th>
									<th scope="col">Action</th>
								</thead>
								<tbody>
									<?php
									$customer = shop_conn($dbName)->query("SELECT * FROM customer_list order by name asc");
									while ($row = $customer->fetch_assoc()) :
										$cus_arr[$row['id']] = $row['name'];
									endwhile;
									$cus_arr[0] = "GUEST";

									$i = 1;
									$sales = shop_conn($dbName)->query("SELECT id, customer_id, SUM(total_amount) as total_amount , date_updated
                                    FROM sales_list 
                                    WHERE paymode = 2 
                                    GROUP BY customer_id 
                                    ORDER BY date(date_updated) DESC");
									while ($row = $sales->fetch_assoc()) :

									?>
										<tr>
											<td scope="row"><?php echo $i++ ?></td>
											<td class=""><?php echo date("M d, Y", strtotime($row['date_updated'])) ?></td>
											<td class=""><?php echo isset($cus_arr[$row['customer_id']]) ? $cus_arr[$row['customer_id']] : 'N/A' ?></td>
											<td class=""><?php echo number_format($row['total_amount'], 2); ?></td>
											<td scope="row">
												<a class="btn btn-sm btn-primary mb-2" href="index.php?page=creditlist&id=<?php echo $row['customer_id'] ?>">Edit</a>
												<a class="btn btn-sm btn-danger delete_sales mb-2" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
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
	$('#new_sales').click(function() {
		location.href = "index.php?page=pos"
	})
	$(document).on('click', '.delete_sales', function() {
		_conf("Are you sure to delete this data?", "delete_sales", [$(this).attr('data-id')])
	})

	function delete_sales($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_sales',
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