<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Groceries Sales and Inventory System</title>

    <?php
    session_start();

    if (($_SESSION['login_type'] != 3)) {
        $firstLogin = $_SESSION ['login_id'];
        if (!isset($_SESSION['login_id']) and !isset($_SESSION['shop_db'])) {
            header('location:home.php');
        }
    }
    include('./header.php');
    ?>

    <style>
        .modalImage {
            max-width: 80%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100vh - 1rem);
        }

        .custom-modal {
            top: 10%;
            /* Adjust this value to set the desired distance from the top */
            transform: translateY(0);
            /* Remove the default centering */
        }
    </style>
</head>

<body>
    <?php include 'loader.php' ?>
    <?php include 'navbar.php' ?>

    <div class="toast sticky-top" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true" style="width: 100%;">
        <h5 class="toast-body text-white text-center"></h5>
    </div>

    <main>
        <?php if (isset($_SESSION['shop_db'])) {
            $dbName = $_SESSION['shop_db'];
        } ?>

        <?php
        if (($_SESSION['login_type'] != 3)) {
            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
        } else {
            $page = isset($_GET['page']) ? $_GET['page'] : 'shops';
        }
        ?>

        <?php include $page . '.php' ?>
        <div class="text-center mt-3">
            <button id="start-tour-again" style="display:none;" class="btn btn-outline-info text-center">Take a Tour</button>
        </div>
    </main>

    <footer class="bg-body-tertiary text-center mt-3">
        <div class="container p-4 pb-0">
            <section class="mb-4">
                <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #3b5998;" href="#!" role="button"><i class="fab fa-facebook-f"></i></a>
                <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #55acee;" href="#!" role="button"><i class="fab fa-twitter"></i></a>
                <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #dd4b39;" href="#!" role="button"><i class="fab fa-google"></i></a>
                <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #ac2bac;" href="#!" role="button"><i class="fab fa-instagram"></i></a>
                <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #0082ca;" href="#!" role="button"><i class="fab fa-linkedin-in"></i></a>
                <a data-mdb-ripple-init class="btn text-white btn-floating m-1" style="background-color: #333333;" href="https://github.com/Realgorithm" target="_blank" role="button"><i class="fab fa-github"></i></a>
            </section>
        </div>

        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2020 Copyright:
            Coded with &hearts; by <a href="https://github.com/Realgorithm" target="_blank">Tabish</a>
        </div>
    </footer>

    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

    <div class="modal fade" id="confirm_modal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md custom-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <div id="delete_content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary col-sm-5 me-5" id='confirm' onclick="">Continue</button>
                    <button type="button" class="btn btn-danger col-sm-5" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uni_modal" tabindex="-1" aria-labelledby="uniModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md custom-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uniModalLabel"></h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary col-sm-5 me-5" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-danger col-sm-5" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="image_modal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="modalImage img-fluid" src="" alt="Preview">
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            'use strict';

            const firstLogin = <?php echo json_encode($firstLogin); ?>;
            if (!localStorage.getItem("tourShown") && firstLogin) {
                startTour();
                <?php $firstLogin = '' ?>
            }

            $("#start-tour-again").click(function() {
                localStorage.removeItem("tourShown");
                localStorage.removeItem("tourStep");
                startTour();
            });

            // Show the "Start Tour Again" button
            $("#start-tour-again").show();
        });
        window.uni_modal = function(title = '', url = '') {
            start_load();
            $.ajax({
                url: url,
                error: function(err) {
                    alert("An error occurred");
                },
                success: function(resp) {
                    if (resp) {
                        $('#uni_modal .modal-title').html(title);
                        $('#uni_modal .modal-body').html(resp);
                        $('#uni_modal').modal('show');
                        end_load();
                    }
                }
            });
        }

        window._conf = function(msg = '', func = '', params = []) {
            var paramString = params.map(param => `'${param}'`).join(',');
            $('#confirm_modal #confirm').attr('onclick', func + "(" + paramString + ")");
            $('#confirm_modal .modal-body').html(msg);
            $('#confirm_modal').modal('show');
        }

        window.image_modal = function(src = '') {
            $('#image_modal .modalImage').attr('src', src);
            $('#image_modal').modal('show');
        }

        window.alert_toast = function(msg = 'TEST', bg = 'success') {
            $('#alert_toast').removeClass('bg-success bg-danger bg-info bg-warning');
            $('#alert_toast').addClass('bg-' + bg);
            $('#alert_toast .toast-body').html(msg);
            $('#alert_toast').toast({
                delay: 3000
            }).toast('show');
        }
    </script>
</body>

</html>