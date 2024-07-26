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
                    <!-- <input type="hidden" name="ref_no" value="<?php echo isset($ref_no) ? $ref_no : '' ?>"> -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="mb-3 col-md-5">
                                <label class="form-label">Customer</label>
                                <?php
                                $customer = shop_conn($dbName)->query("SELECT name FROM customer_list WHERE id=" . $_GET['id']);
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
                                            <col width="30%">
                                            <col width="10%">
                                            <col width="25%">
                                            <col width="25%">
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
                                                $total = 0;
                                                $qry = shop_conn($dbName)->query("SELECT * FROM sales_list WHERE paymode = 2 AND customer_id=" . $_GET['id']);
                                                while ($row = $qry->fetch_assoc()) :
                                                    foreach ($row as $k => $val) {
                                                        $$k = $val;
                                                        $inv = shop_conn($dbName)->query("SELECT * FROM inventory where type=2 and form_id=" . $id);
                                                    }

                                                    if (isset($id)) :
                                                        while ($row = $inv->fetch_assoc()) :
                                                            foreach (json_decode($row['other_details']) as $k => $v) {
                                                                $row[$k] = $v;
                                                            }
                                                            $product = shop_conn($dbName)->query("SELECT * FROM product_list  WHERE id = " . $row['product_id'] . " order by name asc");
                                                            while ($produ = $product->fetch_assoc()) :
                                                                $prod[$produ['id']] = $produ;
                                                            // echo $prod[];
                                                            endwhile; ?>
                                                            <tr class="item-row">
                                                                <td>
                                                                    <input type="hidden" name="id" value="<?php echo $id ?>">
                                                                    <input type="hidden" name="inv_id[]" value="<?php echo $row['id'] ?>">
                                                                    <input type="hidden" name="product_id[]" value="<?php echo $row['product_id'] ?>">
                                                                    <p class="pname">Name: <b><?php echo $prod[$row['product_id']]['name'] ?></b></p>
                                                                    <p class="pdesc"><small><i>Description: <b><?php echo $prod[$row['product_id']]['description'] ?></b></i></small></p>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" min="1" step="any" name="qty[]" value="<?php echo $row['qty'] ?>" class="">
                                                                    <p class=""><?php echo $row['qty'] ?></p>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" min="1" step="any" name="price[]" value="<?php echo $row['price'] ?>" class="">
                                                                    <p class=""><?php echo $row['price'] ?></p>
                                                                </td>
                                                                <input type="text" name="amount" value="" class="form-control" hidden>
                                                                <input type="text" name="quantity" value="" class="form-control" hidden>
                                                                <td>
                                                                    <input type="hidden" min="1" step="any" name="s_price[]" value="<?php echo $row['s_price'] ?>" class="">
                                                                    <p class=""><?php echo $row['s_price'] ?></p>
                                                                    <?php $total = $row['qty'] * $row['s_price']; ?>
                                                                </td>
                                                                <td>

                                                                    <p class="amount "><?php echo $total;
                                                                                        $sum += $total ?></p>
                                                                    <input type="text" name="" value="<?php echo $sum; ?>" class="form-control" hidden>
                                                                </td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                            <tr>
                                                <th class="" colspan="3">Total</th>
                                                <th colspan="2"><input type="text" name="total" id="total" value="<?php echo $sum ?>" class="form-control" readonly=""></th>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <?php if (isset($_GET['id'])) {
                                                $pay_unsettled = shop_conn($dbName)->query("SELECT pay_unsettled FROM customer_list where id ='" . $_GET['id'] . "'")->fetch_assoc()['pay_unsettled'];
                                            } else {
                                                $pay_unsettled = 0;
                                            } ?>
                                            <tr>
                                                <th>Amount Unsettled</th>
                                                <th><input type="text" name="pay_unsettled" id="pay_unsettled" value="<?php echo $pay_unsettled ?>" class="form-control" readonly=""></th>
                                                <th>To PAY</th>
                                                <th colspan="2"><input type="text" name="to_pay" id="to_pay" value="<?php echo $sum - $pay_unsettled ?>" class="form-control" readonly=""></th>
                                            </tr>

                                            <tr>
                                                <th class="" colspan="3">Pay Now</th>
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
        if ('<?php echo isset($_GET['id']) ?>' == 1) {
            $('[name="supplier_id"]').val('<?php echo isset($supplier_id) ? $supplier_id : '' ?>').select2({
                placeholder: "Please select here",
                width: "100%"
            })
            calculate_total()
        }
    })

    function calculate_total() {
        var total = 0;
        $('#list tbody').find('.item-row').each(function() {
            var _this = $(this).closest('tr')
            var quantity = parseFloat(_this.find('[name="qty[]"]').val())
            var price = parseFloat(_this.find('[name="s_price[]"]').val())

            var amount = parseFloat(_this.find('[name="qty[]"]').val()) * parseFloat(_this.find('[name="s_price[]"]').val());

            //console.log(amount)
            $('[name="amount"]').val(amount)
            $('[name="quantity"]').val(quantity)

        })
    }
    $('#manage-credit').submit(function(e) {
        e.preventDefault()
        start_load()
        if ($("#list .item-row").length <= 0) {
            alert_toast("Please insert atleast 1 item first.", 'danger');
            end_load();
            return false;
        }
        var paying = $('#paying').val();
        var to_pay = $('#to_pay').val();

        console.log(paying)
        console.log(total)
        if (parseFloat(paying) > parseFloat(to_pay)) {
            alert_toast("Please insert a valid amount.", 'danger');
            return false;
        }
        if (paying == '') {
            alert_toast("Please insert the amount.", 'danger');
            return false;
        }
        $.ajax({
            url: 'ajax.php?action=save_credit',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                console.log(resp)
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