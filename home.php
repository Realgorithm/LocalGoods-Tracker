<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalGoods-Tracker</title>
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        [data-bs-theme='dark'] .hero-section {
            color: #597445;
        }

        [data-bs-theme='light'] .hero-section {
            color: #6490d0;
        }

        .hero-section {
            background: url('assets/img/hero-bg.png') no-repeat center center/cover;
            padding: 190px 0;
            height: 600px;
        }

        .hero-section form {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .img-responsive {
            width: 100%;
            height: auto;
        }

        [data-bs-theme='dark'] h2 {
            background-color: #008080;
            border-radius: 20px;
        }

        [data-bs-theme='light'] h2 {
            background-color: #80d9d9;
            border-radius: 15px;
        }

        .hero-section h1 {
            font-size: 3rem;
        }

        .features-section,
        .about-section,
        .contact-section {
            padding: 62px 0;
        }

        .features-section h2,
        .about-section h2,
        .contact-section h2 {
            margin-bottom: 30px;
        }

        footer {
            background: #f8f9fa;
            padding: 20px 0;
            margin-top: 20px;
        }
    </style>
    <?php include('./header.php'); ?>

</head>

<body>
    <?php include 'loader.php' ?>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top nav-style">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="assets/img/company.png" alt="Shop Name" width="35" height="35" class="logo"> LocalGoods-Tracker </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav m-auto">

                    <li class="nav-item ">
                        <a class="nav-link" href="#home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#features"><span class='icon-field'><i class="fa fa-star"></i></span> Features</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#about"><span class='icon-field'><i class="fa fa-info-circle"></i></span> About</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#contact"><span class='icon-field'><i class="fa fa-envelope"></i></span> Contact</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="signup.php"><span class='icon-field'><i class="fa fa-user-plus"></i></span> SignUp</a>
                    </li>
                </ul>
                <!-- Use ms-auto to push this element to the right -->
                <div class="form-check form-switch ms-auto">
                    <input class="form-check-input" type="checkbox" id="darkModeSwitch" checked>
                    <label class="form-check-label" for="darkModeSwitch" style="color: white;">Dark Mode</label>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">

        <!-- Hero Section -->
        <header class="hero-section" id="home">
            <div class="container-fluid text-center">
                <h1 class="mb-3"><span class="mark">Welcome to LocalGoods-Tracker</span></h1>
                <h3><span class="mark"> ultimate solution for tracking and managing local goods inventory</span></h3>
                <a href="#features" class="btn btn-primary">Learn More</a>
                <br><br>
                <form class="row g-2" id="already-user" method="POST">
                    <div class="col-md-4">
                        <input type="text" name="shop_url" id="shop_url" class="form-control border-warning" placeholder="Enter your Shop URL">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-danger">Go To Login</button>
                    </div>
                </form>
            </div>
        </header>

        <!-- Features Section -->
        <section class="features-section" id="features">
            <div class="container-fluid">
                <h2 class="text-center">Features</h2>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="assets/img/feature1.png" alt="Track Sales" class="img-fluid" style="max-width:100%;height:auto;">
                        <h3>Track Sales</h3>
                        <p>Keep track of all your sales with ease.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="assets/img/feature2.png" alt="Monitor Profit" class="img-fluid" style="max-width:100%;height:auto;">
                        <h3>Monitor Profit</h3>
                        <p>Monitor your profit and expenses effortlessly.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="assets/img/feature3.png" alt="Generate Reports" class="img-fluid" style="max-width:100%;height:auto;">
                        <h3>Inventory Management</h3>
                        <p>Give easy inventory management for better insights.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section" id="about">
            <div class="container-fluid">
                <h2 class="text-center">About Us</h2>
                <div class="row">
                    <div class="col-md-3 col-sm-4 col-12">
                        <!-- Content for col-md-3 goes here -->
                        <img src="assets/img/about_us.png" alt="Image" class="img-responsive">
                    </div>
                    <div class="col-md-9 col-sm-8 col-12">
                        <!-- Content for col-md-9 goes here -->
                        <h5>
                            <p>
                                <strong>LocalGoods-Tracker</strong> is here to revolutionize how you manage your local goods inventory. Our mission is to deliver an efficient, user-friendly platform that makes tracking sales, profits, and expenses simpler than ever before.
                            </p>
                            <p>
                                With <strong>LocalGoods-Tracker</strong>, you gain access to a powerful suite of tools designed to streamline your business operations:
                            </p>
                            <ul>
                                <li>Seamlessly manage your <strong>inventory</strong>.</li>
                                <li>Effortlessly handle <strong>customer</strong> and <strong>supplier</strong> information.</li>
                                <li>Keep track of <strong>user accounts</strong> and <strong>customer credit</strong>.</li>
                                <li>Manage <strong>supply receipts</strong> and <strong>extra expenses</strong> with ease.</li>
                            </ul>
                            <p>
                                Our goal is to provide you with a comprehensive solution that enhances productivity and boosts your business's efficiency. Join us today and take control of your local goods management like never before!
                            </p>
                        </h5>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section" id="contact">
            <div class="container-fluid">
                <h2 class="text-center">Contact Us</h2>
                <div class="row">
                    <div class="col-md-9 col-sm-8 col-12">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Your Name">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Your Email">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="4" placeholder="Your Message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Send Message</button>
                        </form>
                    </div>
                    <div class="col-md-3 col-sm-4 col-12">
                        <img src="assets/img/contact.png" alt="Image" class="img-responsive">
                    </div>
                </div>

            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-body-tertiary text-center">
            <!-- Grid container -->
            <div class="container p-4 pb-0">
                <!-- Section: Social media -->
                <section class="mb-4">
                    <!-- Facebook -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #3b5998;" href="#!" role="button"><i class="fab fa-facebook-f"></i></a>

                    <!-- Twitter -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #55acee;" href="#!" role="button"><i class="fab fa-twitter"></i></a>

                    <!-- Google -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #dd4b39;" href="#!" role="button"><i class="fab fa-google"></i></a>

                    <!-- Instagram -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #ac2bac;" href="#!" role="button"><i class="fab fa-instagram"></i></a>

                    <!-- Linkedin -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #0082ca;" href="#!" role="button"><i class="fab fa-linkedin-in"></i></a>
                    <!-- Github -->
                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #333333;" href="https://github.com/Realgorithm" target="_blank" role="button"><i class="fab fa-github"></i></a>

                    <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #FF5733;" href="admin_login.php" role="button"><i class="fa fa-sign-in-alt"></i></a>

                </section>
                <!-- Section: Social media -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
                © 2020 Copyright:
                Coded with &hearts; by <a href="https://github.com/Realgorithm" target="_blank">Tabish</a></p>
            </div>
            <!-- Copyright -->
        </footer>
    </div>
</body>
<script>
    $(document).ready(function() {

        if ('serviceWorker' in navigator) {
            console.log("Service Worker supported, attempting to register...");
            navigator.serviceWorker.register('sw.js')
                .then(function(registration) {
                    console.log("Service Worker registered with scope:", registration.scope);
                }).catch(function(error) {
                    console.error("Service Worker registration failed:", error);
                });
        } else {
            console.warn("Service Worker not supported in this browser.");
        }



        $('#already-user').submit(function(e) {
            e.preventDefault()
            var shop_url = $('#shop_url').val();
            location.href = 'login.php?shop_url=' + shop_url;

        })
        // Smooth scrolling
        $('a.nav-link').on('click', function(event) {
            console.log(this.hash); // Corrected logging statement
            if (this.hash !== "") {
                event.preventDefault();
                var hash = this.hash;

                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 800, function() {
                    window.location.hash = hash;
                });

                // Collapse the navbar
                $('.navbar-collapse').collapse('hide');
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            }
        });
    });
    document.addEventListener('DOMContentLoaded', (event) => {
        const htmlElement = document.documentElement;
        const switchElement = document.getElementById('darkModeSwitch');

        // Set the default theme to dark if no setting is found in local storage
        const currentTheme = localStorage.getItem('bsTheme') || 'dark';
        htmlElement.setAttribute('data-bs-theme', currentTheme);
        switchElement.checked = currentTheme === 'dark';

        switchElement.addEventListener('change', function() {
            if (this.checked) {
                htmlElement.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('bsTheme', 'dark');
            } else {
                htmlElement.setAttribute('data-bs-theme', 'light');
                localStorage.setItem('bsTheme', 'light');
            }
        });
    });
</script>

</html>