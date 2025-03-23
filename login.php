<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: inventory.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Inventory - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header text-center">
                        <i class="fas fa-car-side login-icon"></i>
                        <h4>Vehicle Inventory Login</h4>
                    </div>
                    <div class="card-body">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="username" name="username" required>
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    Login
                                    <span class="loading-spinner" id="spinner">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </button>
                            </div>
                        </form>
                        <div id="loginAlert" class="alert mt-3" role="alert" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/login.js"></script>

</body>
</html> 