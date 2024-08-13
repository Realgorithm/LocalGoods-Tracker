<?php
session_start();
ini_set('display_errors', 1);
class Action
{
    private $db;
    private $db_name;
    private $db_conn;

    public function __construct()
    {
        ob_start();
        include 'db_connect.php';

        $this->db = $conn;
        if (isset($_SESSION['shop_db'])) {
            $this->db_name = $_SESSION['shop_db'];
            $this->db_conn = shopConn($this->db_name);
        }
    }

    function __destruct()
    {
        $this->db->close();
        ob_end_flush();
    }

    private function sanitize($input)
    {
        return htmlspecialchars(strip_tags($input));
    }

    private function generate_ref_no($ref_no, $type)
    {
        $ref_no = sprintf("%'.08d\n", $ref_no);
        $i = 1;

        while ($i == 1) {
            $chk = $this->db_conn->query(" SELECT * FROM $type WHERE ref_no = $ref_no ")->num_rows;
            if ($chk > 0) {
                $ref_no = mt_rand(1, 99999999);
                $ref_no = sprintf("%'.08d\n", $ref_no);
            } else {
                $i = 0;
            }
        }
        return $ref_no;
    }

    private function save_inventory($id, $product_ids, $qtys, $type, $from, $prices, $b_prices, $s_prices, $remarks, $ref_no, $inv_ids = [])
    {
        foreach ($product_ids as $k => $v) {
            $data = [
                'form_id' => $id,
                'product_id' => $product_ids[$k],
                'qty' => $qtys[$k],
                'type' => $type,
                'stock_from' => $from,
                'other_details' => json_encode(['price' => $prices[$k], 'b_price' => $b_prices[$k], 's_price' => $s_prices[$k], 'qty' => $qtys[$k]]),
                'remarks' => 'Stock ' . $remarks . '-' . $ref_no,
            ];

            $columns = implode(", ", array_keys($data));
            $values = implode("', '", array_values($data));
            if (!empty($inv_ids[$k])) {
                $query = "UPDATE inventory SET $columns = '$values' WHERE id = " . $inv_ids[$k];
            } else {
                $query = "INSERT INTO inventory ($columns) VALUES ('$values')";
            }
            $save2[] = $this->db_conn->query($query);

            $this->db_conn->query("UPDATE products SET b_price = '$b_prices[$k]', s_price = '$s_prices[$k]' WHERE id = '$product_ids[$k]'");
        }
        return $save2;
    }

