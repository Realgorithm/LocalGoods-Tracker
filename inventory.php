<?php include 'db_connect.php' ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<h4><b>Inventory</b></h4>
				</div>
				<div class="card-body">
					<div class="table-responsive-sm">
						<table class="table table-striped table-bordered border-warning table-info">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Image</th>
									<th scope="col">Product Name</th>
									<th scope="col">Stock In</th>
									<th scope="col">Stock Out</th>
									<th scope="col">Stock Available</th>
									<th scope="col">Stock status</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;

								// Fetch all products
								$productQuery = shopConn($dbName)->query("SELECT * FROM products ORDER BY name ASC");

								// Fetch inventory data in one query
								$inventoryQuery = shopConn($dbName)->query("SELECT product_id, SUM(CASE WHEN type = 1 THEN qty ELSE 0 END) AS inn, SUM(CASE WHEN type = 2 THEN qty ELSE 0 END) AS outt FROM inventory GROUP BY product_id ");

								// Create an associative array for quick lookup
								$inventoryData = [];
								while ($row = $inventoryQuery->fetch_assoc()) {
									$inventoryData[$row['product_id']] = $row;
								}

								while ($row = $productQuery->fetch_assoc()) :
									$productId = $row['id'];
									$inventory = $inventoryData[$productId] ?? ['inn' => 0, 'out' => 0];
									$inn = $inventory['inn'];
									$out = $inventory['outt'];
									$available = $inn - $out;

									// Determine stock status
									$stockStatus = 'Good Stock';
									$statusClass = 'btn-success';
									$statusEmoji = '✅';

									if ($available == 0) {
										$stockStatus = 'Out of Stock';
										$statusClass = 'btn-danger';
										$statusEmoji = '🚨';
									} elseif ($available <= 10) {
										$stockStatus = 'Low Stock';
										$statusClass = 'btn-warning';
										$statusEmoji = '⚠️';
									}
								?>
									<tr>
										<td class="align-middle" scope="row"><?php echo $i++ ?></td>
										<td style="width: 100px; height: 60px;" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to Preview" class="preview">
											<img src="<?php echo !empty($row['img_path']) ? 'assets/img/' . $row['img_path'] : 'assets/img/1600398180_no-image-available.png' ?>" alt="" width="100%">
										</td>
										<td class="align-middle"><?php echo htmlspecialchars($row['name']) ?></td>
										<td class="align-middle"><?php echo $inn ?></td>
										<td class="align-middle"><?php echo $out ?></td>
										<td class="align-middle"><?php echo $available ?></td>
										<td class="align-middle">
											<span class="btn btn-sm rounded-circle mx-5 my-2 <?php echo $statusClass ?>">
												<?php echo $statusEmoji ?> <!-- Emoji for stock status --> <?php echo $stockStatus ?>
											</span>
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

<script>
	$(document).ready(function() {
		$('table').dataTable()

		// $('[data-bs-toggle="popover"]').popover();
		$(document).on('click', 'img', function() {
			var imgSrc = $(this).attr('src');
			image_modal(imgSrc);
		});
	})
</script>