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
                        <!-- Total profit -->
                        <div class="alert alert-profit col-md-4 mb-3 my-2" role="alert">
                            <p><b>
                                    <large>Total Profit</large>
                                </b></p>
                            <hr>
                            <p class=""><b>
                                    <large id="totalProfit"></large>
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
                <div class="card-footer"></div>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to update data based on selected date range
    function updateData(dateRange) {
        // AJAX call to fetch data for the selected date range
        $.ajax({
            url: 'fetch_data.php',
            method: 'POST',
            data: {
                date_range: dateRange
            },
            dataType: 'json', // Specify JSON dataType
            success: function(response) {
                // console.log(parseFloat(response.total_sales))
                // console.log(response)

                // Update total sales today
                $('#totalSales').text(response.total_sales);
                // Update total profit today
                $('#totalProfit').text(response.total_profit);
                // Update total profit after expenses
                $('#totalProfitAfterExpenses').text(response.total_profit_after_expenses);

                // Change alert type based on Total profit
                var totalProfit = parseFloat(response.total_profit_after_expenses);
                var profitAlert = $('.alert-total_profit');
                profitAlert.removeClass('alert-success alert-danger alert-primary');
                if (totalProfit == 0) {
                    profitAlert.addClass('alert-info');

                } else if (totalProfit > 0) {
                    profitAlert.addClass('alert-success');
                } else {
                    profitAlert.addClass('alert-danger');
                }
                // Change alert type based on profit
                var profit = parseFloat(response.total_profit);
                var profitAlert = $('.alert-profit');
                profitAlert.removeClass('alert-success alert-danger');
                if (totalProfit > 0) {
                    profitAlert.addClass('alert-success');
                } else {
                    profitAlert.addClass('alert-primary');
                }
            },
            error: err => {
                // console.log(err);
            }
        });
    }

    // Event listener for dropdown change
    $('#dateRangeSelect').change(function() {
        var selectedDateRange = $(this).val();
        // Call updateData function with selected date range
        updateData(selectedDateRange);
    });

    // Initial data update when page loads (default: today)
    updateData('today');
</script>