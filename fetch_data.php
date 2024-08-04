<?php
include 'db_connect.php';
session_start();
$dbName = $_SESSION['shop_db'];

// Check if date range is set
if (isset($_POST['date_range'])) {
    $dateRange = $_POST['date_range'];
    $totalSales = 0;
    $totalPurchases = 0;
    $totalUdhaar= 0;
    $totalActual = 0;
    $totalExpense = 0;

    // Get sales and expenses data based on the selected date range
    $dateConditions = [
        'today' => "date(date_updated) = CURDATE()",
        'this_month' => "MONTH(date_updated) = MONTH(CURDATE()) AND YEAR(date_updated) = YEAR(CURDATE())",
        'last_3_months' => "date_updated >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)",
    ];

    $dateCondition = $dateConditions[$dateRange] ?? $dateConditions['today'];

    $salesQuery = "SELECT SUM(total_amount) as total_sales, SUM(actual_amount) as total_actual 
                   FROM sales 
                   WHERE $dateCondition";

    $udhaarQuery = "SELECT SUM(amount_change) as total_udhaar 
                   FROM sales
                   WHERE $dateCondition AND paymode = 2";

    $purchasesQuery = "SELECT SUM(total_amount) as total_purchases 
                   FROM receiving
                   WHERE $dateCondition";

    $expenseQuery = "SELECT SUM(amount) as total_expense 
                     FROM expenses 
                     WHERE $dateCondition";

    // Execute queries
    $salesResult = shopConn($dbName)->query($salesQuery);
    $purchasesResult = shopConn($dbName)->query($purchasesQuery);
    $udhaarResult = shopConn($dbName)->query($udhaarQuery);
    $expenseResult = shopConn($dbName)->query($expenseQuery);

    // Check if sales data is fetched
    if ($salesResult && $salesResult->num_rows > 0) {
        $row = $salesResult->fetch_assoc();
        $totalSales = $row['total_sales'] ?? 0;
        $totalActual = $row['total_actual'] ?? 0;
    }
    
    // Check if sales data is fetched
    if ($purchasesResult && $purchasesResult->num_rows > 0) {
        $row = $purchasesResult->fetch_assoc();
        $totalPurchases = $row['total_purchases'] ?? 0;
    }

    // Check if sales data is fetched
    if ($udhaarResult && $udhaarResult->num_rows > 0) {
        $row = $udhaarResult->fetch_assoc();
        $totalUdhaar = $row['total_udhaar'] * -1 ?? 0;
    }

    if ($expenseResult && $expenseResult->num_rows > 0) {
        $row = $expenseResult->fetch_assoc();
        $totalExpense = $row['total_expense'] ?? 0;
    }

    // Calculate profit and profit after expenses
    $profit = $totalSales - $totalActual;
    $profitAfter = $totalSales - ($totalActual + $totalExpense);

    // Return data as JSON response
    echo json_encode([
        'total_sales' => number_format($totalSales, 2),
        'total_purchases' => number_format($totalPurchases, 2),
        'total_udhaar' => number_format($totalUdhaar, 2),
        'total_profit_after_expenses' => number_format($profitAfter, 2),
        'total_profit' => number_format($profit, 2),
        'total_expenses' => number_format($totalExpense, 2)
    ]);
}
