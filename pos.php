<?php include 'db_connect.php';

if (isset($_GET['id'])) {
	$qry = shop_conn($dbName)->query("SELECT * FROM sales_list where id=" . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $val) {
		$$k = $val;
	}
	$inv = shop_conn($dbName)->query("SELECT * FROM inventory where type=2 and form_id=" . $_GET['id']);
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h4>Sales</h4>
			</div>
			<div class="card-body">
				<form action="" id="manage-sales">
					<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
					<input type="hidden" name="ref_no" value="<?php echo isset($ref_no) ? $ref_no : '' ?>">
					<div class="col-md-12">
						<div class="row">
							<div class="mb-3 col-md-5">
								<label class="form-label">Customer</label>
								<select name="customer_id" id="" class="form-select browser-default select2">
									<?php
									$customer = shop_conn($dbName)->query("SELECT * FROM customer_list order by name asc");
									while ($row = $customer->fetch_assoc()) :
										$cus_arr[$row['id']] = $row['name'];
										if (!isset($_GET['id'])) : ?>
											<option value="0" selected="">Guest</option>
											<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
									<?php
										endif;
									endwhile; ?>
									<?php
									$cus_arr[0] = "GUEST";
									if (isset($cus_arr[$customer_id])) { ?>
										<?php echo $cus_arr[$customer_id]; ?>
										<option value="<?php echo $cus_arr[$customer_id] ?>"><?php echo $cus_arr[$customer_id] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<hr>
						<div class="row mb-3">
							<div class="col-md-4">
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
										<option value="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-description="<?php echo $row['description'] ?>"><?php echo '₹' . $row['price'] . ' | ' . $row['name'] . ' | ' . $row['sku'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="col-md-2">
								<label class="form-label">Qty</label>
								<input type="number" class="form-control" step="any" id="qty">
							</div>
							<div class="col-md-2">
								<label class="form-label">Price</label>
								<input type="number" class="form-control" step="any" id="s_price">
							</div>
							<div class="col-md-3">
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
												<th scope="col">Price</th>
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
															<input type="number" minlength="4" size="4" name="qty[]" value="<?php echo $row['qty'] ?>" class="form-control" required>
														</td>
														<td>
															<input type="hidden" min="1" step="any" name="price[]" value="<?php echo $row['price'] ?>" class="">
															<p class=""><?php echo $row['price'] ?></p>
														</td>
														<input type="hidden" min="1" step="any" name="b_price[]" value="<?php echo $row['b_price'] ?>" class="">

														<td>
															<input type="hidden" min="1" step="any" name="s_price[]" value="<?php echo $row['s_price'] ?>" class="">
															<p class=""><?php echo $row['s_price'] ?></p>
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
												<th class="" colspan="3">Total</th>
												<th></th>
												<th class=" tamount"></th>
												<th></th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<button class="btn btn-primary btn-sm btn-block " type="button" id="pay">Pay</button>

							<!-- <button class="btn btn-primary btn-sm btn-block " type="button" id="credit">Credit</button> -->
						</div>
					</div>
					<div class="modal fade" id="pay_modal" role='dialog'>
						<div class="modal-dialog modal-md" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title"></h5>
								</div>
								<div class="modal-body">
									<div class="container-fluid">
										<input type="text" name="aamount" value="" class="form-control " readonly="" hidden>

										<div class="mb-3">
											<label for="" class="form-label">Total Amount</label>
											<input type="text" name="tamount" value="" class="form-control " readonly="">
										</div>
										<div class="mb-3">
											<label for="" class="form-label">Amount Tendered</label>
											<input type="number" name="amount_tendered" value="0" min="0" class="form-control" required>
										</div>
										<div class="mb-3">
											<label for="" class="form-label">Payment mode</label>
											<select name="paymode" id="paymode" class="form-select browser-default select2" required>
												<option value=""></option>
												<option value="1">Cash or online</option>
												<option value="2">Credit</option>
											</select>
										</div>
										<div class="mb-3">
											<label for="" class="form-label">Change</label>
											<input type="number" name="change" value="0" min="0" class="form-control " readonly="">
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary" id='submit' onclick="$('#manage-sales').submit()">Pay</button>
									<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
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
				<input type="number" minlength="4" name="qty[]" value="" class=" form-control">
			</td>
			<input type="hidden" min="1" step="any" name="b_price[]" value="" class="" readonly="">
			<td>
				<input type="hidden" min="1" step="any" name="price[]" value="" class="" readonly="">
				<p class="price ">0</p>
			</td>
			<td>
				<input type="number" min="1" step="any" name="s_price[]" value="" class="">
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
	$('#pay').click(function() {
		if ($("#list .item-row").length <= 0) {
			alert_toast("Please insert atleast 1 item first.", 'danger');
			end_load();
			return false;
		}
		$('#pay_modal').modal('show')
	})
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

		var tr = $('#tr_clone tr.item-row').clone();
		var product = $('#product').val(),
			qty = $('#qty').val(),
			s_price = $('#s_price').val();
		if ($('#list').find('tr[data-id="' + product + '"]').length > 0) {
			alert_toast("Product already on the list", 'danger')
			return false;
		}

		if (product == '' || qty == '') {
			alert_toast("Please complete the fields first", 'danger')
			return false;
		}
		$.ajax({
			url: 'ajax.php?action=chk_prod_availability',
			method: 'POST',
			data: {
				id: product
			},
			success: function(resp) {
				resp = JSON.parse(resp);
				if (resp.available >= qty) {
					tr.attr('data-id', product)
					tr.find('.pname b').html($("#product option[value='" + product + "']").attr('data-name'))
					tr.find('.pdesc b').html($("#product option[value='" + product + "']").attr('data-description'))
					tr.find('.price').html(resp.price)
					tr.find('[name="product_id[]"]').val(product)
					tr.find('[name="qty[]"]').val(qty)
					tr.find('[name="price[]"]').val(resp.price)
					tr.find('[name="s_price[]"]').val(s_price)
					tr.find('[name="b_price[]"]').val(resp.b_price)
					var amount = parseFloat(s_price) * parseFloat(qty);
					tr.find('.amount').html(parseFloat(amount).toLocaleString('en-US', {
						style: 'decimal',
						maximumFractionDigits: 2,
						minimumFractionDigits: 2
					}))
					if (parseFloat(s_price) <= parseFloat(resp.price) && parseFloat(s_price) >= parseFloat(resp.b_price)) {
						$('#list tbody').append(tr)
						calculate_total()
						$('[name="qty[]"],[name="s_price[]"],[name="b_price[]"]').keyup(function() {
							calculate_total()
						})
						$('#product').val('').select2({
							placeholder: "Please select here",
							width: "100%"
						})
						$('#qty').val('')
						$('#s_price').val('')
					} else {
						alert_toast("You enter a wrong price.", 'danger')
					}
				} else {
					alert_toast("Product quantity is greater than available stock.", 'danger')
				}
			}
		})

	})

	function calculate_total() {
		var total = 0;
		var actual = 0;
		$('#list tbody').find('.item-row').each(function() {
			var _this = $(this).closest('tr')
			var amount = parseFloat(_this.find('[name="qty[]"]').val()) * parseFloat(_this.find('[name="s_price[]"]').val());
			var aAmount = parseFloat(_this.find('[name="qty[]"]').val()) * parseFloat(_this.find('[name="b_price[]"]').val());
			amount = amount > 0 ? amount : 0;
			aAmount = aAmount > 0 ? aAmount : 0;
			_this.find('p.amount').html(parseFloat(amount).toLocaleString('en-US', {
				style: 'decimal',
				maximumFractionDigits: 2,
				minimumFractionDigits: 2
			}))
			total += parseFloat(amount);
			actual += parseFloat(aAmount);
		})
		$('[name="tamount"]').val(total)
		$('[name="aamount"]').val(actual)
		$('#list .tamount').html(parseFloat(total).toLocaleString('en-US', {
			style: 'decimal',
			maximumFractionDigits: 2,
			minimumFractionDigits: 2
		}))
	}
	$('[name="amount_tendered"]').keyup(function() {
		var tendered = $(this).val();
		var tamount = $('[name="tamount"]').val();
		$('[name="change"]').val(parseFloat(tendered) - parseFloat(tamount))

	})
	$('#manage-sales').submit(function(e) {
		e.preventDefault()
		start_load()
		if ($("#list .item-row").length <= 0) {
			alert_toast("Please insert atleast 1 item first.", 'danger');
			end_load();
			return false;
		}
		$.ajax({
			url: 'ajax.php?action=save_sales',
			method: 'POST',
			data: $(this).serialize(),
			success: function(resp) {
				//console.log(resp)
				if (resp > 0) {
					end_load()
					alert_toast("Data successfully submitted", 'success')
					uni_modal('Print', "print_sales.php?id=" + resp)
					$('#uni_modal').modal({
						backdrop: 'static',
						keyboard: false
					})

				}

			}
		})
	})
</script>