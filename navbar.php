<style>
[data-bs-theme='dark'] .nav-style{background-color:#004d40} 
[data-bs-theme='light'] .nav-style{background-color:#6499db}
</style>
<nav class="navbar navbar-expand-lg sticky-top nav-style">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <?php if ($_SESSION['login_type'] == 3) : ?>
                <img src="assets/img/company.png" alt="Shop Name" width="35" height="35" class="logo">
                Localgoods-Tracker
            <?php else : ?>
                <img src="assets/img/<?php echo $_SESSION['shop_img'] ?>" alt="Shop Name" width="35" height="35" class="logo">
                <?php echo "  " . $_SESSION['shop_name'] ?>
            <?php endif; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav m-auto">
                <?php if ($_SESSION['login_type'] == 3) : ?>
                    <li class="nav-item">
                        <a href="index.php?page=shops" class="nav-link nav-shops"><i class="fa fa-boxes"></i> Shop List</a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=categories" class="nav-link nav-categories"><i class="fa fa-list"></i> Category List</a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=add_product" class="nav-link nav-add-product"><i class="fa fa-boxes"></i> Add Product</a>
                    </li>
                    <li class="nav-item">
                        <a href="ajax.php?action=admin_logout" class="nav-link nav-admin-logout"><i class="fa fa-power-off"></i> Logout</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="index.php?page=dashboard" class="nav-link nav-dashboard"><i class="fa fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=inventory" class="nav-link nav-inventory"><i class="fa fa-list"></i> Inventory</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=sales" class="nav-link nav-sales"><i class="fa fa-coins"></i> Sales</a>
                </li>
                <?php if ($_SESSION['login_type'] == 2) : ?>
                    <li class="nav-item">
                        <a href="index.php?page=product" class="nav-link nav-product"><i class="fa fa-boxes"></i> Product List</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="index.php?page=receiving" class="nav-link nav-receiving"><i class="fa fa-file-alt"></i> Receiving</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-list"></i> Lists
                    </a>
                    <ul class="dropdown-menu nav-style" aria-labelledby="navbarDropdown">
                        <li><a href="index.php?page=product" class="dropdown-item"><i class="fa fa-boxes"></i> Product List</a></li>
                        <li><a href="index.php?page=supplier" class="dropdown-item"><i class="fa fa-truck-loading"></i> Supplier List</a></li>
                        <li><a href="index.php?page=customer" class="dropdown-item"><i class="fa fa-user-friends"></i> Customer List</a></li>
                        <li><a href="index.php?page=credit" class="dropdown-item"><i class="fa fa-credit-card"></i> Credit List</a></li>
                        <li><a href="index.php?page=expenses" class="dropdown-item"><i class="fa fa-money-bill"></i> Expenses</a></li>
                    </ul>
                </li>
                <?php if ($_SESSION['login_type'] == 1) : ?>
                    <li class="nav-item">
                        <a href="index.php?page=users" class="nav-link nav-users"><i class="fa fa-users"></i> Users</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="index.php?page=account" class="nav-link nav-account"><i class="fa fa-portrait"></i> Account</a>
                </li>
                <li class="nav-item">
                    <a href="ajax.php?action=logout" class="nav-link nav-logout"><i class="fa fa-power-off"></i> Logout</a>
                </li>
            </ul>
            <!-- Bootstrap 5 switch -->
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="darkModeSwitch" checked>
                <label class="form-check-label" for="darkModeSwitch" style="color: white;">Dark Mode</label>
            </div>
        </div>
    </div>
</nav>

<br>
<script>
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

<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
<?php if ($_SESSION['login_type'] != 1) : ?>
	<style>
		.nav-link {
			display: none !important;
		}

		.nav-sales,
		.nav-dashboard,
		.nav-logout,
		.nav-inventory,
		.nav-product {
			display: block !important;
		}
	</style>
<?php endif ?>
<?php if ($_SESSION['login_type'] == 3) : ?>
	<style>
		.nav-link {
			display: none !important;
		}

		.dropdown {
			display: none !important;
		}

		.nav-shops,
		.nav-categories,
		.nav-add-product,
		.nav-admin-logout {
			display: block !important;
		}
	</style>
<?php endif ?>