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

								$product = shop_conn($dbName)->query("SELECT * FROM product_list r order by name asc");
								while ($row = $product->fetch_assoc()) :
									$low_stock = false;
									$out_of_stock = false;
									$inn = shop_conn($dbName)->query("SELECT sum(qty) as inn FROM inventory where type = 1 and product_id = " . $row['id']);
									$inn = $inn && $inn->num_rows > 0 ? $inn->fetch_array()['inn'] : 0;
									$out = shop_conn($dbName)->query("SELECT sum(qty) as `out` FROM inventory where type = 2 and product_id = " . $row['id']);
									$out = $out && $out->num_rows > 0 ? $out->fetch_array()['out'] : 0;
									$available = $inn - $out;

									if ($available == 0) {
										$out_of_stock = true;
									}
									if ($available <= 10 and $available != 0) {
										$low_stock = true;
									}
								?>
									<tr>
										<td class="align-middle" scope="row"><?php echo $i++ ?></td>
										<td style="width: 100px; height: 60px;"><img src="<?php echo $row['image'] != '' ?  $row['image'] : 'assets/img/1600398180_no-image-available.png' ?>" alt="" width="100%" length="100%"></td>
										<td class="align-middle"><?php echo $row['name'] ?></td>
										<td class="align-middle"><?php echo $inn ?></td>
										<td class="align-middle"><?php echo $out ?></td>
										<td class="align-middle"><?php echo $available ?></td>
										<td class="align-middle">
											<?php if (isset($low_stock) and $low_stock) {
												echo "<span class='btn btn-warning btn-sm rounded-circle mx-5 my-2'>
												⚠️ <!-- Warning emoji --> Low Stock 
											</span>";
											} elseif (isset($out_of_stock) and $out_of_stock) {
												echo "<span class='btn btn-danger btn-sm rounded-circle mx-5 my-2'>
												🚨 <!-- Alert emoji -->  Out of Stock
											</span>";
											} else {
												echo "<span class='btn btn-success btn-sm rounded-circle mx-5 my-2'>
												✅ <!-- Success emoji -->   Good Stock
											</span>";
											}
											?>
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
		$(document).on('click', 'img', function() {
            var imgSrc = $(this).attr('src');
            image_modal(imgSrc);
        });
	})
</script>