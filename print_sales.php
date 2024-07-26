<?php
include 'db_connect.php';
session_start();
$dbName = $_SESSION['shop_db'];

if (isset($_GET['id'])) {
    $salesId = $_GET['id'];
    $salesQuery = shop_conn($dbName)->query("SELECT * FROM sales_list WHERE id=$salesId")->fetch_array();
    foreach ($salesQuery as $key => $value) {
        $$key = $value;
    }
    $inventoryQuery = shop_conn($dbName)->query("SELECT * FROM inventory WHERE type=2 AND form_id=$salesId");
    if ($customer_id > 0) {
        $customerQuery = shop_conn($dbName)->query("SELECT name FROM customer_list WHERE id=$customer_id");
        $cname = $customerQuery->num_rows > 0 ? $customerQuery->fetch_array()['name'] : "Guest";
    } else {
        $cname = "Guest";
    }
}

$productQuery = shop_conn($dbName)->query("SELECT * FROM product_list ORDER BY name ASC");
$products = [];
while ($row = $productQuery->fetch_assoc()) {
    $products[$row['id']] = $row;
}
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
            border-bottom: 1px solid black;
        }
        td p, th p {
            margin: unset;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .clear {
            padding: 10px;
        }
        #uni_modal .modal-footer {
            display: none;
        }
    </style>

    <table width="100%">
        <tr>
            <th class="text-center">
                <h1><b><?php echo $_SESSION['shop_name'] ?></b></h1>
            </th>
        </tr>
        <tr>
            <th class="text-center">
                <p><b>Receipt</b></p>
            </th>
        </tr>
        <tr><td class="clear">&nbsp;</td></tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td width="20%">Customer :</td>
                        <td width="40%" class="bbottom"><?php echo ucwords($cname) ?></td>
                        <td width="20%">Date :</td>
                        <td width="20%" class="bbottom"><?php echo date("Y-m-d", strtotime($date_updated)) ?></td>
                    </tr>
                    <tr>
                        <td>Reference Number :</td>
                        <td class="bbottom" colspan="3"><?php echo $ref_no ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td class="clear">&nbsp;</td></tr>
        <tr>
            <table width="100%">
                <tr>
                    <th class="wborder">Qty</th>
                    <th class="wborder">Product</th>
                    <th class="wborder">Unit Price</th>
                    <th class="wborder">Amount</th>
                </tr>
                <?php while ($row = $inventoryQuery->fetch_assoc()): ?>
                    <?php foreach (json_decode($row['other_details']) as $key => $value) $row[$key] = $value; ?>
                    <tr>
                        <td class="wborder text-center"><?php echo $row['qty'] ?></td>
                        <td class="wborder">
                            <p class="pname">Name: <b><?php echo $products[$row['product_id']]['name'] ?></b></p>
                            <p class="pdesc"><small><i>Description: <b><?php echo $products[$row['product_id']]['description'] ?></b></i></small></p>
                        </td>
                        <td class="wborder"><?php echo number_format($row['price'], 2) ?></td>
                        <td class="wborder"><?php echo number_format($row['s_price'] * $row['qty'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <th class="wborder" colspan="3">Total</th>
                    <th class="wborder"><?php echo number_format($total_amount) ?></th>
                </tr>
                <tr>
                    <th class="wborder" colspan="3">Amount Tendered</th>
                    <th class="wborder"><?php echo number_format($amount_tendered) ?></th>
                </tr>
                <tr>
                    <th class="wborder" colspan="3">Change</th>
                    <th class="wborder"><?php echo number_format($amount_change) ?></th>
                </tr>
            </table>
        </tr>
        <tr><td class="clear">&nbsp;</td></tr>
        <tr>
            <th>
                <p class="text-center"><i>This is not an official receipt.</i></p>
            </th>
        </tr>
    </table>
</div>
<hr>
<div>
    <div class="col-md-12">
        <div class="row">
            <button type="button" class="btn btn-sm btn-primary" id="print"><i class="fa fa-print"></i> Print</button>
            <button type="button" class="btn btn-sm btn-secondary" onclick="location.reload()"><i class="fa fa-plus"></i> New Sales</button>
        </div>
    </div>
</div>

<script>
    $('#print').click(function() {
        var printContents = $('#print-sales').html();
        var printWindow = window.open("", "", "menubar=no,scrollbars=yes,resizable=yes,width=700,height=600");
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<link rel="stylesheet" href="assets/css/bootstrap.min.css" />');
        printWindow.document.write('</head><body >');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        setTimeout(function() { printWindow.close(); }, 1500);
    });
</script>
