<?php
session_start();

if (isset($_SESSION['login_id']) and isset($_SESSION['shop_db'])) {
    // User is logged in
    http_response_code(200); // OK
} else {
    // User is not logged in
    http_response_code(401); // Unauthorized
}
