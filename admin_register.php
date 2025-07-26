<?php
session_start();
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    try {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username already exists";
        } else {
            // Insert new admin
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);
            $_SESSION['admin_id'] = $conn->lastInsertId();
            $_SESSION['role'] = 'admin';
            header("Location: dashboard.php");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f0f0f0; }
        .container { padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { width: 100%; padding: 10px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #c82333; }
        .error { color: red; text-align: center; }
        .link { text-align: center; margin-top: 10px; }
        .link a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Register</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <div class="link">
            <a href="admin_login.php">Already have an account? Login</a>
        </div>
        <div class="link">
            <a href="index.php">Back to Home</a>
        </div>
    </div>
</body>
</html>
