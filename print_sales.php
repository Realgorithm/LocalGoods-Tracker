<?php
include 'db_connect.php';
session_start();
$dbName = $_SESSION['shop_db'];
if (isset($_GET['id'])) {
	$qry = shop_conn($dbName)->query("SELECT * FROM sales_list where id=" . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $val) {
		$$k = $val;
	}
	$inv = shop_conn($dbName)->query("SELECT * FROM inventory where type=2 and form_id=" . $_GET['id']);
	if ($customer_id > 0) {
		$cname = shop_conn($dbName)->query("SELECT * FROM customer_list where id = $customer_id ");
		$cname = $cname->num_rows > 0 ? $cname->fetch_array()['name'] : "Guest";
	} else {
		$cname = "Guest";
	}
}
$product = shop_conn($dbName)->query("SELECT * FROM product_list  order by name asc");
while ($row = $product->fetch_assoc()) :
	$prod[$row['id']] = $row;
endwhile;

?>
<div class="container-fluid" id="print-sales">
	<style>
		table {
			border-collapse: collapse;
		}

		.wborder {
			border: 1px solid gray;
		}

		.bbottom {
			border-bottom: 1px solid black
		}

		td p,
		th p {
			margin: unset
		}

		.text-center {
			text-align: center
		}

		.text-right {
			text-align: right
		}

		.clear {
			padding: 10px
		}

		#uni_modal .modal-footer {
			display: none;
		}
	</style>


	<table width="100%">
		<tr>
			<th  class="text-center">
				<h1>
					<b><?php echo $_SESSION['shop_name'] ?></b>
				</h1>
			</th>
		</tr>
		<tr>
			<th class="text-center">
				<p>
					<b>Receipt</b>
				</p>
			</th>
		</tr>
		<tr>
			<td class="clear">&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="20%" class="">Customer :</td>
						<td width="40%" class="bbottom"><?php echo ucwords($cname) ?></td>
						<td width="20%" class="">Date :</td>
						<td width="20%" class="bbottom"><?php echo date("Y-m-d", strtotime($date_updated)) ?></td>
					</tr>
					<tr>
						<td width="20%" class="">Reference Number :</td>
						<td width="80%" class="bbottom" colspan="3"><?php echo $ref_no ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="clear">&nbsp;</td>
		</tr>
		<tr>
			<table width="100%">
				<tr>
					<th width="20%" class="wborder">Qty</th>
					<th width="30%" class="wborder">Product</th>
					<th width="25%" class="wborder">Unit Price</th>
					<th width="25%" class="wborder">Amount</th>
				</tr>
				<?php
				while ($row = $inv->fetch_assoc()) :
					foreach (json_decode($row['other_details']) as $k => $v) {
						$row[$k] = $v;
					}
				?>
					<tr>
						<td class="wborder text-center">
							<?php echo $row['qty'] ?>
						</td>
						<td class="wborder">
							<p class="pname">Name: <b><?php echo $prod[$row['product_id']]['name'] ?></b></p>
							<p class="pdesc"><small><i>Description: <b><?php echo $prod[$row['product_id']]['description'] ?></b></i></small></p>
						</td>
						<td class="wborder "><?php echo number_format($row['price'], 2) ?></td>
						<td class="wborder "><?php echo number_format($row['s_price'] * $row['qty'], 2) ?></td>

					</tr>
				<?php endwhile; ?>
				<tr>
					<th class=" wborder" colspan="3">Total</th>
					<th class=" wborder"><?php echo number_format($total_amount) ?></th>
				</tr>
				<tr>
					<th class=" wborder" colspan="3">Amount Tendered</th>
					<th class=" wborder"><?php echo number_format($amount_tendered) ?></th>
				</tr>
				<tr>
					<th class=" wborder" colspan="3">Change</th>
					<th class=" wborder"><?php echo number_format($amount_change) ?></th>
				</tr>
			</table>
		</tr>
		<tr>
			<td class="clear">&nbsp;</td>
		</tr>
		<tr>
			<th>
				<p class="text-center"><i>This is not an official receipt.</i></p>
			</th>
		</tr>
	</table>


</div>
<hr>
<div class="">
	<div class="col-md-12">
		<div class="row">
			<button type="button" class="btn btn-sm btn-primary" id="print"><i class="fa fa-print"></i> Print</button>
			<button type="button" class="btn btn-sm btn-secondary" onclick="location.reload()"><i class="fa fa-plus"></i> New Sales</button>

		</div>
	</div>
</div>

<script>
	$('#print').click(function() {
		var _html = $('#print-sales').clone();
		var newWindow = window.open("", "_blank", "menubar=no,scrollbars=yes,resizable=yes,width=700,height=600");
		newWindow.document.write(_html.html())
		newWindow.document.close()
		newWindow.focus()
		newWindow.print()
		setTimeout(function() {
			;
			newWindow.close();
		}, 1500);
	})
</script>