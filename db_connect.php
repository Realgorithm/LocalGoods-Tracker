<?php
$shopDb='';

function createShopDatabase($dbName)
{
    $conn = new mysqli('localhost', 'root', '');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
    if ($conn->query($sql) === TRUE) {
        // CREATE TABLE IF NOT EXISTSs for the new shop
        $conn->select_db($dbName);
        $createCustomerTable = "CREATE TABLE IF NOT EXISTS customer_list (
            id int(30) AUTO_INCREMENT PRIMARY KEY,
            name text NOT NULL,
            contact varchar(30) NOT NULL,
            address text NOT NULL
        )";
        $createExpensesTable = "CREATE TABLE IF NOT EXISTS expenses_list (
            id int(11) AUTO_INCREMENT PRIMARY KEY,
            name varchar(56) NOT NULL,
            price double NOT NULL,
            date_updated datetime NOT NULL DEFAULT current_timestamp()
        )";
        $createInventoryTable = "CREATE TABLE IF NOT EXISTS inventory (
            id int(30) AUTO_INCREMENT PRIMARY KEY,
            product_id int(30) NOT NULL,
            qty int(30) NOT NULL,
            type tinyint(1) NOT NULL COMMENT '1= stockin , 2 = stockout',
            stock_from varchar(100) NOT NULL COMMENT 'sales/receiving',
            form_id int(30) NOT NULL,
            other_details text NOT NULL,
            remarks text NOT NULL,
            date_updated datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        )";
        $createProductTable = "CREATE TABLE IF NOT EXISTS product_list (
            id int(30) AUTO_INCREMENT PRIMARY KEY,
            category_id int(30) NOT NULL,
            sku varchar(50) NOT NULL,
            price double NOT NULL,
            image varchar(256) NOT NULL,
            b_price double NOT NULL,
            l_price double NOT NULL,
            s_price double NOT NULL,
            name varchar(150) NOT NULL,
            description text NOT NULL
        )";
        $createReceivingTable = "CREATE TABLE IF NOT EXISTS receiving_list (
            id int(30) AUTO_INCREMENT PRIMARY KEY,
            ref_no varchar(100) NOT NULL,
            supplier_id int(30) NOT NULL,
            total_amount double NOT NULL,
            date_added datetime NOT NULL DEFAULT current_timestamp()
        )";
        $createSalesTable = "CREATE TABLE IF NOT EXISTS sales_list (
            id int(30) AUTO_INCREMENT PRIMARY KEY,
            ref_no varchar(30) NOT NULL,
            customer_id int(30) NOT NULL,
            actual_amount double NOT NULL,
            total_amount double NOT NULL,
            paymode int(11) NOT NULL,
            amount_tendered double NOT NULL,
            amount_change double NOT NULL,
            date_updated datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        )";
        $createSupplierTable = "CREATE TABLE IF NOT EXISTS supplier_list (
            id int(30) AUTO_INCREMENT PRIMARY KEY,
            supplier_name text NOT NULL,
            contact varchar(30) NOT NULL,
            address text NOT NULL
        )";
        $createUsersTable = "CREATE TABLE IF NOT EXISTS users (
            id int(30) AUTO_INCREMENT PRIMARY KEY,
            name varchar(200) NOT NULL,
            username varchar(100) NOT NULL,
            password varchar(200) NOT NULL,
            type tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=admin , 2 = cashier'
        )";
        
        $conn->query($createCustomerTable);
        $conn->query($createExpensesTable);
        $conn->query($createInventoryTable);
        $conn->query($createProductTable);
        $conn->query($createReceivingTable);
        $conn->query($createSalesTable);
        $conn->query($createSupplierTable);
        $conn->query($createUsersTable);

        return $dbName;
    } else {
        return false;
    }
}
$conn = new mysqli('localhost', 'root', '') or die("Could not connect to mysql" . mysqli_error($con));
function shop_conn($dbName)
{
    global $shopDb;
    $shopDb = $dbName;
    $shopConn = new mysqli('localhost', 'root', '', $dbName) or die("Could not connect to mysql" . mysqli_error($shopConn));

    return $shopConn;
}

echo $shopDb;
