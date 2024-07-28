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
			$this->db_conn = shop_conn($this->db_name);
			// echo $GLOBALS['shopDb'];
		}
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);
		$qry = shop_conn($db_name)->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		// echo md5($password)."<br>";
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				// echo $key. "  ";
				$meta[$key] = $value;
				if ($key != 'passwors' && !is_numeric($key)) {
					$_SESSION['login_' . $key] = $value;
				}
			}
			// echo $_SESSION['shop_id'];
			// echo $meta['shop_id'];
			$_SESSION['shop_db'] = $db_name;
			$_SESSION['shop_url'] = $url;
			return 1;
		} else {
			return 3;
		}
	}
	function admin_login()
	{
		extract($_POST);
		$this->db->select_db('central_db');
		$qry = $this->db->query("SELECT * FROM admin where username = '" . $username . "' and password = '" . $password . "' ");
		// echo md5($password)."<br>";
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				// echo $key. "  ";
				$meta[$key] = $value;
				if ($key != 'passwors' && !is_numeric($key)) {
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
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM user_info where email = '" . $email . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			$ip = (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
			$this->db->query("UPDATE cart set user_id = '" . $_SESSION['login_user_id'] . "' where client_ip ='$ip' ");
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{

		header("location:login.php?shop_url=" . $_SESSION['shop_url']);
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
	}
	function admin_logout()
	{

		header("location:home.php");
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
	}
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = '$type' ";
		if (empty($id)) {
			$save = $this->db_conn->query("INSERT INTO users set " . $data);
		} else {
			$save = $this->db_conn->query("UPDATE users set " . $data . " where id = " . $id);
		}
		if ($save) {
			return 1;
		}
	}

	function delete_user()
	{
		extract($_POST);
		$delete = $this->db_conn->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}
	function signup()
	{
		extract($_POST);
		// Get form data
		$data1 = "name = '$name', username = '$username', password = '" . md5($password) . "', type = 1";
		$data = "shop_name = '$shop_name', email = '$email', contact = '$contact', shop_tagline = '$shop_tagline', shop_url = '$url'";

		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
			$data .= ", cover_img = '$fname'";
		}
		$dbName = strtolower(str_replace(' ', '_', $shop_name)) . '_' . rand(1000, 9999);
		$data .= ", db_name = '$dbName' ";

		// Switch to central database
		$this->db->select_db('central_db');
		$chk = $this->db->query("SELECT * FROM shops where email = '$email' ");

		if ($chk->num_rows > 0) {
			$row = $chk->fetch_assoc();
			$existingDBName = $row['db_name'];

			// Check if database exists
			// Check if database exists
			$stmt = $this->db->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
			$stmt->bind_param("s", $existingDBName);
			$stmt->execute();
			$stmt->store_result();

			if ($stmt->num_rows > 0) {
				if (createShopDatabase($existingDBName)) {
					$chk1 = shop_conn($existingDBName)->query("SELECT * FROM users WHERE username = '$username'")->num_rows;
					if ($chk1 > 0) {
						echo 0;
						exit;
					} else {
						$save2 = shop_conn($existingDBName)->query("INSERT INTO users SET $data1");
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

	function save_settings()
	{
		extract($_POST);
		$this->db->select_db('central_db');

		$data = " shop_name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}
		$data .= ", shop_tagline = '$about' ";

		// echo "INSERT INTO shops set ".$data;
		$chk = $this->db->query("SELECT * FROM shops");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE shops set " . $data . " where id =" . $chk->fetch_array()['id']);
		} else {
			$save = $this->db->query("INSERT INTO shops set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM shops limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['shop_' . $key] = $value;
			}

			return 1;
		}
	}

	function save_category()
	{
		extract($_POST);
		$data = " name = '$name' ";

		$this->db->select_db('central_db');
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO category_list set " . $data);
		} else {
			$save = $this->db->query("UPDATE category_list set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_category()
	{
		extract($_POST);
		$this->db->select_db('central_db');
		$delete = $this->db->query("DELETE FROM category_list where id = " . $id);
		if ($delete)
			return 1;
	}

	function save_expenses()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", price = '$price' ";
		if (empty($id)) {
			$save = $this->db_conn->query("INSERT INTO expenses_list set " . $data);
		} else {
			$save = $this->db_conn->query("UPDATE expenses_list set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_expenses()
	{
		extract($_POST);
		$delete = $this->db_conn->query("DELETE FROM expenses_list where id = " . $id);
		if ($delete)
			return 1;
	}

	function save_supplier()
	{
		extract($_POST);
		$data = " supplier_name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		if (empty($id)) {
			$save = $this->db_conn->query("INSERT INTO supplier_list set " . $data);
		} else {
			$save = $this->db_conn->query("UPDATE supplier_list set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_supplier()
	{
		extract($_POST);
		$delete = $this->db_conn->query("DELETE FROM supplier_list where id = " . $id);
		if ($delete)
			return 1;
	}

	function save_product()
	{
		extract($_POST);
		$data = " name = '$product_name' ";
		$data .= ", sku = '$sku' ";
		$data .= ", image = '$img' ";
		$data .= ", category_id = '$category_id' ";
		$data .= ", description = '$description' ";
		$data .= ", price = '$price' ";
		$data .= ", l_price = '$l_price' ";

		// echo $img;

		if (empty($id)) {
			$save = $this->db_conn->query("INSERT INTO product_list set " . $data);
			// echo $save;
		} else {
			$save = $this->db_conn->query("UPDATE product_list set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_product()
	{
		extract($_POST);
		$profile_pic = $this->db_conn->query("SELECT * FROM product_list WHERE id = $id")->fetch_assoc()['image'];
		$delete = $this->db_conn->query("DELETE FROM product_list where id = " . $id);
		if ($delete) {
			// Step 3: Delete the profile picture file from the server
			$file_path = $profile_pic;
			if (file_exists($file_path)) {
				unlink($file_path);
				return 1;
			} else {
				return 2;
			}
		}
	}

	function add_product()
	{
		extract($_POST);
		$data = " name = '$product_name' ";
		$data .= ", image = '$img' ";
		$data .= ", category_id = '$category_id' ";

		if ($_FILES['img']['tmp_name'] != '') {
			$fname = $_FILES['img']['name'];
			// Convert to lowercase
			$fname = strtolower($fname);
			// Replace spaces with underscores
			$fname = str_replace(' ', '_', $fname);
			// Move the uploaded file
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
			$data .= ", image = '$fname' ";
		}

		// echo $fname;
		$this->db->select_db('central_db');

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO products set " . $data);
			// echo $save;
		} else {
			$save = $this->db->query("UPDATE products set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}

	function remove_product()
	{
		extract($_POST);
		$this->db->select_db('central_db');
		$profile_pic = $this->db->query("SELECT * FROM products WHERE id = $id")->fetch_assoc()['image'];
		$delete = $this->db->query("DELETE FROM products where id = " . $id);
		if ($delete) {
			// Step 3: Delete the profile picture file from the server
			$file_path = 'assets/img/' . $profile_pic;
			if (file_exists($file_path)) {
				unlink($file_path);
				return 1;
			} else {
				return 2;
			}
		}
	}

	function save_receiving()
	{
		extract($_POST);
		$data = " supplier_id = '$supplier_id' ";
		$data .= ", total_amount = '$tamount' ";

		if (empty($id)) {
			$ref_no = sprintf("%'.08d\n", $ref_no);
			$i = 1;

			while ($i == 1) {
				$chk = $this->db_conn->query("SELECT * FROM receiving_list where ref_no ='$ref_no'")->num_rows;
				if ($chk > 0) {
					$ref_no = mt_rand(1, 99999999);
					$ref_no = sprintf("%'.08d\n", $ref_no);
				} else {
					$i = 0;
				}
			}
			$data .= ", ref_no = '$ref_no' ";
			$save = $this->db_conn->query("INSERT INTO receiving_list set " . $data);
			$id = $this->db_conn->insert_id;
			foreach ($product_id as $k => $v) {
				$data = " form_id = '$id' ";
				$data .= ", product_id = '$product_id[$k]' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", type = '1' ";
				$data .= ", stock_from = 'receiving' ";
				$details = json_encode(array('price' => $price[$k], 'b_price' => $b_price[$k], 'qty' => $qty[$k]));
				$data .= ", other_details = '$details' ";
				$data .= ", remarks = 'Stock from Receiving-" . $ref_no . "' ";

				$save_product = $this->db_conn->query("UPDATE product_list SET b_price = '$b_price[$k]' WHERE id = '$product_id[$k]'");
				$save2[] = $this->db_conn->query("INSERT INTO inventory set " . $data);
			}
			if (isset($save2)) {
				return 1;
			}
		} else {
			$save = $this->db_conn->query("UPDATE receiving_list set " . $data . " where id =" . $id);
			$ids = implode(",", $inv_id);
			$this->db_conn->query("DELETE FROM inventory where type = 1 and form_id =$id and id NOT IN (" . $ids . ") ");
			foreach ($product_id as $k => $v) {
				$data = " form_id = '$id' ";
				$data .= ", product_id = '$product_id[$k]' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", type = '1' ";
				$data .= ", stock_from = 'receiving' ";
				$details = json_encode(array('price' => $price[$k], 'b_price' => $b_price[$k], 'qty' => $qty[$k]));
				$data .= ", other_details = '$details' ";
				$data .= ", remarks = 'Stock from Receiving-" . $ref_no . "' ";
				if (!empty($inv_id[$k])) {
					$save2[] = $this->db_conn->query("UPDATE inventory set " . $data . " where id=" . $inv_id[$k]);
					$save_product = $this->db_conn->query("UPDATE product_list SET b_price = '$b_price[$k]' WHERE id = '$product_id[$k]'");
				} else {
					$save2[] = $this->db_conn->query("INSERT INTO inventory set " . $data);
				}
			}
			if (isset($save2)) {

				return 1;
			}
		}
	}
	function delete_receiving()
	{
		extract($_POST);
		$del1 = $this->db_conn->query("DELETE FROM receiving_list where id = $id ");
		$del2 = $this->db_conn->query("DELETE FROM inventory where type = 1 and form_id = $id ");
		if ($del1 && $del2)
			return 1;
	}

	function save_customer()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		if (empty($id)) {
			$save = $this->db_conn->query("INSERT INTO customer_list set " . $data);
		} else {
			$save = $this->db_conn->query("UPDATE customer_list set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_customer()
	{
		extract($_POST);
		$delete = $this->db_conn->query("DELETE FROM customer_list where id = " . $id);
		if ($delete)
			return 1;
	}

	function chk_prod_availability()
	{
		extract($_POST);
		$price = $this->db_conn->query("SELECT * FROM product_list where id = " . $id)->fetch_assoc()['price'];
		$b_price = $this->db_conn->query("SELECT * FROM product_list where id = " . $id)->fetch_assoc()['b_price'];
		$l_price = $this->db_conn->query("SELECT * FROM product_list where id = " . $id)->fetch_assoc()['l_price'];
		$s_price = $this->db_conn->query("SELECT * FROM product_list where id = " . $id)->fetch_assoc()['s_price'];
		$inn = $this->db_conn->query("SELECT sum(qty) as inn FROM inventory where type = 1 and product_id = " . $id);
		$inn = $inn && $inn->num_rows > 0 ? $inn->fetch_array()['inn'] : 0;
		$out = $this->db_conn->query("SELECT sum(qty) as `out` FROM inventory where type = 2 and product_id = " . $id);
		$out = $out && $out->num_rows > 0 ? $out->fetch_array()['out'] : 0;
		$available = $inn - $out;
		return json_encode(array('available' => $available, 'price' => $price, 'b_price' => $b_price, 'l_price' => $l_price, 's_price' => $s_price));
	}

	function save_sales()
	{
		extract($_POST);
		$data = " customer_id = '$customer_id' ";
		$data .= ", actual_amount = '$aamount'";
		$data .= ", total_amount = '$tamount' ";
		$data .= ", paymode = '$paymode' ";
		$data .= ", amount_tendered = '$amount_tendered' ";
		$data .= ", amount_change = '$change' ";

		if (empty($id)) {
			$ref_no = sprintf("%'.08d\n", $ref_no);
			$i = 1;

			while ($i == 1) {
				$chk = $this->db_conn->query("SELECT * FROM sales_list where ref_no ='$ref_no'")->num_rows;
				if ($chk > 0) {
					$ref_no = mt_rand(1, 99999999);
					$ref_no = sprintf("%'.08d\n", $ref_no);
				} else {
					$i = 0;
				}
			}
			$data .= ", ref_no = '$ref_no' ";
			$save = $this->db_conn->query("INSERT INTO sales_list set " . $data);
			if ($save) {
				$id = $this->db_conn->insert_id;
			}
			// echo $this->db_name;
			foreach ($product_id as $k => $v) {
				$data = " form_id = '$id' ";
				$data .= ", product_id = '$product_id[$k]' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", type = '2' ";
				$data .= ", stock_from = 'Sales' ";
				$details = json_encode(array('price' => $price[$k], 's_price' => $s_price[$k], 'b_price' => $b_price[$k], 'qty' => $qty[$k]));
				$data .= ", other_details = '$details' ";
				$data .= ", remarks = 'Stock out from Sales-" . $ref_no . "' ";
				$save_product = $this->db_conn->query("UPDATE product_list SET s_price = '$s_price[$k]' WHERE id = '$product_id[$k]'");

				$save2[] = $this->db_conn->query("INSERT INTO inventory set " . $data);
			}
			if (isset($save2)) {
				return $id;
			}
		} else {
			$save = $this->db_conn->query("UPDATE sales_list set " . $data . " where id=" . $id);
			$ids = implode(",", $inv_id);
			$this->db_conn->query("DELETE FROM inventory where type = 1 and form_id ='$id' and id NOT IN (" . $ids . ") ");
			foreach ($product_id as $k => $v) {
				$data = " form_id = '$id' ";
				$data .= ", product_id = '$product_id[$k]' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", type = '2' ";
				$data .= ", stock_from = 'Sales' ";
				$details = json_encode(array('price' => $price[$k], 's_price' => $s_price[$k], 'qty' => $qty[$k]));
				$data .= ", other_details = '$details' ";
				$data .= ", remarks = 'Stock out from Sales-" . $ref_no . "' ";

				if (!empty($inv_id[$k])) {
					$save_product = $this->db_conn->query("UPDATE product_list SET s_price = '$s_price[$k]' WHERE id = '$product_id[$k]'");

					$save2[] = $this->db_conn->query("UPDATE inventory set " . $data . " where id=" . $inv_id[$k]);
				} else {
					$save2[] = $this->db_conn->query("INSERT INTO inventory set " . $data);
				}
			}
			if (isset($save2)) {
				return $id;
			}
		}
	}

	function delete_sales()
	{
		extract($_POST);
		// echo $this->db_name;
		$del1 = $this->db_conn->query("DELETE FROM sales_list where id = $id ");
		$del2 = $this->db_conn->query("DELETE FROM inventory where type = 2 and form_id = $id ");
		if ($del1 && $del2)
			return 1;
	}
	function delete_shop()
	{
		extract($_POST);
		// echo $this->db_name;
		$this->db->select_db('central_db');
		$profile_pic = $this->db->query("SELECT * FROM shops WHERE id = $id")->fetch_assoc()['cover_img'];
		$del1 = $this->db->query("DELETE FROM shops where id = $id ");
		if ($del1) {
			// Step 3: Delete the profile picture file from the server
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
		extract($_POST);

		// echo $amount;
		// echo $paying;
		// echo $customer_id;
		$chk = $this->db_conn->query("SELECT * FROM sales_list where paymode = 2 AND customer_id = '$customer_id'");
		// echo $chk->num_rows;
		while ($row = $chk->fetch_assoc()) {
			foreach ($row as $k => $val) {
				$$k = $val;
				// echo $$k;
			}
			// echo "amount : " . $amount;
			// echo "quantity : " . $quantity;
			// echo "total : " . $total;
			// echo "totalamount : " . $total_amount;
			$paying = $paying + $pay_unsettled;
			// echo "paying : " . $paying;
			if ($paying >= $amount) {
				// echo $id;
				// echo "amnt/qnt" . $amount / $quantity;
				// Perform the division in PHP
				$total_amount = $amount / $quantity;

				// Update the sales_list with the calculated total_amount
				$save = $this->db_conn->query("UPDATE sales_list SET paymode = 1 WHERE id = '$id' AND total_amount = '$total_amount'");
				$save2 = $this->db_conn->query("UPDATE customer_list SET pay_unsettled = 0 WHERE id = '$customer_id'");
				$paying = $paying - $amount;
				// echo "pay" . $paying;
				// Check if the query was successful
				if ($save and $save2) {
					return $id;
				}
			} else {
				$total = $total - $paying;
				// echo "total" . $total;
				$save = $this->db_conn->query("UPDATE customer_list SET pay_unsettled = '$paying' WHERE id = '$customer_id'");
				if ($save) {
					return $id;
				}
			}
		}
	}
}
