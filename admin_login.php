<?php
session_start();
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    try {
        $stmt = $conn->prepare("SELECT id, password FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid admin credentials";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Expense Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4CAF50; /* A pleasant green */
            --primary-dark: #388E3C;
            --secondary-color: #2196F3; /* A calming blue */
            --background-light: #f7f9fc;
            --card-background: #ffffff;
            --text-dark: #333333;
            --text-light: #555555;
            --border-color: #e0e0e0;
            --shadow-light: rgba(0, 0, 0, 0.08);
            --admin-btn-color: #dc3545; /* Red for admin actions */
            --admin-btn-dark: #c82333;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: var(--background-light);
            color: var(--text-light);
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            padding: 30px;
            background: var(--card-background);
            border-radius: 12px;
            box-shadow: 0 10px 30px var(--shadow-light);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: var(--text-dark);
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 2em;
            letter-spacing: -0.5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95em;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: var(--admin-btn-color); /* Admin login button color */
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .btn:hover {
            background: var(--admin-btn-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        .error {
            color: var(--admin-btn-color);
            text-align: center;
            margin-bottom: 20px;
            background-color: #f8d7da; /* Light red background */
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.95em;
        }

        .link {
            text-align: center;
            margin-top: 15px; /* Adjusted spacing */
            font-size: 0.95em;
            color: var(--text-light);
        }

        .link a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .link a:hover {
            color: #1e87e5;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Admin Username</label>
                <input type="text" id="username" name="username" required autocomplete="username" placeholder="Enter admin username">
            </div>
            <div class="form-group">
                <label for="password">Admin Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Enter admin password">
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="link">
            <a href="index.php">Back to User Login</a>
        </div>
    </div>
</body>
</html>