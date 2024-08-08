<?php
include('db_connect.php');
session_start();
$dbName = $_SESSION['shop_db'];
if (isset($_GET['id'])) {
	$user = shopConn($dbName)->query("SELECT * FROM users where id =" . $_GET['id']);
	foreach ($user->fetch_array() as $k => $v) {
		$meta[$k] = $v;
	}
}
?>
<div class="container-fluid">

	<form action="" id="manage-user" class="needs-validation" novalidate>
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">
		<div class="mb-3">
			<label for="name" class="form-label">Name</label>
			<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required>
			<div class="invalid-feedback">
				Please provide your name.
			</div>
		</div>
		<div class="mb-3">
			<label for="username" class="form-label">Username</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" required>
			<div class="invalid-feedback">
				Please provide your username.
			</div>
		</div>
		<div class="mb-3">
			<label for="password" class="form-label">New Password</label>
			<input type="password" name="password" id="password" class="form-control" value="" required>
			<div class="invalid-feedback">
				Please provide your password.
			</div>
		</div>
		<div class="mb-3">
			<label for="type" class="form-label">User Type</label>
			<select name="type" id="type" class="form-select">
				<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Admin</option>
				<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Cashier</option>
			</select>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		'use strict';
		$('#manage-user').each(function() {
			var form = $(this);
			form.on('submit', function(e) {
				e.preventDefault()
				start_load()
				// Check if the form is valid
				if (form[0].checkValidity() === false) {
					e.preventDefault();
					e.stopPropagation();
					end_load();
					form.addClass('was-validated');
					return false;
				}
				$.ajax({
					url: 'ajax.php?action=save_user',
					method: 'POST',
					data: $(this).serialize(),
					success: function(resp) {
						//console.log(resp);
						if (resp == 1) {
							alert_toast("Data successfully saved", 'success')
							setTimeout(function() {
								location.reload()
							}, 1500)
						} else if (resp == 2) {
							alert_toast("Data successfully updated", 'success')
							setTimeout(function() {
								location.reload()
							}, 1500)

						} else {
							alert_toast("An error occurred. Please try again.", 'danger')
							setTimeout(function() {
								location.reload()
							}, 1500)
						}
					}
				})
				form.addClass('was-validated');
				e.preventDefault(); // Prevent default form submission
			})
		})
	});
</script>