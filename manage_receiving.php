<?php include 'db_connect.php';
// session_start();
// $dbName = $_SESSION['shop_db'];
if (isset($_GET['id'])) {
	$qry = shop_conn($dbName)->query("SELECT * FROM receiving_list where id=" . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $val) {
		$$k = $val;
	}
	$inv = shop_conn($dbName)->query("SELECT * FROM inventory where type=1 and form_id=" . $_GET['id']);
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form action="" id="manage-receiving">
			<div class="card">
				<div class="card-header">
					<h4>Manage Receiving</h4>
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
									$supplier = shop_conn($dbName)->query("SELECT * FROM supplier_list order by supplier_name asc");
									while ($row = $supplier->fetch_assoc()) :
										$cus_arr[$row['id']] = $row['supplier_name'];
										if (!isset($_GET['id'])) : ?>
											<option value="" selected=""></option>
											<option value="<?php echo $row['id'] ?>"><?php echo $row['supplier_name'] ?></option>
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
								<select name="" id="product" class="form-select browser-default select2">
									<option value=""></option>
									<?php
									$conn->select_db('central_db');
									$cat = $conn->query("SELECT * FROM category_list order by name asc");
									while ($row = $cat->fetch_assoc()) :
										$cat_arr[$row['id']] = $row['name'];
									endwhile;
									$product = shop_conn($dbName)->query("SELECT * FROM product_list  order by name asc");
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
															<buttob class="btn btn-sm btn-danger" onclick="rem_list($(this))"><i class="fa fa-trash"></i></buttob>
														</td>
													</tr>
												<?php endwhile; ?>
											<?php endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<th colspan="3">Total</th>
												<th class=" tamount"></th>
												<th><input type="hidden" name="tamount" value=""></th>
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
		<tr class="item-row">
			<td>
				<input type="hidden" name="inv_id[]" value="">
				<input type="hidden" name="product_id[]" value="">
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
	</table>
</div>
</div>
<style type="text/css">
	#tr_clone {
		display: none;
	}

	td {
		vertical-align: middle;
	}

	td p {
		margin: unset;
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
		$('.select2').select2({
			placeholder: "Please select here",
			width: "100%"
		})
		if ('<?php echo isset($id) ?>' == 1) {
			$('[name="supplier_id"]').val('<?php echo isset($supplier_id) ? $supplier_id : '' ?>').select2({
				placeholder: "Please select here",
				width: "100%"
			})
			calculate_total()
		}
	})

	function rem_list(_this) {
		_this.closest('tr').remove()
	}
	$('#add_list').click(function() {
		// alert("TEST");
		// return false;
		// //console.log("Start");

		var tr = $('#tr_clone tr.item-row').clone();
		var product = $('#product').val(),
			qty = $('#qty').val(),
			// Get the value of data-price attribute from the selected option
			price = $('#product option:selected').data('price'),
			b_price = $('#b_price').val();
		if ($('#list').find('tr[data-id="' + product + '"]').length > 0) {
			alert_toast("Product already on the list", 'danger')
			return false;
		}
		if (product == '' || qty == '') {
			alert_toast("Please complete the fields first", 'danger')
			return false;
		}
		tr.attr('data-id', product)
		tr.find('.pname b').html($("#product option[value='" + product + "']").attr('data-name'))
		tr.find('.pdesc b').html($("#product option[value='" + product + "']").attr('data-description'))
		tr.find('[name="product_id[]"]').val(product)
		tr.find('[name="qty[]"]').val(qty)
		tr.find('[name="price[]"]').val(price)
		tr.find('[name="b_price[]"]').val(b_price)
		var amount = parseFloat(b_price) * parseFloat(qty);
		tr.find('.amount').html(parseFloat(amount).toLocaleString('en-US', {
			style: 'decimal',
			maximumFractionDigits: 2,
			minimumFractionDigits: 2
		}))
		$('#list tbody').append(tr)
		calculate_total()
		$('[name="qty[]"],[name="b_price[]"]').keyup(function() {
			calculate_total()
		})
		$('#product').val('').select2({
			placeholder: "Please select here",
			width: "100%"
		})
		$('#qty').val('')
		$('#b_price').val('')
	})

	function calculate_total() {
		var total = 0;
		$('#list tbody').find('.item-row').each(function() {
			var _this = $(this).closest('tr')
			var amount = parseFloat(_this.find('[name="qty[]"]').val()) * parseFloat(_this.find('[name="b_price[]"]').val());
			amount = amount > 0 ? amount : 0;
			_this.find('p.amount').html(parseFloat(amount).toLocaleString('en-US', {
				style: 'decimal',
				maximumFractionDigits: 2,
				minimumFractionDigits: 2
			}))
			total += parseFloat(amount);
		})
		$('#list [name="tamount"]').val(total)
		$('#list .tamount').html(parseFloat(total).toLocaleString('en-US', {
			style: 'decimal',
			maximumFractionDigits: 2,
			minimumFractionDigits: 2
		}))
	}
	$('#manage-receiving').submit(function(e) {
		e.preventDefault()
		start_load()
		if ($("#list .item-row").length <= 0) {
			alert_toast("Please insert atleast 1 item first.", 'danger');
			end_load();
			return false;
		}
		$.ajax({
			url: 'ajax.php?action=save_receiving',
			method: 'POST',
			data: $(this).serialize(),
			success: function(resp) {
				// console.log(resp)
				if (resp == 1) {
					alert_toast("Data successfully added", 'success')
					setTimeout(function() {
						location.href = "index.php?page=receiving"
					}, 1500)

				}

			}
		})
	})
</script>