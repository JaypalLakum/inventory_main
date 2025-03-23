<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the current page name for active nav item
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Inventory - <?php echo ucfirst(str_replace('.php', '', $current_page)); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php if($current_page === 'model.php'): ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">
    <?php endif; ?>
    <link href="assets/css/style.css" rel="stylesheet">
   
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="inventory.php">
                <i class="fas fa-car-side"></i>
                Vehicle Inventory
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav nav-main">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'inventory.php' ? 'active' : ''; ?>" href="inventory.php">
                            <i class="fas fa-warehouse"></i>
                            Inventory
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'manufacturer.php' ? 'active' : ''; ?>" href="manufacturer.php">
                            <i class="fas fa-industry"></i>
                            Manufacturers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'model.php' ? 'active' : ''; ?>" href="model.php">
                            <i class="fas fa-car"></i>
                            Models
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav nav-logout">
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-container">

 