<?php include 'db_connect.php';
?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>Udhar</h4>
            </div>
            <div class="card-body">
                <form action="" id="manage-credit">
                    <input type="hidden" name="customer_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="mb-3 col-md-5">
                                <label class="form-label">Customer</label>
                                <?php
                                $customer = shopConn($dbName)->query("SELECT name FROM customers WHERE id=" . $_GET['id']);
                                $row = $customer->fetch_assoc()
                                ?>
                                <input type="text" name="customer" value="<?php echo isset($row['name']) ? $row['name'] : 'GUEST' ?>" class="form-control" readonly="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive-sm">
                                    <table class="table table-striped table-bordered border-warning table-info table-hover" id="list">
                                        <colgroup>
                                            <col width="25%">
                                            <col width="25%">
                                            <col width="20%">
                                            <col width="20%">
                                            <col width="10%">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th scope="col">Product</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col">M.R.P</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($_GET['id'])) :
                                                $sum = 0;
                                                $paid = 0;
                                                $to_pay = 0;
                                                $qry = shopConn($dbName)->query("SELECT * FROM sales WHERE paymode = 2 AND customer_id = " . $_GET['id']);

                                                while ($row = $qry->fetch_assoc()) :
                                                    $id = $row['id'];
                                                    $amount_tendered = $row['amount_tendered'];
                                                    $change_amount = $row['amount_change'] * -1;
                                                    $inv = shopConn($dbName)->query("SELECT * FROM inventory WHERE type = 2 AND form_id = $id");

                                                    while ($invRow = $inv->fetch_assoc()) :
                                                        $otherDetails = json_decode($invRow['other_details'], true);
                                                        $invRow = array_merge($invRow, $otherDetails);
                                                        $product = shopConn($dbName)->query("SELECT * FROM products WHERE id = " . $invRow['product_id'] . " ORDER BY name ASC")->fetch_assoc();

                                                        $total = $invRow['qty'] * $invRow['s_price'];
                                                        $sum += $total;
                                                        $paid += $amount_tendered;
                                                        $to_pay += $change_amount;
                                            ?>
                                                        <tr class="item-row">
                                                            <td>
                                                                <p class="pname">Name: <b><?php echo $product['name']; ?></b></p>
                                                                <p class="pdesc"><small><i>Description: <b><?php echo $product['description']; ?></b></i></small></p>
                                                            </td>
                                                            <td style="min-width: 80px;">
                                                                <input type="hidden" name="qty[]" value="<?php echo $invRow['qty']; ?>">
                                                                <p class=""><?php echo $invRow['qty']; ?></p>
                                                            </td>
                                                            <td>
                                                                <p class=""><?php echo $invRow['price']; ?></p>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="s_price[]" value="<?php echo $invRow['s_price']; ?>">
                                                                <p class=""><?php echo $invRow['s_price']; ?></p>
                                                            </td>
                                                            <td>
                                                                <p class="amount"><?php echo $total; ?></p>
                                                                <input type="hidden" name="amount" value="<?php echo $total; ?>" class="form-control">
                                                            </td>
                                                        </tr>
                                            <?php
                                                    endwhile;
                                                endwhile;
                                            endif;
                                            ?>

                                            <tr>
                                                <th class="" colspan="3">Total</th>
                                                <th colspan="2"><input type="text" name="total" id="total" value="<?php echo $sum ?>" class="form-control" readonly=""></th>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Paid</th>
                                                <th><input type="text" name="paid" id="paid" value="<?php echo $paid ?>" class="form-control" readonly=""></th>
                                                <th>To PAY</th>
                                                <th colspan="2"><input type="text" name="to_pay" id="to_pay" value="<?php echo $to_pay?>" class="form-control" readonly=""></th>
                                            </tr>
                                            
                                            <tr>
                                                <th colspan="3">Pay Now</th>
                                                <th colspan="2"><input type="number" name="paying" id="paying" value="" class="form-control pay"> </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <button type="button" class="btn btn-primary btn-sm btn-block" id='submit' onclick="$('#manage-credit').submit()">Pay</button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#manage-credit').submit(function(e) {
            e.preventDefault();
            start_load();

            if ($("#list .item-row").length <= 0) {
                alert_toast("Please insert at least 1 item first.", 'danger');
                end_load();
                return false;
            }
            var id = $('.customer_id').val();
            var paying = parseFloat($('#paying').val());
            var to_pay = parseFloat($('#to_pay').val());

            if (isNaN(paying) || isNaN(to_pay) || paying > to_pay) {
                alert_toast("Please insert a valid amount.", 'danger');
                end_load();
                return false;
            }

            if (paying === 0) {
                alert_toast("Please insert the amount.", 'danger');
                end_load();
                return false;
            }
            // Collect form data
            var formData = $(this).serialize();

            // Escape form data to safely pass as a parameter
            var escapedFormData = encodeURIComponent(formData);

            // Call _conf with the serialized form data as a parameter
            _conf("Are you sure to pay?", "pay_dues", [escapedFormData]);
        });
    });

    function pay_dues(escapedFormData) {
        start_load();
        // Decode the form data
        var formData = decodeURIComponent(escapedFormData);
        $.ajax({
            url: 'ajax.php?action=save_credit',
            method: 'POST',
            data: formData, // Parse the serialized form data
            success: function(resp) {
                console.log(resp)
                if (resp > 0) {
                    end_load();
                    alert_toast("Data successfully updated", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>