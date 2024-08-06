<?php include 'db_connect.php';
// session_start();
// $dbName = $_SESSION['shop_db'];
if (isset($_GET['id'])) {
	$qry = shopConn($dbName)->query("SELECT * FROM receiving where id=" . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $val) {
		$$k = $val;
	}
	$inv = shopConn($dbName)->query("SELECT * FROM inventory where type=1 and form_id=" . $_GET['id']);
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form action="" id="manage-receiving" method="POST" enctype="multipart/form-data">
			<div class="card">
				<div class="card-header">
					<h4>Manage Purchase</h4>
				</div>
				<div class="card-body">
					<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
					<input type="hidden" name="ref_no" value="<?php echo isset($ref_no) ? $ref_no : '' ?>">
					<div class="col-md-12">
						<div class="row">
							<div class="mb-3 col-md-5">
								<label class="form-label">Supplier</label>
								<select name="supplier_id" id="" class="form-select browser-default select2">
									<?php
									$supplier = shopConn($dbName)->query("SELECT * FROM suppliers order by name asc");
									while ($row = $supplier->fetch_assoc()) :
										$cus_arr[$row['id']] = $row['name'];
										if (!isset($_GET['id'])) : ?>
											<option value="" selected=""></option>
											<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
									<?php
										endif;
									endwhile; ?>
									<?php
									$cus_arr[0] = "";
									if (isset($cus_arr[$supplier_id])) { ?>
										<?php echo $cus_arr[$supplier_id]; ?>
										<option value="<?php echo $supplier_id ?>" selected><?php echo $cus_arr[$supplier_id] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<hr>
						<div class="row mb-3">
							<div class="col-md-2">
								<label class="form-label">Product</label>
								<select name="" id="product" class="form-select select2">
									<option value=""></option>
									<?php
									$conn->select_db('central_db');
									$cat = $conn->query("SELECT * FROM categories order by name asc");
									while ($row = $cat->fetch_assoc()) :
										$cat_arr[$row['id']] = $row['name'];
									endwhile;
									$product = shopConn($dbName)->query("SELECT * FROM products order by name asc");
									while ($row = $product->fetch_assoc()) :
										$prod[$row['id']] = $row;

									?>
										<option value="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-description="<?php echo $row['description'] ?>" data-price="<?php echo $row['price'] ?>"><?php echo $row['name'] . ' | ₹' . $row['price'] . ' | ' . $row['sku']  ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="col-md-2">
								<label class="form-label">Qty</label>
								<input type="number" class="form-control " step="any" id="qty">
							</div>
							<div class="col-md-2" style="display: none;">
								<label class="form-label">M.R.P</label>
								<input type="number" class="form-control " step="any" id="price">
							</div>
							<div class="col-md-2">
								<label class="form-label">Bill Price</label>
								<input type="number" class="form-control " step="any" id="b_price">
							</div>
							<div class="col-md-2">
								<label class="form-label">&nbsp</label>
								<button class="btn btn-block btn-sm btn-primary form-control" type="button" id="add_list"><i class="fa fa-plus"></i> Add to List</button>
							</div>
							<?php if (!isset($_GET['id'])) : ?>
								<div class="col-md-2 save_image">
									<label for="img" class="form-label" style=" text-align:left">Shop Image</label>
									<input type="file" name="img" id="img" class="form-control" accept=".jpg, .jpeg, .png, .gif">
								</div>
							<?php else : ?>
								<div class="col-md-2 edit_image" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to Preview">
									<img id="recieving_img" name="recieving_img" src="<?php echo $img_path != '' ? 'assets/img/' . $img_path : 'assets/img/1600398180_no-image-available.png' ?>" alt="Bill Image" style="width: 150px; height: 100px;">
								</div>
							<?php endif; ?>

						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive-sm">
									<table class="table table-striped table-bordered border-warning table-info table-hover" id="list">
										<colgroup>
											<col width="25%">
											<col width="10%">
											<col width="25%">
											<col width="25%">
											<col width="10%">
											<col width="5%">
										</colgroup>
										<thead>
											<tr>
												<th scope="col">Product</th>
												<th scope="col">Qty</th>
												<th scope="col">M.R.P</th>
												<th scope="col">Bill Price</th>
												<th scope="col">Amount</th>
												<th scope="col"></th>
											</tr>
										</thead>
										<tbody>
											<?php
											if (isset($id)) :
												while ($row = $inv->fetch_assoc()) :
													foreach (json_decode($row['other_details']) as $k => $v) {
														$row[$k] = $v;
													}
											?>
													<tr class="item-row">
														<td>
															<input type="hidden" name="inv_id[]" value="<?php echo $row['id'] ?>">
															<input type="hidden" name="product_id[]" value="<?php echo $row['product_id'] ?>">
															<input type="hidden" name="s_price[]" value="<?php echo isset($row['s_price']) ? $row['s_price'] : '' ?>">
															<p class="pname">Name: <b><?php echo $prod[$row['product_id']]['name'] ?></b></p>
															<p class="pdesc"><small><i>Description: <b><?php echo $prod[$row['product_id']]['description'] ?></b></i></small></p>
														</td>
														<td style="min-width: 80px;">
															<input type="number" min="1" step="any" name="qty[]" value="<?php echo $row['qty'] ?>" required>
														</td>
														<td>
															<input type="number" min="1" step="any" name="price[]" value="<?php echo $row['price'] ?>" required>
														</td>
														<td>
															<input type="number" min="1" step="any" name="b_price[]" value="<?php echo $row['b_price'] ?>" required>
														</td>
														<td>
															<p class="amount "></p>
														</td>
														<td scope="col">
															<buttob class="btn btn-sm btn-danger rem-list"><i class="fa fa-trash"></i></buttob>
														</td>
													</tr>
												<?php endwhile; ?>
											<?php endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<th colspan="3">Total</th>
												<th><input type="hidden" name="total_amount" value=""></th>
												<th class=" total_amount"></th>
												<th></th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-primary btn-sm w-100">Save</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="tr_clone">
	<table>
		<tbody>
			<tr class="item-row">
				<td>
					<input type="hidden" name="inv_id[]" value="">
					<input type="hidden" name="product_id[]" value="">
					<input type="hidden" name="s_price[]" value="">
					<p class="pname">Name: <b>product</b></p>
					<p class="pdesc"><small><i>Description: <b>Description</b></i></small></p>
				</td>
				<td style="min-width: 80px;">
					<input type="number" min="1" step="any" name="qty[]" value="" class="">
				</td>
				<td>
					<input type="number" min="1" step="any" name="price[]" value="" class="">
				</td>
				<td>
					<input type="number" min="1" step="any" name="b_price[]" value="" class="">
				</td>
				<td>
					<p class="amount "></p>
				</td>
				<td scope="col">
					<buttob class="btn btn-sm btn-danger" onclick="rem_list($(this))"><i class="fa fa-trash"></i></buttob>
				</td>
			</tr>
		</tbody>
	</table>
</div>
</div>
<style type="text/css">
	#tr_clone {
		display: none;
	}

	td input[type='number'] {
		height: calc(100%);
		width: calc(100%);

	}

	input[type=number]::-webkit-inner-spin-button,
	input[type=number]::-webkit-outer-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
</style>
<script>
	$(document).ready(function() {

		$(document).on('click', 'img', function() {
			var imgSrc = $(this).attr('src');
			image_modal(imgSrc);
		});

		$('.select2').select2({
			placeholder: "Please select here",
			width: "100%"
		})
		if ('<?php echo isset($id) ?>' == 1) {
			$('[name="supplier_id"]').val('<?php echo isset($supplier_id) ? $supplier_id : '' ?>').select2({
				placeholder: "Please select here",
				width: "100%"
			})
			calculate_total();
		}
		$('#list').on('click', '.rem-list', function() {
			rem_list($(this));
		});

		$('#add_list').click(function() {
			// alert("TEST");
			// return false;
			// //console.log("Start");

			var tr = $('#tr_clone tr.item-row').clone();
			var product = $('#product').val();
			var qty = $('#qty').val();
			// Get the value of data-price attribute from the selected option
			var price = $('#product option:selected').data('price');
			var b_price = $('#b_price').val();
			var s_price = $('#s_price').val();

			if ($('#list').find('tr[data-id="' + product + '"]').length > 0) {
				alert_toast("Product already on the list", 'danger')
				return false;
			}

			if (!product || !qty) {
				alert_toast("Please complete the fields first", 'danger')
				return false;
			}

			tr.attr('data-id', product)
				.find('.pname b').text($("#product option[value='" + product + "']").data('name')).end()
				.find('.pdesc b').text($("#product option[value='" + product + "']").data('description')).end()
				.find('[name="product_id[]"]').val(product).end()
				.find('[name="qty[]"]').val(qty).end()
				.find('[name="price[]"]').val(price).end()
				.find('[name="b_price[]"]').val(b_price).end()
				.find('[name="s_price[]"]').val(s_price).end()

			var amount = parseFloat(b_price) * parseFloat(qty);
			tr.find('.amount').text(amount.toLocaleString('en-US', {
				style: 'decimal',
				maximumFractionDigits: 2,
				minimumFractionDigits: 2
			}))

			$('#list tbody').append(tr)
			calculate_total()

			$('[name="qty[]"],[name="b_price[]"]').on('keyup', calculate_total)

			$('#product').val('').select2({
				placeholder: "Please select here",
				width: "100%"
			})
			$('#qty').val('')
			$('#b_price').val('')
		})

		$('#manage-receiving').submit(function(e) {
			e.preventDefault()
			start_load()

			var fileInput = $('#img')[0]; // Access the DOM element
			if (fileInput != undefined) {
				var file = fileInput.files[0];

				if (file && file.size > 2 * 1024 * 1024) { // 2MB in bytes
					alert_toast('The selected file is too large. Please select a file less than 2MB.', 'warning');
					end_load()
					return false;
				}
			}

			if ($("#list .item-row").length <= 0) {
				alert_toast("Please insert atleast 1 item first.", 'danger');
				end_load();
				return false;
			}
			console.log($('[name="total_amount"]').val())
			// Collect form data
			var formData = new FormData($(this)[0]);
			var urlEncodedData = new URLSearchParams(formData).toString();

			var conf_msg = "Are you sure to pay " + $('.total_amount').val() + " to receiver ? "
			console.log(conf_msg)
			// Call _conf with the URL-encoded form data as a parameter
			_conf("Are you sure to pay  <b><u> ₹" + $('[name="total_amount"]').val() + "</u></b>  to receiver ? ", "pay_receiver", [urlEncodedData]);

		})
	})

	function rem_list(element) {
		element.closest('tr').remove();
		calculate_total();
	};

	function calculate_total() {
		var total = 0;
		var amount = 0;
		$('#list tbody .item-row').each(function() {
			const _this = $(this).closest('tr')
			var amount = parseFloat(_this.find('[name="qty[]"]').val()) * parseFloat(_this.find('[name="b_price[]"]').val());
			_this.find('p.amount').text(parseFloat(amount).toLocaleString('en-US', {
				style: 'decimal',
				maximumFractionDigits: 2,
				minimumFractionDigits: 2
			}))
			total += amount;
		})
		$('[name="total_amount"]').val(total)
		$('#list .total_amount').text(parseFloat(total).toLocaleString('en-US', {
			style: 'decimal',
			maximumFractionDigits: 2,
			minimumFractionDigits: 2
		}))
	}


	function pay_receiver(urlEncodedData) {
		start_load();
		$.ajax({
			url: 'ajax.php?action=save_receiving',
			data: urlEncodedData,
			processData: false, // Important to not process data
			contentType: 'application/x-www-form-urlencoded', // Specify content type, // Important to not set content type
			method: 'POST',
			success: function(resp) {
				console.log(resp);
				if (resp == 1) {
					alert_toast("Data successfully added", 'success');
					setTimeout(function() {
						location.href = "index.php?page=receiving";
					}, 1500);
				} else {
					alert_toast("Data successfully updated", 'success');
					setTimeout(function() {
						location.href = "index.php?page=receiving";
					}, 1500);
				}
			}
		});
	}
</script>