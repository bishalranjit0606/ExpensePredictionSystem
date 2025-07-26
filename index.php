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
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
        }
        .container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
            animation: fadeIn 1s ease-in-out;
        }
        h1 {
            color: #2c3e50;
            font-size: 2.2em;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 10px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            border-radius: 25px;
            transition: transform 0.3s, box-shadow 0.3s;
            background: linear-gradient(45deg, #007bff, #00d4ff);
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }
        .admin-btn {
            background: linear-gradient(45deg, #dc3545, #ff7588);
        }
        .admin-btn:hover {
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 500px) {
            .container { padding: 20px; }
            h1 { font-size: 1.8em; }
            .btn { padding: 10px 20px; font-size: 0.9em; }
        }
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
