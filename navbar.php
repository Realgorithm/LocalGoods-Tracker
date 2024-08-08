<nav class="navbar navbar-expand-lg sticky-top nav-style mb-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php?page=dashboard">
            <img src="assets/img/<?php echo $_SESSION['login_type'] == 3 ? 'company.png' : $_SESSION['shop_img']; ?>" alt="Shop Name" width="35" height="35" class="logo">
            <?php echo $_SESSION['login_type'] == 3 ? 'Localgoods-Tracker' : "  " . $_SESSION['shop_name']; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav m-auto">
                <?php if ($_SESSION['login_type'] == 3) : ?>
                    <li class="nav-item"><a href="index.php?page=shops" class="nav-link nav-shops"><i class="fa fa-store"></i> Shops</a></li>
                    <li class="nav-item"><a href="index.php?page=categories" class="nav-link nav-categories"><i class="fa fa-th-list"></i> Categories</a></li>
                    <li class="nav-item"><a href="index.php?page=add_product" class="nav-link nav-add_product"><i class="fa fa-box"></i> Products</a></li>
                    <li class="nav-item"><a href="ajax.php?action=admin_logout" class="nav-link nav-admin-logout"><i class="fa fa-power-off"></i> Logout</a></li>
                <?php endif; ?>
                <li class="nav-item"><a href="index.php?page=dashboard" class="nav-link nav-dashboard"><i class="fa fa-home"></i> Home</a></li>
                <li class="nav-item"><a href="index.php?page=inventory" class="nav-link nav-inventory"><i class="fa fa-boxes"></i> Inventory</a></li>
                <li class="nav-item"><a href="index.php?page=sales" class="nav-link nav-sales"><i class="fa fa-chart-line"></i> Sales</a></li>
                <?php if ($_SESSION['login_type'] == 2) : ?>
                    <li class="nav-item"><a href="index.php?page=product" class="nav-link nav-product"><i class="fa fa-tags"></i> Products</a></li>
                <?php endif; ?>
                <li class="nav-item"><a href="index.php?page=receiving" class="nav-link nav-receiving"><i class="fa fa-shopping-cart"></i> Purchases</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-list"></i> Records
                    </a>
                    <ul class="dropdown-menu nav-style" aria-labelledby="navbarDropdown">
                        <li><a href="index.php?page=product" class="dropdown-item nav-product"><i class="fa fa-tags"></i> Products</a></li>
                        <li><a href="index.php?page=supplier" class="dropdown-item nav-supplier"><i class="fa fa-truck"></i> Suppliers</a></li>
                        <li><a href="index.php?page=customer" class="dropdown-item nav-customer"><i class="fa fa-user-friends"></i> Customers</a></li>
                        <li><a href="index.php?page=credit" class="dropdown-item nav-credit"><i class="fa fa-credit-card"></i> Credit Ledger</a></li>
                        <li><a href="index.php?page=expenses" class="dropdown-item nav-expenses"><i class="fa fa-receipt"></i> Expenses</a></li>
                    </ul>
                </li>
                <?php if ($_SESSION['login_type'] == 1) : ?>
                    <li class="nav-item"><a href="index.php?page=users" class="nav-link nav-users"><i class="fa fa-users"></i> Users</a></li>
                <?php endif; ?>
                <li class="nav-item"><a href="index.php?page=account" class="nav-link nav-account"><i class="fa fa-portrait"></i> Account</a></li>
                <li class="nav-item"><a href="ajax.php?action=logout" class="nav-link nav-logout"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="darkModeSwitch" checked>
                <label class="form-check-label" for="darkModeSwitch">Dark Mode</label>
            </div>
        </div>
    </div>
</nav>
<span class="nav-manage_receiving"></span><span class="nav-pos"></span><span class="nav-creditlist"></span>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const htmlElement = document.documentElement;
        const switchElement = document.getElementById('darkModeSwitch');

        // Set the default theme to dark if no setting is found in local storage
        const currentTheme = localStorage.getItem('bsTheme') || 'dark';
        htmlElement.setAttribute('data-bs-theme', currentTheme);
        switchElement.checked = currentTheme === 'dark';

        switchElement.addEventListener('change', function() {
            const newTheme = this.checked ? 'dark' : 'light';
            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('bsTheme', newTheme);
        });

        // Highlight the current page link
        const currentPage = '<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>';
        if (currentPage) {
            document.querySelector(`.nav-${currentPage}`).classList.add('active');
        }
    });
</script>

<style>
    <?php if ($_SESSION['login_type'] != 1) : ?>.nav-link {
        display: none !important;
    }

    .nav-sales,
    .nav-dashboard,
    .nav-logout,
    .nav-inventory,
    .nav-product {
        display: block !important;
    }

    <?php endif; ?><?php if ($_SESSION['login_type'] == 3) : ?>.nav-link,
    .dropdown {
        display: none !important;
    }

    .nav-shops,
    .nav-categories,
    .nav-add_product,
    .nav-admin-logout {
        display: block !important;
    }

    <?php endif; ?>
</style>