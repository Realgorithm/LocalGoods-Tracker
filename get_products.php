<?php
include('db_connect.php');
if (isset($_POST['category_id'])) {
    $categoryId = $_POST['category_id'];

    $conn->select_db('central_db');
    $products = $conn->query("SELECT * FROM products WHERE category_id = $categoryId ORDER BY name ASC");
    while ($row = $products->fetch_assoc()) {
        $img = $row['img_path'] != '' ? 'assets/img/' . $row['img_path'] : 'assets/img/1600398180_no-image-available.png';
        echo "<option value='{$row['name']}' data-img='{$img}'>{$row['name']}</option>";
    }
}
