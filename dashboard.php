<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Linear Regression function
function predictNextMonth($conn, $user_id) {
    $stmt = $conn->prepare("SELECT YEAR(expense_date) as year, MONTH(expense_date) as month, SUM(amount) as total 
                           FROM expenses 
                           WHERE user_id = ? 
                           GROUP BY YEAR(expense_date), MONTH(expense_date) 
                           ORDER BY year, month");
    $stmt->execute([$user_id]);
    $monthly_expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($monthly_expenses) < 2) return 0;
    
    $x = array_map(function($i) { return $i; }, range(1, count($monthly_expenses)));
    $y = array_column($monthly_expenses, 'total');
    
    $n = count($x);
    $sum_x = array_sum($x);
    $sum_y = array_sum($y);
    $sum_xy = 0;
    $sum_xx = 0;
    
    for ($i = 0; $i < $n; $i++) {
        $sum_xy += $x[$i] * $y[$i];
        $sum_xx += $x[$i] * $x[$i];
    }
    
    $slope = ($n * $sum_xy - $sum_x * $sum_y) / ($n * $sum_xx - $sum_x * $sum_x);
    $intercept = ($sum_y - $slope * $sum_x) / $n;
    
    return round($slope * ($n + 1) + $intercept, 2);
}

// CRUD Operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $amount = $_POST['amount'];
        $category = $_POST['category'];
        $expense_date = $_POST['expense_date'];
        $user_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'];
        $stmt = $conn->prepare("INSERT INTO expenses (user_id, amount, category, expense_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $amount, $category, $expense_date]);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $amount = $_POST['amount'];
        $category = $_POST['category'];
        $expense_date = $_POST['expense_date'];
        $user_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'];
        $stmt = $conn->prepare("UPDATE expenses SET amount = ?, category = ?, expense_date = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$amount, $category, $expense_date, $id, $user_id]);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $user_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'];
        $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
    }
}

// Fetch expenses
$user_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT * FROM expenses WHERE user_id = ? ORDER BY expense_date DESC");
$stmt->execute([$user_id]);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get current month expenses
$current_month = date('Y-m');
$stmt = $conn->prepare("SELECT SUM(amount) as total FROM expenses WHERE user_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?");
$stmt->execute([$user_id, $current_month]);
$current_month_total = $stmt->fetchColumn() ?: 0;

// Get two most recent previous months' expenses
$stmt = $conn->prepare("SELECT YEAR(expense_date) as year, MONTH(expense_date) as month, SUM(amount) as total 
                        FROM expenses 
                        WHERE user_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') < ? 
                        GROUP BY YEAR(expense_date), MONTH(expense_date) 
                        ORDER BY year DESC, month DESC 
                        LIMIT 2");
$stmt->execute([$user_id, $current_month]);
$previous_months = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get prediction
$prediction = predictNextMonth($conn, $user_id);

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
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f0f0f0; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); display: flex; flex-wrap: wrap; }
        .main-content { flex: 3; min-width: 500px; padding-right: 20px; }
        .sidebar { flex: 1; min-width: 250px; }
        h2, h3 { color: #333; }
        .prediction, .current-month { background: #e7f3fe; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .previous-months { background: #e7f3fe; padding: 10px; border-radius: 4px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f8f9fa; }
        .logout { float: right; }
        .admin-link { margin-bottom: 20px; }
    </style>
    <script>
        function editExpense(id, amount, category, date) {
            document.getElementById('expense_id').value = id;
            document.getElementById('amount').value = amount;
            document.getElementById('category').value = category;
            document.getElementById('expense_date').value = date;
            document.getElementById('add_btn').style.display = 'none';
            document.getElementById('update_btn').style.display = 'inline-block';
        }
        function resetForm() {
            document.getElementById('expense_form').reset();
            document.getElementById('expense_id').value = '';
            document.getElementById('add_btn').style.display = 'inline-block';
            document.getElementById('update_btn').style.display = 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <form method="POST" class="logout">
                <button type="submit" name="logout" class="btn">Logout</button>
            </form>
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <div class="admin-link">
                    <a href="admin.php" class="btn">Admin Panel</a>
                </div>
            <?php } ?>
            <h2>Dashboard</h2>
            <div class="prediction">
                <h3>Next Month's Prediction: ₨<?php echo number_format($prediction, 2); ?></h3>
            </div>
            <div class="current-month">
                <h3>Current Month's Expenses (<?php echo date('F Y'); ?>): ₨<?php echo number_format($current_month_total, 2); ?></h3>
            </div>
            <h3>Add/Edit Expense</h3>
            <form id="expense_form" method="POST">
                <input type="hidden" id="expense_id" name="id">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" id="amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="Food">Food</option>
                        <option value="Transport">Transport</option>
                        <option value="Utilities">Utilities</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="expense_date">Date</label>
                    <input type="date" id="expense_date" name="expense_date" required>
                </div>
                <button type="submit" name="add" id="add_btn" class="btn">Add Expense</button>
                <button type="submit" name="update" id="update_btn" class="btn" style="display:none;">Update Expense</button>
                <button type="button" onclick="resetForm()" class="btn">Reset</button>
            </form>
            <h3>Your Expenses</h3>
            <table>
                <tr>
                    <th>Amount</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($expenses as $expense) { ?>
                    <tr>
                        <td>₨<?php echo number_format($expense['amount'], 2); ?></td>
                        <td><?php echo $expense['category']; ?></td>
                        <td><?php echo $expense['expense_date']; ?></td>
                        <td>
                            <button onclick="editExpense(<?php echo $expense['id']; ?>, <?php echo $expense['amount']; ?>, '<?php echo $expense['category']; ?>', '<?php echo $expense['expense_date']; ?>')" class="btn">Edit</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $expense['id']; ?>">
                                <button type="submit" name="delete" class="btn" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="sidebar">
            <div class="previous-months">
                <h3>Previous Months' Expenses</h3>
                <?php if ($previous_months) { ?>
                    <table>
                        <tr>
                            <th>Month</th>
                            <th>Total (₨)</th>
                        </tr>
                        <?php foreach ($previous_months as $month) { ?>
                            <tr>
                                <td><?php echo date('F Y', mktime(0, 0, 0, $month['month'], 1, $month['year'])); ?></td>
                                <td>₨<?php echo number_format($month['total'], 2); ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } else { ?>
                    <p>No previous months' expenses found.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
