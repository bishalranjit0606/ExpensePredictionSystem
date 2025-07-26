<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
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
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f0f0f0; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .btn { padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 5px; text-decoration: none; }
        .btn:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f8f9fa; }
        .logout { float: right; }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" class="logout">
            <button type="submit" name="logout" class="btn">Logout</button>
        </form>
        <h2>Admin Dashboard</h2>
        <h3>User Expenses</h3>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Total Expenses (₨)</th>
            </tr>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td>₨<?php echo number_format($user['total_expenses'], 2); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
