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
									<tr>
										<th scope="col">#</th>
										<th scope="col">Date</th>
										<th scope="col">Customer</th>
										<th scope="col">Amount</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Fetch customers
									$customerResult = shopConn($dbName)->query("SELECT * FROM customers ORDER BY name ASC");
									$cus_arr = [];
									while ($row = $customerResult->fetch_assoc()) {
										$cus_arr[$row['id']] = $row['name'];
									}
									$cus_arr[0] = "GUEST";

									// Fetch sales
									$i = 1;
									$salesResult = shopConn($dbName)->query("SELECT id, customer_id, SUM(amount_change) AS total_amount, date_updated FROM sales WHERE paymode = 2 GROUP BY customer_id ORDER BY DATE(date_updated) DESC ");
									while ($row = $salesResult->fetch_assoc()) :
									?>
										<tr>
											<td scope="row"><?php echo $i++; ?></td>
											<td><?php echo date("h:i A | d-M-Y", strtotime($row['date_updated'])); ?></td>
											<td><?php echo isset($cus_arr[$row['customer_id']]) ? $cus_arr[$row['customer_id']] : 'N/A'; ?></td>
											<td><?php echo (number_format($row['total_amount'], 2)* -1); ?></td>
											<td>
												<a class="btn btn-sm btn-primary mb-2" href="index.php?page=creditlist&id=<?php echo $row['customer_id']; ?>">Edit</a>
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
	});
</script>