    function signup()
    {
        $name = $this->sanitize($_POST['name']);
        $username = $this->sanitize($_POST['username']);
        $password = md5($this->sanitize($_POST['password']));
        $shop_name = $this->sanitize($_POST['shop_name']);
        $email = $this->sanitize($_POST['email']);
        $contact = $this->sanitize($_POST['contact']);
        $shop_tagline = $this->sanitize($_POST['shop_tagline']);
        $url = $this->sanitize($_POST['url']);

        $data1 = "name = '$name', username = '$username', password = '$password', type = 1";
        $data = "shop_name = '$shop_name', email = '$email', contact = '$contact', shop_tagline = '$shop_tagline', shop_url = '$url'";

        if ($_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
            $data .= ", cover_img = '$fname'";
        }

        $dbName = strtolower(str_replace(' ', '_', $shop_name)) . '_' . rand(1000, 9999);
        $data .= ", db_name = '$dbName'";

        $this->db->select_db('central_db');
        $chk = $this->db->query("SELECT * FROM shops WHERE email = '$email'");
        if ($chk->num_rows > 0) {
            $row = $chk->fetch_assoc();
            $existingDBName = $row['db_name'];

            $stmt = $this->db->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
            $stmt->bind_param("s", $existingDBName);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                if (createShopDatabase($existingDBName)) {
                    $chk1 = shopConn($existingDBName)->query("SELECT * FROM users WHERE username = '$username'")->num_rows;
                    if ($chk1 > 0) {
                        echo 0;
                        exit;
                    } else {
                        $save2 = shopConn($existingDBName)->query("INSERT INTO users SET $data1");
                        $data .= ", db_name = '$existingDBName'";
                        $save = $this->db->query("UPDATE shops SET $data WHERE email = '$email'");
                        if ($save2 && $save) {
                            echo 1;
                            exit;
                        } else {
                            echo 0;
                            exit;
                        }
                    }
                } else {
                    echo "error";
                    exit;
                }
            } else {
                echo 2;
                exit;
            }
            $stmt->close();
        } else {
            $save = $this->db->query("INSERT INTO shops SET $data");
            if ($save) {
                echo 2;
                exit;
            } else {
                echo "error";
            }
        }
    }

    function login()
    {
        $username = $this->sanitize($_POST['username']);
        $password = md5($this->sanitize($_POST['password']));
        $db_name = $this->sanitize($_POST['db_name']);
        $url = $this->sanitize($_POST['url']);

        $qry = shopConn($db_name)->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_array() as $key => $value) {
                if ($key != 'password' && !is_numeric($key)) {
                    $_SESSION['login_' . $key] = $value;
                }
            }
            $_SESSION['shop_db'] = $db_name;
            $_SESSION['shop_url'] = $url;
            return 1;
        } else {
            return 3;
        }
    }

    function admin_login()
    {
        $username = $this->sanitize($_POST['username']);
        $password = $this->sanitize($_POST['password']);

        $this->db->select_db('central_db');
        $qry = $this->db->query("SELECT * FROM admin WHERE username = '$username' AND password = '$password'");
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_array() as $key => $value) {
                if ($key != 'password' && !is_numeric($key)) {
                    $_SESSION['login_type'] = 3;
                }
            }
            return 1;
        } else {
            return 3;
        }
    }

    function login2()
    {
        $email = $this->sanitize($_POST['email']);
        $password = md5($this->sanitize($_POST['password']));

        $qry = $this->db->query("SELECT * FROM user_info WHERE email = '$email' AND password = '$password'");
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_array() as $key => $value) {
                if ($key != 'password' && !is_numeric($key)) {
                    $_SESSION['login_' . $key] = $value;
                }
            }
            $ip = $_SERVER['HTTP_CLIENT_IP'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']);
            $this->db->query("UPDATE cart SET user_id = '" . $_SESSION['login_user_id'] . "' WHERE client_ip ='$ip'");
            return 1;
        } else {
            return 3;
        }
    }

    function logout()
    {
        // Retrieve the shop_url before destroying the session
        $shop_url = isset($_SESSION['shop_url']) ? $_SESSION['shop_url'] : '';


        // Destroy the session
        session_destroy();

        // Redirect to the login page with the shop_url as a parameter
        header("location:login.php?shop_url=" . $shop_url);
        // exit(); // Make sure to call exit() after a header redirection
    }

    function admin_logout()
    {
        session_destroy();
        header("location:home.php");
    }

    function logout2()
    {
        session_destroy();
        header("location:../index.php");
    }

    function save_user()
    {
        $name = $this->sanitize($_POST['name']);
        $username = $this->sanitize($_POST['username']);
        $password = md5($this->sanitize($_POST['password']));
        $type = $this->sanitize($_POST['type']);
        $id = $this->sanitize($_POST['id'] ?? '');

        $data = "name = '$name', username = '$username', password = '$password', type = '$type'";
        if (empty($id)) {
            $save = $this->db_conn->query("INSERT INTO users SET $data");
            $returnValue = 1;
        } else {
            $save = $this->db_conn->query("UPDATE users SET $data WHERE id = $id");
            $returnValue = 2;
        }
        return $save ? $returnValue : 0;
    }

    function delete_user()
    {
        $id = $this->sanitize($_POST['id']);
        $delete = $this->db_conn->query("DELETE FROM users WHERE id = $id");
        return $delete ? 1 : 0;
    }

    function save_account()
    {
        $name = $this->sanitize($_POST['name']);
        $email = $this->sanitize($_POST['email']);
        $contact = $this->sanitize($_POST['contact']);
        $about = $this->sanitize($_POST['about']);

        $this->db->select_db('central_db');

        $data = "shop_name = '$name', email = '$email', contact = '$contact', shop_tagline = '$about'";

        if ($_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
            $data .= ", cover_img = '$fname'";
        }

        $chk = $this->db->query("SELECT * FROM shops");
        if ($chk->num_rows > 0) {
            $save = $this->db->query("UPDATE shops SET $data WHERE id =" . $chk->fetch_array()['id']);
            $returnValue = 1;
        } else {
            $save = $this->db->query("INSERT INTO shops SET $data");
            $returnValue = 2;
        }
        if ($save) {
            $query = $this->db->query("SELECT * FROM shops LIMIT 1")->fetch_array();
            foreach ($query as $key => $value) {
                if (!is_numeric($key)) {
                    $_SESSION['shop_' . $key] = $value;
                }
            }
            return $returnValue;
        }
    }

    function save_category()
    {
        $name = $this->sanitize($_POST['name']);
        $id = $this->sanitize($_POST['id'] ?? '');

        $data = "name = '$name'";
        echo $data;
        echo $id;
        $this->db->select_db('central_db');
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO categories SET $data");
            $returnValue = 1;
        } else {
            $save = $this->db->query("UPDATE categories SET $data WHERE id = $id");
            $returnValue = 2;
        }
        return $save ? $returnValue : 0;
    }

    function delete_category()
    {
        $id = $this->sanitize($_POST['id']);
        $this->db->select_db('central_db');
        $delete = $this->db->query("DELETE FROM categories WHERE id = $id");
        return $delete ? 1 : 0;
    }

    function save_expenses()
    {
        $expense = $this->sanitize($_POST['expense']);
        $description = $this->sanitize($_POST['description']);
        $amount = $this->sanitize($_POST['amount']);
        $id = $this->sanitize($_POST['id'] ?? '');

        $data = "expense = '$expense', description = '$description', amount = '$amount'";
        if (empty($id)) {
            $save = $this->db_conn->query("INSERT INTO expenses SET $data");
            $returnValue = 1;
        } else {
            $save = $this->db_conn->query("UPDATE expenses SET $data WHERE id = $id");
            $returnValue = 1;
        }
        return $save ? $returnValue : 0;
    }

    function delete_expenses()
    {
        $id = $this->sanitize($_POST['id']);
        $delete = $this->db_conn->query("DELETE FROM expenses WHERE id = $id");
        return $delete ? 1 : 0;
    }

    function save_supplier()
    {
        $name = $this->sanitize($_POST['name']);
        $contact = $this->sanitize($_POST['contact']);
        $address = $this->sanitize($_POST['address']);
        $id = $this->sanitize($_POST['id'] ?? '');

        $data = "name = '$name', contact = '$contact', address = '$address'";
        if (empty($id)) {
            $save = $this->db_conn->query("INSERT INTO suppliers SET $data");
            $returnValue = 1;
        } else {
            $save = $this->db_conn->query("UPDATE suppliers SET $data WHERE id = $id");
            $returnValue = 2;
        }
        return $save ? $returnValue : 0;
    }

    function delete_supplier()
    {
        $id = $this->sanitize($_POST['id']);
        $delete = $this->db_conn->query("DELETE FROM suppliers WHERE id = $id");
        return $delete ? 1 : 0;
    }

    function save_product()
    {
        $name = $this->sanitize($_POST['name']);
        $sku = $this->sanitize($_POST['sku']);
        $description = $this->sanitize($_POST['description']);
        $category_id = $this->sanitize($_POST['category_id']);
        $price = $this->sanitize($_POST['price']);
        $l_price = $this->sanitize($_POST['l_price']);
        $id = $this->sanitize($_POST['id'] ?? '');

        $data = "name = '$name', description = '$description', category_id = '$category_id', sku = '$sku', price = '$price', l_price = '$l_price'";

        if ($_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
            $data .= ", img_path = '$fname'";
        }

        if (empty($id)) {
            $save = $this->db_conn->query("INSERT INTO products SET $data");
            $returnValue = 1;
        } else {
            $save = $this->db_conn->query("UPDATE products SET $data WHERE id = $id");
            $returnValue = 2;
        }
        return $save ? $returnValue : 0;
    }

    function delete_product()
    {
        $id = $this->sanitize($_POST['id']);
        $qry = $this->db_conn->query("SELECT * FROM products WHERE id = $id")->fetch_array();
        $delete = $this->db_conn->query("DELETE FROM products WHERE id = $id");
        if ($delete) {
            unlink('assets/img/' . $qry['img_path']);
            return 1;
        }
    }

    function add_product()
    {
        $name = $this->sanitize($_POST['name']);
        $img_path = $this->sanitize($_POST['img']);
        $category_id = $this->sanitize($_POST['category_id']);
        $id = $this->sanitize($_POST['id'] ?? '');

        $data = "name = '$name', category_id = '$category_id'";

        if ($_FILES['p_img']['tmp_name'] != '') {
            $fname = $_FILES['p_img']['name'];
            $fname = strtolower($name);
            $fname = str_replace(' ', '_', $fname);
            move_uploaded_file($_FILES['p_img']['tmp_name'], 'assets/img/' . $fname);
            $data .= ", img_path = '$fname'";
        } else {
            $data .= ", img_path = '$img_path'";
        }

        $this->db->select_db('central_db');

        if (empty($id)) {
            $save = $this->db->query("INSERT INTO products SET $data");
            $returnValue = 1;
        } else {
            $save = $this->db->query("UPDATE products SET $data WHERE id = $id");
            $returnValue = 2;
        }
        return $save ? $returnValue : 0;
    }

    function remove_product()
    {
        $id = $this->sanitize($_POST['id']);

        $this->db->select_db('central_db');
        $qry = $this->db->query("SELECT * FROM products WHERE id = $id")->fetch_array();
        $delete = $this->db->query("DELETE FROM products WHERE id = $id");
        if ($delete) {
            unlink('assets/img/' . $qry['img_path']);
            return 1;
        }
    }

    function save_receiving()
    {
        $supplier_id = $this->sanitize($_POST['supplier_id']);
        $total_amount = $this->sanitize($_POST['total_amount']);
        $ref_no = $this->sanitize($_POST['ref_no']);
        $id = $this->sanitize($_POST['id'] ?? '');
        $product_ids = $_POST['product_id'];
        $qtys = $_POST['qty'];
        $prices = $_POST['price'];
        $b_prices = $_POST['b_price'];
        $s_prices = $_POST['s_price'];
        $inv_ids = $_POST['inv_id'] ?? [];

        $data = [
            'supplier_id' => $supplier_id,
            'total_amount' => $total_amount,
        ];

        if (empty($id)) {
            $ref_no = $this->generate_ref_no($ref_no, 'receiving');
            $data['ref_no'] = $ref_no;

            if (!empty($_FILES['img']['tmp_name'])) {
                $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
                move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
                $data['img_path'] = $fname;
            }

            $columns = implode(", ", array_keys($data));
            $values = implode("', '", array_values($data));
            $query = "INSERT INTO receiving ($columns) VALUES ('$values')";
            $save = $this->db_conn->query($query);

            $id = $this->db_conn->insert_id;
            $save2 = $this->save_inventory($id, $product_ids, $qtys, '1', 'receiving', $prices, $b_prices, $s_prices, 'in form receiving', $ref_no);

            return isset($save2) ? 1 : 0;
        } else {
            $update_data = "";
            foreach ($data as $key => $value) {
                $update_data .= "$key = '$value', ";
            }
            $update_data = rtrim($update_data, ', ');
            $query = "UPDATE receiving SET $update_data WHERE id = $id";
            $save = $this->db_conn->query($query);

            $ids = implode(",", $inv_ids);
            $this->db_conn->query("DELETE FROM inventory WHERE type = 1 AND form_id = $id AND id NOT IN ($ids)");

            $save2 = $this->save_inventory($id, $product_ids, $qtys, '1', 'receiving', $prices, $b_prices, $s_prices, 'in form receiving', $ref_no,  $inv_ids);

            return isset($save2) ? 2 : 0;
        }
    }

    function delete_receiving()
    {
        $id = $this->sanitize($_POST['id']);
        $delete = $this->db_conn->query("DELETE FROM receiving WHERE id = $id");
        if ($delete) {
            $this->db_conn->query("DELETE FROM inventory WHERE form_id = '$id' AND type = 1");
            return 1;
        }
    }

    function save_customer()
    {
        $name = $this->sanitize($_POST['name']);
        $contact = $this->sanitize($_POST['contact']);
        $address = $this->sanitize($_POST['address']);
        $id = $this->sanitize($_POST['id'] ?? '');

        $data = "name = '$name', contact = '$contact', address = '$address'";
        if (empty($id)) {
            $save = $this->db_conn->query("INSERT INTO customers SET $data");
            $returnValue = 1;
        } else {
            $save = $this->db_conn->query("UPDATE customers SET $data WHERE id = $id");
            $returnValue = 2;
        }
        return $save ? $returnValue : 0;
    }

    function delete_customer()
    {
        $id = $this->sanitize($_POST['id']);
        $delete = $this->db_conn->query("DELETE FROM customers WHERE id = $id");
        return $delete ? 1 : 0;
    }

    function chk_prod_availability()
    {
        $id = $this->sanitize($_POST['id']);
        $product = $this->db_conn->query("SELECT price, b_price, l_price, s_price FROM products WHERE id = $id")->fetch_assoc();
        $inn = $this->db_conn->query("SELECT SUM(qty) as inn FROM inventory WHERE type = 1 AND product_id = $id")->fetch_assoc()['inn'] ?? 0;
        $out = $this->db_conn->query("SELECT SUM(qty) as `out` FROM inventory WHERE type = 2 AND product_id = $id")->fetch_assoc()['out'] ?? 0;
        $available = $inn - $out;
        $data = [
            'available' => $available,
            'price' => $product['price'],
            'b_price' => $product['b_price'],
            'l_price' => $product['l_price'],
            's_price' => $product['s_price'],
        ];

        return json_encode($data);
    }


    function save_sales()
    {
        $customer_id = $this->sanitize($_POST['customer_id']);
        $actual_amount = $this->sanitize($_POST['aamount']);
        $total_amount = $this->sanitize($_POST['tamount']);
        // $paymode = $this->sanitize($_POST['paymode']);
        $amount_tendered = $this->sanitize($_POST['amount_tendered']);
        $change = $this->sanitize($_POST['change']);
        $ref_no = $this->sanitize($_POST['ref_no']);
        $id = isset($_POST['id']) ? $this->sanitize($_POST['id']) : '';
        $product_ids = $_POST['product_id'];
        $qtys = $_POST['qty'];
        $prices = $_POST['price'];
        $b_prices = $_POST['b_price'];
        $s_prices = $_POST['s_price'];
        $inv_ids = $_POST['inv_id'] ?? [];


        $data = "customer_id = '$customer_id', actual_amount = '$actual_amount', total_amount = '$total_amount', 
              amount_tendered = '$amount_tendered', amount_change = '$change'";

        if (empty($id)) {
            $ref_no = $this->generate_ref_no($ref_no, 'sales');
            $data .= ", ref_no = '$ref_no'";
            $data .= ($amount_tendered < $total_amount) ? ", paymode = 2" : ", paymode = 1";

            $save = $this->db_conn->query("INSERT INTO sales SET $data");
            if ($save) {
                $id = $this->db_conn->insert_id;
                $save2 = $this->save_inventory($id, $product_ids, $qtys, '2', 'sales', $prices, $b_prices, $s_prices, 'out from sales', $ref_no);
            }
            return isset($save2) ? $id : false;
        } else {

            $data .= ($amount_tendered < $total_amount) ? ", paymode = 2" : ", paymode = 1";
            $save = $this->db_conn->query("UPDATE sales SET $data WHERE id = $id");
            if ($save) {
                $ids = implode(",", $inv_ids);
                $this->db_conn->query("DELETE FROM inventory WHERE type = 2 AND form_id = '$id' AND id NOT IN ($ids)");
                $save2 = $this->save_inventory($id, $product_ids, $qtys, '2', 'sales', $prices, $b_prices, $s_prices, 'out from sales', $ref_no, $inv_ids);

                return isset($save2) ? $id : false;
            }
        }
    }

    function delete_sales()
    {
        $id = $this->sanitize($_POST['id']);
        $delete = $this->db_conn->query("DELETE FROM sales WHERE id = $id");
        if ($delete) {
            $this->db_conn->query("DELETE FROM inventory WHERE form_id = '$id' AND type = 2");
            return 1;
        }
    }

    function delete_shop()
    {
        $id = $this->sanitize($_POST['id']);

        $this->db->select_db('central_db');
        $profile_pic = $this->db->query("SELECT * FROM shops WHERE id = $id")->fetch_assoc()['cover_img'];
        $del_shop = $this->db->query("DELETE FROM shops where id = $id ");
        if ($del_shop) {
            $file_path = 'assets/img/' . $profile_pic;
            if (file_exists($file_path)) {
                unlink($file_path);
                return 1;
            } else {
                return 2;
            }
        }
    }

    function save_credit()
    {
        // Extract and sanitize input data
        $customer_id = $this->sanitize($_POST['customer_id']);
        $paying = floatval($_POST['paying']);

        // Fetch sales list where payment mode is credit for the specified customer
        $chk = $this->db_conn->query("SELECT * FROM sales WHERE paymode = 2 AND customer_id = '$customer_id' ORDER BY total_amount ASC");

        // Process each row in the result set
        while ($row = $chk->fetch_assoc()) {
            // Assign values from the row to variables
            $id = $row['id'];
            $total_amount = floatval($row['total_amount']);
            $amount_tendered = floatval($row['amount_tendered']);
            $amount_change = floatval($row['amount_change']) * -1;

            if ($paying >= $amount_change) {
                $amount_tendered += $total_amount;
                $amount_change -= $total_amount;
                $paying -= $total_amount;

                // Update the sales list to mark the payment as settled
                $update_sales = $this->db_conn->query("UPDATE sales SET paymode = 1, amount_change = '$amount_change', amount_tendered = '$amount_tendered' WHERE id = '$id'");

                if ($update_sales) {
                    return $id;
                }
            } elseif ($paying < $amount_change) {
                $amount_change = $paying - $total_amount;
                $amount_tendered += $paying;
                $paying = 0;

                // Update the sales list to mark the payment as partially settled
                $update_sales = $this->db_conn->query("UPDATE sales SET amount_change = '$amount_change', amount_tendered = '$amount_tendered' WHERE id = '$id'");

                if ($update_sales) {
                    return $id;
                }
            }
        }

        // Return an error code if no updates were made
        return 0;
    }
}
