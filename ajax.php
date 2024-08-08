<?php
ob_start();
$action = $_GET['action'] ?? ''; // Ensure $action is set and not undefined
include 'admin_class.php';
$crud = new Action();

$response = null;

switch ($action) {
    case 'signup':
        $response = $crud->signup();
        break;
    case 'login':
        $response = $crud->login();
        break;
    case 'admin_login':
        $response = $crud->admin_login();
        break;
    case 'login2':
        $response = $crud->login2();
        break;
    case 'logout':
        $response = $crud->logout();
        break;
    case 'admin_logout':
        $response = $crud->admin_logout();
        break;
    case 'logout2':
        $response = $crud->logout2();
        break;
    case 'save_user':
        $response = $crud->save_user();
        break;
    case 'delete_user':
        $response = $crud->delete_user();
        break;
    case 'save_account':
        $response = $crud->save_account();
        break;
    case 'save_category':
        $response = $crud->save_category();
        break;
    case 'delete_category':
        $response = $crud->delete_category();
        break;
    case 'save_expenses':
        $response = $crud->save_expenses();
        break;
    case 'delete_expenses':
        $response = $crud->delete_expenses();
        break;
    case 'save_supplier':
        $response = $crud->save_supplier();
        break;
    case 'delete_supplier':
        $response = $crud->delete_supplier();
        break;
    case 'save_product':
        $response = $crud->save_product();
        break;
    case 'add_product':
        $response = $crud->add_product();
        break;
    case 'delete_product':
        $response = $crud->delete_product();
        break;
    case 'remove_product':
        $response = $crud->remove_product();
        break;
    case 'save_receiving':
        $response = $crud->save_receiving();
        break;
    case 'delete_receiving':
        $response = $crud->delete_receiving();
        break;
    case 'save_customer':
        $response = $crud->save_customer();
        break;
    case 'delete_customer':
        $response = $crud->delete_customer();
        break;
    case 'chk_prod_availability':
        $response = $crud->chk_prod_availability();
        break;
    case 'save_sales':
        $response = $crud->save_sales();
        break;
    case 'delete_sales':
        $response = $crud->delete_sales();
        break;
    case 'delete_shop':
        $response = $crud->delete_shop();
        break;
    case 'save_credit':
        $response = $crud->save_credit();
        break;
    default:
        echo "Invalid action.";
        break;
}

if ($response !== null) {
    echo $response;
}
