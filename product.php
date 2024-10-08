<?php include('db_connect.php');
$sku = mt_rand(1, 99999999);
$sku = sprintf("%'.08d\n", $sku);
$i = 1;
while ($i == 1) {
	$chk = shopConn($dbName)->query("SELECT * FROM products where sku ='$sku'")->num_rows;
	if ($chk > 0) {
		$sku = mt_rand(1, 99999999);
		$sku = sprintf("%'.08d\n", $sku);
	} else {
		$i = 0;
	}
}
?>
<script>
	$(document).ready(function() {
		$('table').dataTable()

		$(document).on('click', 'img', function() {
			var imgSrc = $(this).attr('src');
			image_modal(imgSrc);
		});

		// Call updateProducts on page load
		updateProducts('');

		// Attach change event handler to category dropdown
		$('#category').change(function() {
			updateProducts('');
		});

		// Attach change event handler to product dropdown
		$(document).on('change', '#product_name', function() {
			updateImage();
		});
	});

	function updateProducts(name) {
		var categoryId = $('#category').val();
		$.ajax({
			url: 'get_products.php',
			type: 'POST',
			data: {
				category_id: categoryId
			},
			success: function(response) {
				$('#product_name').html(response);
				updateImage(); // Call updateImage after updating products
				// Perform check and select the matching option
				var targetValue = name; // Set this to the value you want to match
				$('#product_name option').each(function() {
					if ($(this).val() === targetValue) {
						console.log($(this).val())
						$(this).prop('selected', true);
						return false; // Exit the loop once the match is found
					}
				});
				// Ensure the change event is triggered after setting the option
				$('#product_name').trigger('change');
			}

		});
	}

	function updateImage() {
		var selectedOption = $('#product_name option:selected');
		var imgSrc = selectedOption.data('img');
		// console.log(imgSrc)
		$('#product_img').attr('src', imgSrc);
	}

	// ('#product_name').change(function() {
	// 	updateImage();
	// });
