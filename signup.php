<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Groceries Sales and Inventory System</title>

    <?php include('./header.php'); ?>
    <?php include('./db_connect.php'); ?>
</head>

<body>
    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row main-content bg-success text-center">
            <div class="col-md-2 text-center company__info">
                <span class="company__logo">
                    <h2><img src="assets/img/company.png" alt="" width="100%" length="100%"></h2>
                </span>
                <h4 class="company_title">LocalGoods-Tracker</h4>
                <h5></h5>
            </div>
            <div class="col-md-10 col-xs-12 col-sm-12 login_form ">
                <div class="container-fluid">
                    <div class="row">
                        <h2>Sign Up</h2>
                    </div>
                    <div class="row">
                        <form control="" class="form-group" id="signup-form" enctype="multipart/form-data" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="name" id="name" class="form__input" placeholder="Name" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="shop_name" id="shop_name" class="form__input" placeholder="Shop Name" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="username" id="username" class="form__input" placeholder="Username" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" name="password" id="password" class="form__input" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="email" name="email" id="email" class="form__input" placeholder="Email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="img" class="form__input" style="background-color: #fff; text-align:left">Shop Image</label>
                                    <input type="file" name="img" id="img" class="form__input" hidden>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="contact" id="contact" class="form__input" placeholder="Contact" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="shop_tagline" id="shop_tagline" class="form__input" placeholder="Shop Tagline">
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" id="url" name="url">
                            </div>
                            <div class="row">
                                <button class="btn_login">SignUp</button>
                            </div>
                        </form>
                        <form control="" class="form-group" id="already-user" method="POST" style="display: none;">
                            <div class="row">
                                <input type="text" name="shop_url" id="shop_url" class="form__input" placeholder="Shop URL">
                            </div>
                            <div class="row">
                                <button class="btn_login">Login Page</button>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <p id="already_acc">Already have a account? <button class="btn_login" onclick="alreadyUser()">Click Here</button></p>
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
    function alreadyUser() {
        document.getElementById('signup-form').style.display = "none";
        document.getElementById('already-user').style.display = "block";
        document.getElementById('already_acc').style.display = "none";

    }
    $('#already-user').submit(function(e) {
        e.preventDefault()
        var shop_url = $('#shop_url').val();
        location.href = 'login.php?shop_url=' + shop_url;

    })
    $('#signup-form').submit(function(e) {
        e.preventDefault()

        var shop_name = $('#shop_name').val();
        var url = shop_name.toLowerCase().replace(/ /g, '-') + '-' + Math.floor(1000 + Math.random() * 9000);
        $('#url').val(url);
        //console.log($('#url').val(url))

        $('#signup-form button[class="btn_login"]').attr('disabled', true).html('Signing up...');
        if ($(this).find('.alert-danger').length > 0)
            $(this).find('.alert-danger').remove();
        $.ajax({
            url: 'ajax.php?action=signup',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            error: err => {
                //console.log(err)
                $('#signup-form button[class="btn_login"]').removeAttr('disabled').html('signup');

            },
            success: function(resp) {
                console.log(resp)
                if (resp == 1) {
                    location.href = 'login.php?shop_url=' + url;
                } else if (resp == 2) {
                    $('#signup-form').prepend('<div class="alert alert-warning">your request are under process please try signup after 30 min.</div>')
                    $('#signup-form button[class="btn_login"]').removeAttr('disabled').html('signup');
                } else {
                    $('#signup-form').prepend('<div class="alert alert-danger">Username already there.</div>')
                    $('#signup-form button[class="btn_login"]').removeAttr('disabled').html('signup');
                }
            }
        })
    })
</script>

</html>