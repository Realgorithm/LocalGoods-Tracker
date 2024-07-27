<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Admin | Groceries Sales and Inventory System</title>


	<?php include('./header.php'); ?>
	<?php include('./db_connect.php'); ?>
	<?php
	session_start();
	if (isset($_SESSION['login_id']))
		header("location:index.php?page=dashboard");

	if (isset($_GET['shop_url']) and $_GET['shop_url'] != '') {

		$shop_url = $_GET['shop_url'];

		$conn->select_db('central_db');
		$query = $conn->prepare("SELECT * FROM shops WHERE shop_url = ?");
		$query->bind_param("s", $shop_url);
		$query->execute();
		$result = $query->get_result();
		$shop = $result->fetch_assoc();

		if (!$shop) {
			echo "Shop not found.";
			exit();
		}
	} else {
		echo "Please enter your your shop url after login.php";
		exit();
	}

	// $query = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	foreach ($shop as $key => $value) {
		$meta[$key] = $value;
	}
	$_SESSION['shop_name'] = $meta['shop_name'];
	$_SESSION['shop_img'] = $meta['cover_img'];
	?>

</head>
<!-- Main Content -->
<div class="container-fluid">
	<div class="row main-content bg-success text-center">
		<div class="col-md-4 text-center company__info">
			<a href="home.php" class="btn_login">Home</a>
			<span class="company__logo">
				<h2><img src="<?php echo $meta['cover_img'] != '' ? 'assets/img/' . $meta['cover_img'] : 'assets/img/1600398180_no-image-available.png' ?>" alt="" width="100%" length="100%"></h2>
			</span>
			<h4 class="company_title"><?php echo isset($meta['shop_name']) ? $meta['shop_name'] : '' ?></h4>
			<h5><?php echo isset($meta['shop_tagline']) ? $meta['shop_tagline'] : '' ?></h5>
		</div>
		<div class="col-md-8 col-xs-12 col-sm-12 login_form ">
			<div class="container-fluid">
				<div class="row">
					<h2>Log In</h2>
				</div>
				<div class="row">
					<form control="" class="form-group" id="login-form">
						<div class="row">
							<input type="text" name="username" id="username" class="form__input" placeholder="Username" required>
						</div>
						<div class="row">
							<!-- <span class="fa fa-lock"></span> -->
							<input type="password" name="password" id="password" class="form__input" placeholder="Password" required>
						</div>
						<input type="hidden" id="url" name="url">
						<input type="hidden" id="db_name" name="db_name">
						<div class="row">
							<button class="btn_login">Login</button>
						</div>
					</form>
				</div>
				<div class="row">
					<p>Don't have an account? <a href="signup.php">Register Here</a></p>
				</div>

			</div>
		</div>
	</div>
</div>
<!-- Footer -->
<div class="container-fluid text-center footer">
	Coded with &hearts; by <a href="https://github.com/Realgorithm" target="_blank">Tabish</a></p>
</div>

<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
	$('#login-form').submit(function(e) {
		e.preventDefault()
		var shop_url = "<?php echo $shop_url; ?>"; // PHP variable embedded in JavaScript
		//console.log(shop_url);
		$('#url').val(shop_url);
		var db_name = "<?php echo $meta['db_name']; ?>"; // PHP variable embedded in JavaScript
		console.log(db_name);
		$('#db_name').val(db_name);
		$('#login-form button[class="btn_login"]').attr('disabled', true).html('Logging in...');
		if ($(this).find('.alert-danger').length > 0)
			$(this).find('.alert-danger').remove();
		$.ajax({
			url: 'ajax.php?action=login',
			method: 'POST',
			data: $(this).serialize(),
			error: err => {
				//console.log(err)
				$('#login-form button[class="btn_login"]').removeAttr('disabled').html('Login');

			},
			success: function(resp) {
				console.log(resp);
				if (resp == 1) {
					location.href = 'index.php?page=dashboard';
				} else if (resp == 2) {
					location.href = 'error.html';
				} else {
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[class="btn_login"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>

</html>