</script>
<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4 mb-3">
				<form action="" id="manage-product" class="needs-validation" novalidate>
					<div class="card">
						<div class="card-header">
							<h5><i class="fas fa-edit"></i> Product Form</h5>
						</div>
						<div class="card-body">
							<input type="hidden" name="id">
							<div class="mb-3">
								<label class="form-label">SKU</label>
								<input type="text" class="form-control" name="sku" value="<?php echo $sku ?>">
							</div>
							<div class="mb-3">
								<label class="form-label">Category</label>
								<select name="category_id" id="category" class="custom-select browser-default form-select">
									<?php
									$conn->select_db('central_db');
									$cat = $conn->query("SELECT * FROM categories ORDER BY name ASC");
									while ($row = $cat->fetch_assoc()) {
										$cat_arr[$row['id']] = $row['name'];
										echo "<option value='{$row['id']}'>{$row['name']}</option>";
									}
									?>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Product Name</label>
								<select name="product_name" id="product_name" class="custom-select browser-default form-select" onchange="updateImage()" required>
									<!-- Product options will be populated here based on the selected category -->
									<option value=""><?php echo $row['name'] ?></option>
								</select>
								<div class="invalid-feedback">
									Select a category which has product.
								</div>
							</div>
							<div id="product-img-container">
								<img id="product_img" name="product_img" src="" alt="Product Image" style="width: 150px; height: 100px;">
								<input type="text" name="img" id="img" hidden>
							</div>
							<div class="mb-3">
								<label class="form-label">Description</label>
								<textarea class="form-control" cols="30" rows="3" name="description"></textarea>
							</div>
							<div class="mb-3">
								<label class="form-label">M.R.P</label>
								<input type="number" step="any" class="form-control" name="price" required>
								<div class="invalid-feedback">
									Please Enter the M.R.P for product.
								</div>
							</div>
							<div class="mb-3" style="display: none;">
								<label class="form-label">Bill Price</label>
								<input type="number" step="any" class="form-control" name="b_price">
							</div>
							<div class="mb-3">
								<label class="form-label">Last Price</label>
								<input type="number" class="form-control" step="any" name="l_price" required>
								<div class="invalid-feedback">
									Please Enter Last price for the product.
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<?php if ($_SESSION['login_type'] == 1) : ?>
										<button class="btn btn-sm btn-primary col-sm-4 offset-md-1 mb-2 me-2"> Save</button>
										<button class="btn btn-sm btn-danger col-sm-4 offset-md-1 mb-2" type="button" onclick="$('#manage-product').trigger('reset'); $('#product_img').attr('src', '')"> Cancel</button>
									<?php endif; ?>
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
						<h4><b><i class="fa fa-box-open"></i> Product List</b></h4>
					</div>
					<div class="card-body">
						<div class="table-responsive-sm">
							<table class="table table-striped table-bordered border-warning table-info">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Image</th>
										<th scope="col">Product Info</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									$prod = shopConn($dbName)->query("SELECT * FROM products order by id asc");
									while ($row = $prod->fetch_assoc()) :
									?>
										<tr>
											<td scope="row"><?php echo $i++ ?></td>
											<td style="width: 150px; height: 100px;"><img src="<?php echo $row['img_path'] != '' ? 'assets/img/' . $row['img_path'] : 'assets/img/1600398180_no-image-available.png' ?>" alt="" width="100%" length="100"></td>
											<td>
												<p>SKU : <b><?php echo $row['sku'] ?></b></p>
												<p><small>Category : <b><?php echo $cat_arr[$row['category_id']] ?></b></small></p>
												<p><small>Name : <b><?php echo $row['name'] ?></b></small></p>
												<p><small>Description : <b><?php echo $row['description'] ?></b></small></p>
												<p><small>M.R.P : <b><?php echo number_format($row['price'], 2) ?></b></small></p>
												<p><small>Bill Price : <b><?php echo number_format($row['b_price'], 2) ?></b></small></p>
												<p><small>Last Price : <b><?php echo number_format($row['l_price'], 2) ?></b></small></p>
											</td>
											<?php if ($_SESSION['login_type'] == 1) : ?>
												<td class="text-center product-btn">
													<button class="btn btn-sm btn-primary edit_product mb-2" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-sku="<?php echo $row['sku'] ?>" data-category_id="<?php echo $row['category_id'] ?>" data-description="<?php echo $row['description'] ?>" data-price="<?php echo $row['price'] ?>" data-bprice="<?php echo $row['b_price'] ?>" data-lprice="<?php echo $row['l_price'] ?>" data-img="<?php echo $row['img_path'] ?>">Edit</button>
													<button class="btn btn-sm btn-danger delete_product mb-2" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
												</td>
											<?php else : ?>
												<td>

												</td>
											<?php endif; ?>

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
		'use strict';

		$('#manage-product').each(function() {
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

				var imgSrc = $('#product_img').attr('src'); // Get the src attribute of the img element
				$('#img').val(imgSrc); // Set the value of the hidden input field

				$.ajax({
					url: 'ajax.php?action=save_product',
					data: new FormData($(this)[0]),
					cache: false,
					contentType: false,
					processData: false,
					method: 'POST',
					type: 'POST',
					success: function(resp) {
						console.log(resp)
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
		$(document).on('click', '.edit_product', function() {
			start_load()
			var cat = $('#manage-product')
			cat.trigger('reset')
				.find("[name='id']").val($(this).data('id')).end()
				.find("[name='product_name']").val($(this).data('name')).end()
				.find("[name='product_img']").val($(this).data('img')).end()
				.find("[name='sku']").val($(this).data('sku')).end()
				.find("[name='category_id']").val($(this).data('category_id')).end()
				.find("[name='description']").val($(this).data('description')).end()
				.find("[name='price']").val($(this).data('price')).end()
				.find("[name='b_price']").val($(this).data('bprice')).end()
				.find("[name='l_price']").val($(this).data('lprice')).end();
			updateProducts($(this).data('name'))
			end_load()
		})
		$(document).on('click', '.delete_product', function() {
			_conf("Are you sure to delete this product?", "delete_product", [$(this).data('id')])
		})
	});

	function delete_product($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_product',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				console.log(resp);
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