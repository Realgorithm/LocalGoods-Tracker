<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Admin | Localgoods-Tracker</title>


    <?php include('./header.php'); ?>
    <?php include('./db_connect.php'); ?>

</head>

<body>
    <!-- Main Content -->
    <?php include 'loader.php' ?>
    <div class="container-fluid">
        <div class="row main-content bg-success text-center">
            <div class="col-md-4 col-sm-4 col-12 company__info">
                <a href="home.php" class="btn_login btn btn-primary">Home</a>
                <span class="company__logo">
                    <h2><img src="assets/img/company.png" alt="" width="100%" length="100%"></h2>
                </span>
                <h4 class="company_title">LocalGoods-Tracker</h4>
                <h5>Goods Manager</h5>
            </div>
            <div class="col-md-8 col-sm-8 col-12 login_form d-flex flex-column justify-content-between">
                <h2>Log In</h2>
                <form control="" class="form-group" id="admin-login-form">
                    <div class="row">
                        <input type="text" name="username" id="username" class="form__input" placeholder="Username" required>
                    </div>
                    <div class="row">
                        <!-- <span class="fa fa-lock"></span> -->
                        <input type="password" name="password" id="password" class="form__input" placeholder="Password" required>
                    </div>
                    <div class="row justify-content-center">
                        <button class="btn btn_login">Log In</button>
                    </div>
                </form>
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
        $('#admin-login-form').submit(function(e) {
            e.preventDefault()
            // Disable the login button and change its text
            const $loginButton = $('#admin-login-form button.btn_login');
            $loginButton.attr('disabled', true).text('Logging in...');

            // Clear any previous error messages
            $('#login-form .alert-danger').remove();

            $.ajax({
                url: 'ajax.php?action=admin_login',
                method: 'POST',
                data: $(this).serialize(),
                error: err => {
                    // Handle errors
                    console.error('Login error:', err);
                    $('#admin-login-form').prepend('<div class="alert alert-danger">An error occurred. Please try again.</div>');

                },
                success: function(resp) {
                    console.log(resp);
                    if (resp == 1) {
                        location.href = 'index.php?page=shops';
                    } else if (resp == 2) {
                        location.href = 'error.html';
                    } else {
                        $('#admin-login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
                    }
                },
                complete: function() {
                    // Re-enable the login button and reset its text
                    $loginButton.removeAttr('disabled').text('Log In');
                }
            })
        })
    });
</script>

</html>