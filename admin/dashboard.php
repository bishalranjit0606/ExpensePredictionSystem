<?php
session_start();
require_once '../includes/db_config.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle user deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id_to_delete = $_POST['user_id'];

    try {
        // Start a transaction for atomicity
        $conn->beginTransaction();

        // 1. Delete associated expenses first (to avoid foreign key constraint issues)
        $stmt_delete_expenses = $conn->prepare("DELETE FROM expenses WHERE user_id = ?");
        $stmt_delete_expenses->execute([$user_id_to_delete]);

        // 2. Delete the user
        $stmt_delete_user = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt_delete_user->execute([$user_id_to_delete]);

        // Commit the transaction
        $conn->commit();

        // Redirect back to admin dashboard after successful deletion
        header("Location: dashboard.php");
        exit();

    } catch (PDOException $e) {
        // Rollback the transaction if something went wrong
        $conn->rollBack();
        $error = "Error deleting user: " . $e->getMessage();
    }
}


// Fetch all users and their total expenses
$stmt = $conn->prepare("
    SELECT u.id, u.username, COALESCE(SUM(e.amount), 0) as total_expenses
    FROM users u
    LEFT JOIN expenses e ON u.id = e.user_id
    GROUP BY u.id, u.username
    ORDER BY u.username
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle admin logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../index.php"); // Redirect to regular user login page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Expense Tracker</title>
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
            margin: 0;
            padding: 20px;
            background-color: var(--background-light);
            color: var(--text-light);
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background: var(--card-background);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px var(--shadow-light);
        }

        h2 {
            color: var(--text-dark);
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 2.2em;
            border-bottom: 2px solid var(--admin-btn-color);
            padding-bottom: 10px;
            display: inline-block;
        }
        
        h3 {
            color: var(--text-dark);
            font-size: 1.6em;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 12px 25px;
            background: var(--admin-btn-color);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            margin-right: 10px;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: var(--admin-btn-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .btn-delete-user { /* Specific style for delete user button */
            background: var(--admin-btn-color);
            padding: 8px 15px; /* Smaller padding for table buttons */
            font-size: 0.9em;
        }

        .btn-delete-user:hover {
            background: var(--admin-btn-dark);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 25px;
            background: var(--card-background);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px var(--shadow-light);
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }

        th {
            background: var(--admin-btn-color);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        tr:hover {
            background-color: #fff0f0;
        }

        .logout-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .error {
            color: var(--admin-btn-color);
            text-align: center;
            margin-bottom: 20px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.95em;
        }

        .no-records {
            text-align: center;
            padding: 20px;
            color: var(--text-light);
            font-style: italic;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            .container {
                margin: 15px auto;
                padding: 15px;
            }
            h2 {
                font-size: 1.8em;
            }
            h3 {
                font-size: 1.4em;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr { border: 1px solid var(--border-color); margin-bottom: 10px; border-radius: 8px; overflow: hidden;}
            td { 
                border: none;
                border-bottom: 1px solid #eee; 
                position: relative;
                padding-left: 50%; 
                text-align: right;
            }
            td:before { 
                position: absolute;
                top: 0;
                left: 6px;
                width: 45%; 
                padding-right: 10px; 
                white-space: nowrap;
                content: attr(data-label);
                font-weight: 600;
                text-align: left;
                color: var(--text-dark);
            }
            td:last-child {
                border-bottom: none;
            }
            td:nth-of-type(1):before { content: "User ID"; }
            td:nth-of-type(2):before { content: "Username"; }
            td:nth-of-type(3):before { content: "Total Expenses (₨)"; }
            td:nth-of-type(4):before { content: "Actions"; } /* Add this for responsive actions column */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout-section">
            <h2>Admin Dashboard</h2>
            <form method="POST">
                <button type="submit" name="logout" class="btn">Logout</button>
            </form>
        </div>
        
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <h3>Overview of User Expenses</h3>
        <?php if ($users) { ?>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Total Expenses (₨)</th>
                        <th>Actions</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td data-label="User ID"><?php echo $user['id']; ?></td>
                            <td data-label="Username"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td data-label="Total Expenses (₨)">₨<?php echo number_format($user['total_expenses'], 2); ?></td>
                            <td data-label="Actions">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-delete-user" onclick="return confirm('Are you sure you want to delete user \'<?php echo htmlspecialchars($user['username']); ?>\' and ALL their expenses? This cannot be undone.')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="no-records">No user data found.</p>
        <?php } ?>
    </div>
</body>
</html>