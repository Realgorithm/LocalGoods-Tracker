<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Admin | Groceries Sales and Inventory System</title>


	<?php
	include 'header.php';
	ob_start(); // Start output buffering

	include 'db_connect.php';

	$response = ob_get_clean(); // Get the output and clear the buffer

	$title = '';
	$body = '';

	// Check if response is a valid JSON
	if ($json_response = json_decode($response, true)) {
		$title = $json_response['title'];
		$body = $json_response['body'];
	} else {

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
				$title = "Shop not found.";
				$body = "The URL you enter is wrong or contact to the administrative. please check your URL before contact with us.";
			} else {
				// $query = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
				foreach ($shop as $key => $value) {
					$meta[$key] = $value;
				}
				$_SESSION['shop_name'] = $meta['shop_name'];
				$_SESSION['shop_img'] = $meta['cover_img'];
			}
		} else {
			$title = "shop url is missing";
			$body = "Please enter your shop url after login.php?shopurl='yourshopurl' or go to home page and enter url their to go to login page.";
		}
	} ?>
	<?php
	if ($title != '' and $body != '') : ?>
		<script>
			$(document).ready(function() {
				$('.login-fail').show();
				$('.main-content').hide();
				$('.login-fail .title').text("<?php echo $title; ?>");
				$('.login-fail .body').text("<?php echo $body; ?>");
			});
		</script>
	<?php endif; ?>

</head>

<body>

	<!-- Main Content -->
	<?php include 'loader.php' ?>
	<div class="container-fluid">
		<div class="container login-fail" style="display: none;">
			<div class="card m-5">
				<h3><b>
						<div class="card-header title"></div>
					</b></h3>
				<div class="card-body text-center">
					<img src="assets/img/login_url_error.jpg" alt="" width="50%" height="auto">
					<div class="body"></div><br>
					<button type="button" class="btn btn-success"><a href="home.php" class="btn">Home</a></button>
				</div>
				<div class="card-footer"></div>
			</div>
		</div>
		<div class="row main-content bg-success text-center">
			<div class="col-md-4 col-sm-4 col-12 company__info">
				<a href="home.php" class="btn btn-primary btn_login">Home</a>
				<span class="company__logo">
					<h2><img src="<?php echo $meta['cover_img'] != '' ? 'assets/img/' . $meta['cover_img'] : 'assets/img/company.png' ?>" alt="" width="100%" length="100%"></h2>
				</span>
				<h4 class="company_title"><?php echo isset($meta['shop_name']) ? $meta['shop_name'] : '' ?></h4>
				<h5><?php echo isset($meta['shop_tagline']) ? $meta['shop_tagline'] : '' ?></h5>
			</div>
			<div class="col-md-8 col-sm-8 col-12 login_form d-flex flex-column justify-content-between">
				<h2 class="py-2"><b>LOGIN</b></h2>
				<form control="" class="needs-validation" id="login-form" novalidate>
					<div class="row">
						<input type="text" name="username" id="username" class="form-control form__input" placeholder="Username" required>
						<div class="invalid-feedback">
							Please provide your username.
						</div>
						<div class="valid-feedback">
							Look at this username!
						</div>
					</div>
					<div class="row">
						<!-- <span class="fa fa-lock"></span> -->
						<input type="password" name="password" id="password" class="form-control form__input" placeholder="Password" required>
						<div class="invalid-feedback">
							Please provide your password.
						</div>
						<div class="valid-feedback">
							Pretty good password!
						</div>
					</div>
					<input type="hidden" id="url" name="url" value="<?php echo $shop_url; ?>">
					<input type="hidden" id="db_name" name="db_name" value="<?php echo $meta['db_name']; ?>">
					<div class="row justify-content-center">
						<button class="btn btn_login">Log In</button>
					</div>
				</form>
				<p><span class="mark">Don't have an account? <a href="signup.php">Register Here</a></span></p>
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
	$(document).ready(function() {
		'use strict';

		$('.needs-validation').each(function() {
			var form = $(this);
			form.on('submit', function(event) {
				event.preventDefault(); // Prevent the default form submission

				start_load();

				// Check if the form is valid
				if (form[0].checkValidity() === false) {
					event.stopPropagation();
					end_load();
					form.addClass('was-validated');
					return false;
				}

				// Disable the login button and change its text
				const $loginButton = $('#login-form button.btn_login');
				$loginButton.attr('disabled', true).text('Logging in...');

				// Clear any previous error messages
				$('#login-form .alert-danger').remove();

				$.ajax({
					url: 'ajax.php?action=login',
					method: 'POST',
					data: form.serialize(),
					success: function(resp) {
						// Handle successful response
						switch (resp) {
							case '1':
								location.href = 'index.php?page=dashboard';
								break;
							case '2':
								location.href = 'error.html';
								break;
							default:
								$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
								break;
						}
					},
					error: err => {
						// Handle errors
						console.error('Login error:', err);
						$('#login-form').prepend('<div class="alert alert-danger">An error occurred. Please try again.</div>');

					},
					complete: function() {
						// Re-enable the login button and reset its text
						$loginButton.removeAttr('disabled').text('Log In');
					}
				});
			});
		});
	});
</script>

</html>