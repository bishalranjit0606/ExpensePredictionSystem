<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f0f0f0; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .btn { padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 5px; text-decoration: none; }
        .btn:hover { background: #0056b3; }
        .logout { float: right; }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" class="logout">
            <button type="submit" name="logout" class="btn">Logout</button>
        </form>
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php" class="btn">View User Expenses</a>
    </div>
</body>
</html>
