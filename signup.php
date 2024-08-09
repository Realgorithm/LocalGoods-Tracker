<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Localgoods-Tracker</title>

    <?php include('./header.php'); ?>
    <?php include('./db_connect.php'); ?>
</head>

<body>
    <?php include 'loader.php' ?>
    <div class="container-fluid">
        <div class="row main-content bg-success text-center">
            <div class="col-md-3 col-sm-4 col-12 company__info">
                <a href="home.php" class="btn btn-primary btn_login">Home</a>
                <h2><img src="assets/img/company.png" alt="Company Logo" class="img-fluid"></h2>
                <h4 class="company_title">LocalGoods-Tracker</h4>
            </div>
            <div class="col-md-9 col-sm-8 col-12 login_form ">
                <h2 class="py-2"><b>SIGNUP</b></h2>
                <form id="signup-form" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="name" id="name" class="form-control form__input" placeholder="Name" required>
                            <div class="invalid-feedback">
                                Please provide your name.
                            </div>
                            <div class="valid-feedback">
                                What a name!
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="shop_name" id="shop_name" class="form-control form__input" placeholder="Shop Name" required>
                            <div class="invalid-feedback">
                                Please provide your shop name.
                            </div>
                            <div class="valid-feedback">
                                Fabulous Shop name!
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="username" id="username" class="form-control form__input" placeholder="Username" required>
                            <div class="invalid-feedback">
                                Please provide your username.
                            </div>
                            <div class="valid-feedback">
                                Look at this username!
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="password" name="password" id="password" class="form-control form__input" placeholder="Password" required>
                            <div class="invalid-feedback">
                                Please provide your password.
                            </div>
                            <div class="valid-feedback">
                                Pretty good password!
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="email" name="email" id="email" class="form-control form__input" placeholder="Email" required>
                            <div class="invalid-feedback">
                                Please provide a valid email.
                            </div>
                            <div class="valid-feedback">
                                genuine Email!
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="img" class="form-control form__input" style="background-color: #fff; text-align:left">Shop Image</label>
                            <input type="file" name="img" id="img" class="form-control-file form__input" accept=".jpg, .jpeg, .png, .gif" hidden required>
                            <div class="invalid-feedback">
                                Please upload an image (less than 1MB).
                            </div>
                            <div class="valid-feedback">
                                Picture Perfect!
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="contact" id="contact" class="form-control form__input" placeholder="Contact" pattern="\d{10}" required>
                            <div class="invalid-feedback">
                                Contact number should be exactly 10 digits long.
                            </div>
                            <div class="valid-feedback">
                                Contact connects!
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="shop_tagline" id="shop_tagline" class="form-control form__input" placeholder="Shop Tagline">
                        </div>
                        <div class="valid-feedback">
                            Eye Catching tagline!
                        </div>
                    </div>
                    <input type="hidden" id="url" name="url">
                    <div class="row justify-content-center">
                        <button class="btn btn-primary btn_login" type="submit">Sign Up</button>
                    </div>
                </form>
                <form id="already-user" method="POST" style="display: none;">
                    <div class="row">
                        <input type="text" name="shop_url" id="shop_url" class="form__input" placeholder="Shop URL">
                    </div>
                    <div class="row justify-content-center">
                        <button class="btn btn_login">Login Page</button>
                    </div>
                </form>
                <p id="already_acc"><span class="mark">Already have an account? <button class="btn_login" onclick="alreadyUser()">Click Here</button></span></p>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <div class="container-fluid text-center footer">
        Coded with &hearts; by <a href="https://github.com/Realgorithm" target="_blank">Tabish</a>
    </div>
    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
    function alreadyUser() {
        $('#signup-form').hide();
        $('#already-user').show();
        $('#already_acc').hide();
    }

    $('#already-user').submit(function(e) {
        e.preventDefault();
        var shop_url = $('#shop_url').val();
        location.href = 'login.php?shop_url=' + shop_url;
    });

    $(document).ready(function() {
        'use strict';

        $('.needs-validation').each(function() {
            var form = $(this);
            form.on('submit', function(event) {

                event.preventDefault(); // Prevent the default form submission
                start_load();

                // Check if the form is valid
                if (form[0].checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    end_load();
                    form.addClass('was-validated');
                    return false;
                }

                // Check the image file size
                var fileInput = $('#img')[0]; // Access the DOM element
                var file = fileInput.files[0];

                if (file && file.size > 1 * 1024 * 1024) { // 1MB in bytes
                    $('#signup-form').prepend('<div class="alert alert-warning alert-dismissible fade show" role="alert">The selected file is too large. Please select a file less than 1MB.  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    end_load();
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }

                // Check the password length
                var password = $('#password').val();
                if (password.length < 6 || password.length > 8) {
                    $('#signup-form').prepend('<div class="alert alert-warning alert-dismissible fade show" role="alert">Password must be between 6 and 8 characters long.  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    end_load();
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }

                var shop_name = $('#shop_name').val();
                var url = shop_name.toLowerCase().replace(/ /g, '-') + '-' + Math.floor(1000 + Math.random() * 9000);
                $('#url').val(url);

                // Disable the signup button and change its text
                const $signupButton = $('#signup-form button.btn_login');
                $signupButton.attr('disabled', true).text('Signing up...');

                // Clear any previous error messages
                $('#signup-form .alert-danger').remove();

                $.ajax({
                    url: 'ajax.php?action=signup',
                    data: new FormData(form[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    error: function(err) {
                        // Handle errors
                        console.error('Signup error:', err);
                        $('#signup-form').prepend('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                        end_load();
                        // Re-enable the signup button and reset its text
                        $signupButton.removeAttr('disabled').text('Sign Up');
                    },
                    success: function(resp) {
                        console.log(resp);
                        if (resp == 1) {
                            location.href = 'login.php?shop_url=' + url;
                        } else if (resp == 2) {
                            $('#signup-form').prepend('<div class="alert alert-warning">Your request is under process. Please try signing up after 30 minutes.</div>');
                        } else {
                            $('#signup-form').prepend('<div class="alert alert-danger">Shop already exists.</div>');
                        }
                        end_load();
                    },
                    complete: function() {
                        // Re-enable the signup button and reset its text
                        $signupButton.removeAttr('disabled').text('Sign Up');
                    }
                });
                form.addClass('was-validated');
                event.preventDefault(); // Prevent default form submission
            });
        });
    });
</script>


</html>