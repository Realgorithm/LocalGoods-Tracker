<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4><?php echo "Welcome back <b>" . $_SESSION['login_name'] . "</b>"  ?></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Dropdown for selecting date range -->
                        <div class="col-md-3 ml-auto">
                            <select class="form-control" id="dateRangeSelect">
                                <option value="today">Today</option>
                                <option value="this_month">This Month</option>
                                <option value="last_3_months">Last 3 Months</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <!-- Display sales and profit based on selected date range -->
                    <div class="row">
                        <!-- Sales -->
                        <div class="alert alert-success col-md-4 mb-3 my-2" role="alert">
                            <p><b>
                                    <large>Total Sales</large>
                                </b></p>
                            <hr>
                            <p class=""><b>
                                    <large id="totalSales"></large>
                                </b></p>
                        </div>
                        <!-- Total purchases -->
                        <div class="alert alert-info col-md-4 mb-3 my-2" role="alert">
                            <p><b>
                                    <large>Total Purchases</large>
                                </b></p>
                            <hr>
                            <p class=""><b>
                                    <large id="totalPurchases"></large>
                                </b></p>
                        </div>

                        <!-- Total udhaar -->
                        <div class="alert alert-warning col-md-4 mb-3 my-2" role="alert">
                            <p><b>
                                    <large>Total Udhaar</large>
                                </b></p>
                            <hr>
                            <p class=""><b>
                                    <large id="totalUdhaar"></large>
                                </b></p>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Total profit  -->
                        <div class="alert alert-profit col-md-4 mb-3 my-2" role="alert">
                            <p><b>
                                    <large>Total Profit</large>
                                </b></p>
                            <hr>
                            <p class=""><b>
                                    <large id="totalProfit"></large>
                                </b></p>
                        </div>

                        <!-- Total Expenses  -->
                        <div class="alert alert-danger col-md-4 mb-3 my-2" role="alert">
                            <p><b>
                                    <large>Total Expenses</large>
                                </b></p>
                            <hr>
                            <p class=""><b>
                                    <large id="totalExpenses"></large>
                                </b></p>
                        </div>

                        <!-- Total profit after expenses -->
                        <div class="alert alert-total_profit col-md-4 mb-3 my-2" role="alert">
                            <p><b>
                                    <large>Total Profit after Expenses</large>
                                </b></p>
                            <hr>
                            <p class=""><b>
                                    <large id="totalProfitAfterExpenses"></large>
                                </b></p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        // Function to update data based on selected date range
        function updateData(dateRange) {
            $.ajax({
                url: 'fetch_data.php',
                method: 'POST',
                data: {
                    date_range: dateRange
                },
                dataType: 'json',
                success: function(response) {
                    $('#totalSales').text(response.total_sales);
                    $('#totalPurchases').text(response.total_purchases);
                    $('#totalUdhaar').text(response.total_udhaar);
                    $('#totalProfit').text(response.total_profit);
                    $('#totalExpenses').text(response.total_expenses);
                    $('#totalProfitAfterExpenses').text(response.total_profit_after_expenses);

                    // Update alert type based on Total profit after expenses
                    var totalProfitAfterExpenses = parseFloat(response.total_profit_after_expenses);
                    var profitAfterExpensesAlert = $('.alert-total_profit');
                    profitAfterExpensesAlert.removeClass('alert-success alert-danger alert-info');

                    if (totalProfitAfterExpenses === 0) {
                        profitAfterExpensesAlert.addClass('alert-info');
                    } else if (totalProfitAfterExpenses > 0) {
                        profitAfterExpensesAlert.addClass('alert-success');
                    } else {
                        profitAfterExpensesAlert.addClass('alert-danger');
                    }

                    // Update alert type based on Total profit
                    var totalProfit = parseFloat(response.total_profit);
                    var profitAlert = $('.alert-profit');
                    profitAlert.removeClass('alert-success alert-danger alert-primary');

                    if (totalProfit > 0) {
                        profitAlert.addClass('alert-success');
                    } else {
                        profitAlert.addClass('alert-primary');
                    }
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }

        // Event listener for dropdown change
        $('#dateRangeSelect').change(function() {
            var selectedDateRange = $(this).val();
            updateData(selectedDateRange);
        });

        // Initial data update when page loads (default: today)
        updateData('today');
    });
</script>