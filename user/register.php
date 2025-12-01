<?php
session_start();
require_once '../includes/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Input Validation
    if (empty($username) || empty($full_name) || empty($email) || empty($phone_number) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = "Username can only contain letters, numbers, and underscores.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            // Check for existing user
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR phone_number = ?");
            $stmt->execute([$username, $email, $phone_number]);
            if ($stmt->rowCount() > 0) {
                // Determine which field is duplicate (simplified check)
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);
                // Ideally we would check each field individually for a precise error message
                // For now, let's do individual checks for better UX
                
                $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->rowCount() > 0) {
                    throw new Exception("Username already exists.");
                }

                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->rowCount() > 0) {
                    throw new Exception("Email already exists.");
                }

                $stmt = $conn->prepare("SELECT id FROM users WHERE phone_number = ?");
                $stmt->execute([$phone_number]);
                if ($stmt->rowCount() > 0) {
                    throw new Exception("Phone number already exists.");
                }
            }

            $stmt = $conn->prepare("INSERT INTO users (username, full_name, email, phone_number, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $full_name, $email, $phone_number, $passwordHash]);
            header("Location: login.php");
            exit();
        } catch(Exception $e) {
            $error = $e->getMessage();
        } catch(PDOException $e) {
             $error = "An error occurred during registration. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Expense Tracker</title>
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
            background: var(--primary-color);
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
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        .error {
            color: #dc3545;
            text-align: center;
            margin-bottom: 20px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.95em;
        }

        .link {
            text-align: center;
            margin-top: 25px;
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
        <h2>Create Your Account</h2>
        <?php if (isset($error)) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required placeholder="Enter your full name" value="<?php echo isset($full_name) ? htmlspecialchars($full_name) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="text" id="email" name="email" required placeholder="Enter your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" required placeholder="Enter your phone number" value="<?php echo isset($phone_number) ? htmlspecialchars($phone_number) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username" placeholder="Choose a username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Create a strong password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password" placeholder="Confirm your password">
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <div class="link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>