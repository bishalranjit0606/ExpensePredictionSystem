<?php
session_start();
if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
    header("Location: " . (isset($_SESSION['admin_id']) ? "admin_dashboard.php" : "dashboard.php"));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Prediction System</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f0f0f0; }
        .container { text-align: center; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .btn { padding: 10px 20px; margin: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
        .admin-btn { background: #dc3545; }
        .admin-btn:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Expense Prediction System</h1>
        <a href="login.php" class="btn">User Login</a>
        <a href="admin_login.php" class="btn admin-btn">Admin Login</a>
        <a href="register.php" class="btn">User Register</a>
    </div>
</body>
</html>
