<?php
include 'db_connect.php';
session_start();
$dbName = $_SESSION['shop_db'];

// Check if date range is set
if (isset($_POST['date_range'])) {
    $dateRange = $_POST['date_range'];
    // echo $dateRange;
    $totalSales = 00;
    $totalActual = 00;
    $totalExpense = 00;

    // Get sales data based on the selected date range
    switch ($dateRange) {
        case 'today':
            $salesQuery = "SELECT SUM(total_amount) as total_sales, SUM(actual_amount) as total_actual 
                           FROM sales_list 
                           WHERE date(date_updated) = CURDATE()";
            // echo $salesQuery;
            $totalExpense = shop_conn($dbName)->query("SELECT SUM(price) as total_expense FROM expenses_list WHERE date(date_updated) = CURDATE()");
            break;
        case 'this_month':
            $salesQuery = "SELECT SUM(total_amount) as total_sales, SUM(actual_amount) as total_actual 
                           FROM sales_list 
                           WHERE MONTH(date_updated) = MONTH(CURDATE()) AND YEAR(date_updated) = YEAR(CURDATE())";
            $totalExpense = shop_conn($dbName)->query("SELECT SUM(price) as total_expense FROM expenses_list  WHERE MONTH(date_updated) = MONTH(CURDATE()) AND YEAR(date_updated) = YEAR(CURDATE())");

            break;
        case 'last_3_months':
            $salesQuery = "SELECT SUM(total_amount) as total_sales, SUM(actual_amount) as total_actual 
                           FROM sales_list 
                           WHERE date_updated >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
            $totalExpense = shop_conn($dbName)->query("SELECT SUM(price) as total_expense FROM expenses_list  WHERE date_updated >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");

            break;
        default:
            $salesQuery = "SELECT SUM(total_amount) as total_sales, SUM(actual_amount) as total_actual 
                           FROM sales_list 
                           WHERE date(date_updated) = CURDATE()";
            $totalExpense = shop_conn($dbName)->query("SELECT SUM(price) as total_expense FROM expenses_list WHERE date(date_updated) = CURDATE()");

            break;
    }

    // Execute query
    $salesResult = shop_conn($dbName)->query($salesQuery);

    // Check if sales data is fetched
    if ($salesResult && $salesResult->num_rows > 0) {
        $row = $salesResult->fetch_assoc();
        $totalSales = $row['total_sales'];
        $totalActual = $row['total_actual'];
    }
    if($totalExpense && $totalExpense ->num_rows >0){
        $row = $totalExpense -> fetch_assoc();
        $totalExpense = $row['total_expense'];
    }
    // Calculate profit
    $profit = $totalSales - $totalActual;
    // Calculate profit after expense
    $profitAfter = $totalSales - ($totalActual + $totalExpense);

    // Return data as JSON response
    echo json_encode(array(
        'total_sales' => number_format($totalSales, 2),
        'total_profit_after_expenses' => number_format($profitAfter, 2),
        'total_profit' => number_format($profit, 2)
    ));
}
