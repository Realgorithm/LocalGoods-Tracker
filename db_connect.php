<?php
$shopDb = '';

function createShopDatabase($dbName)
{
    $conn = new mysqli('localhost', 'root', '');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create the database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
    if ($conn->query($sql) !== TRUE) {
        die("Error creating database: " . $conn->error);
    }

    // Select the database
    $conn->select_db($dbName);

    // SQL statements to create tables
    $tables = [
        "CREATE TABLE IF NOT EXISTS customers (
            id INT(30) AUTO_INCREMENT PRIMARY KEY,
            name TEXT NOT NULL,
            contact VARCHAR(30) NOT NULL,
            address TEXT NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS expenses (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            expense VARCHAR(56) NOT NULL,
            description VARCHAR(255) NOT NULL,
            amount DOUBLE NOT NULL,
            date_updated DATETIME NOT NULL DEFAULT current_timestamp()
        )",
        "CREATE TABLE IF NOT EXISTS inventory (
            id INT(30) AUTO_INCREMENT PRIMARY KEY,
            product_id INT(30) NOT NULL,
            qty INT(30) NOT NULL,
            type TINYINT(1) NOT NULL COMMENT '1=stockin, 2=stockout',
            stock_from VARCHAR(100) NOT NULL COMMENT 'sales/receiving',
            form_id INT(30) NOT NULL,
            other_details TEXT NOT NULL,
            remarks TEXT NOT NULL,
            date_updated DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        )",
        "CREATE TABLE IF NOT EXISTS products (
            id INT(30) AUTO_INCREMENT PRIMARY KEY,
            category_id INT(30) NOT NULL,
            sku VARCHAR(50) NOT NULL,
            price DOUBLE NOT NULL,
            image VARCHAR(256) NOT NULL,
            b_price DOUBLE NOT NULL,
            l_price DOUBLE NOT NULL,
            s_price DOUBLE NOT NULL,
            name VARCHAR(150) NOT NULL,
            description TEXT NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS receiving (
            id INT(30) AUTO_INCREMENT PRIMARY KEY,
            ref_no VARCHAR(100) NOT NULL,
            supplier_id INT(30) NOT NULL,
            img_path VARCHAR(100) NOT NULL,
            total_amount DOUBLE NOT NULL,
            date_updated DATETIME NOT NULL DEFAULT current_timestamp()
        )",
        "CREATE TABLE IF NOT EXISTS sales (
            id INT(30) AUTO_INCREMENT PRIMARY KEY,
            ref_no VARCHAR(30) NOT NULL,
            customer_id INT(30) NOT NULL,
            actual_amount DOUBLE NOT NULL,
            total_amount DOUBLE NOT NULL,
            paymode INT(11) NOT NULL,
            amount_tendered DOUBLE NOT NULL,
            amount_change DOUBLE NOT NULL,
            date_updated DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        )",
        "CREATE TABLE IF NOT EXISTS suppliers (
            id INT(30) AUTO_INCREMENT PRIMARY KEY,
            name TEXT NOT NULL,
            contact VARCHAR(30) NOT NULL,
            address TEXT NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS users (
            id INT(30) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(200) NOT NULL,
            username VARCHAR(100) NOT NULL,
            password VARCHAR(200) NOT NULL,
            type TINYINT(1) NOT NULL DEFAULT 2 COMMENT '1=admin, 2=cashier'
        )"
    ];

    // Execute the SQL statements
    foreach ($tables as $table) {
        if ($conn->query($table) !== TRUE) {
            die("Error creating table: " . $conn->error);
        }
    }

    return $dbName;
}

function shopConn($dbName)
{
    $shopConn = new mysqli('localhost', 'root', '', $dbName);

    if ($shopConn->connect_error) {
        die("Could not connect to MySQL: " . $shopConn->connect_error);
    }

    return $shopConn;
}

// Create a connection to MySQL
$conn = new mysqli('localhost', 'root', '');

if ($conn->connect_error) {
    die("Could not connect to MySQL: " . $conn->connect_error);
}
