<?php
session_start();
require_once '../includes/db_config.php';

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
    <title>Expense Tracker Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4CAF50; /* A pleasant green */
            --primary-dark: #388E3C;
            --secondary-color: #2196F3; /* A calming blue */
            --accent-color: #FFC107; /* A bright yellow for highlights */
            --background-light: #f7f9fc;
            --card-background: #ffffff;
            --text-dark: #333333;
            --text-light: #555555;
            --border-color: #e0e0e0;
            --shadow-light: rgba(0, 0, 0, 0.08);
            --shadow-strong: rgba(0, 0, 0, 0.15);
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
            max-width: 1200px;
            margin: 30px auto;
            background: var(--card-background);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px var(--shadow-light);
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .main-content {
            flex: 3;
            min-width: 600px; /* Adjusted for better layout */
            padding-right: 20px;
            border-right: 1px solid var(--border-color); /* Separator */
        }

        .sidebar {
            flex: 1;
            min-width: 300px; /* Adjusted for better layout */
        }

        h2, h3 {
            color: var(--text-dark);
            margin-bottom: 20px;
            font-weight: 600;
        }

        h2 {
            font-size: 2.2em;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            display: inline-block;
        }
        
        h3 {
            font-size: 1.6em;
        }

        .info-card {
            background: linear-gradient(135deg, #e7f3fe, #d9edff);
            padding: 20px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #cce7ff;
            color: var(--text-dark);
        }

        .info-card h3 {
            margin-top: 0;
            font-size: 1.4em;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
        }

        /* Removed ::before pseudo-elements for icons */

        .form-section {
            background: var(--card-background);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px var(--shadow-light);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
        }

        input[type="number"],
        input[type="date"],
        select {
            width: calc(100% - 20px);
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="number"]:focus,
        input[type="date"]:focus,
        select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
            outline: none;
        }

        .btn {
            padding: 12px 25px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            margin-right: 10px;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .btn.secondary {
            background: #6c757d;
        }

        .btn.secondary:hover {
            background: #5a6268;
        }

        .btn-delete {
            background: #dc3545;
            margin-left: 5px; /* Spacing between edit and delete */
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-logout { /* New class for green logout button */
            background: var(--primary-color);
        }

        .btn-logout:hover {
            background: var(--primary-dark);
        }


        table {
            width: 100%;
            border-collapse: separate; /* For rounded corners on cells */
            border-spacing: 0; /* Remove default spacing */
            margin-top: 25px;
            background: var(--card-background);
            border-radius: 8px;
            overflow: hidden; /* Ensures rounded corners are visible */
            box-shadow: 0 4px 15px var(--shadow-light);
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }

        th {
            background: var(--primary-color);
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
            background-color: #f0f8ff;
        }

        td .btn {
            padding: 8px 15px;
            font-size: 0.9em;
            margin: 0 3px;
        }

        .logout {
            float: right;
            margin-bottom: 20px;
        }

        .admin-link {
            margin-bottom: 20px;
            display: inline-block; /* Aligns with other buttons if needed */
        }
        
        .admin-link .btn {
            background: var(--secondary-color);
        }

        .admin-link .btn:hover {
            background: #1e87e5;
        }

        .no-records {
            text-align: center;
            padding: 20px;
            color: var(--text-light);
            font-style: italic;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .main-content {
                min-width: auto;
                flex: 2; /* Allow it to shrink more */
                border-right: none; /* Remove vertical separator on smaller screens */
                padding-right: 0;
            }
            .sidebar {
                min-width: auto;
                flex: 1; /* Allow it to shrink more */
            }
            .container {
                flex-direction: column;
                gap: 20px;
                padding: 20px;
            }
        }

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
            .btn {
                width: 100%;
                margin-bottom: 10px;
                margin-right: 0;
            }
            td .btn {
                width: auto;
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
            td:nth-of-type(1):before { content: "Amount"; }
            td:nth-of-type(2):before { content: "Category"; }
            td:nth-of-type(3):before { content: "Date"; }
            td:nth-of-type(4):before { content: "Actions"; }
            
            .main-content, .sidebar {
                padding-right: 0;
                border-right: none;
            }
        }

        @media (max-width: 480px) {
            .info-card h3 {
                flex-direction: column;
                align-items: flex-start;
            }
            /* Removed styling for ::before icons */
        }
    </style>
    <script>
        function editExpense(id, amount, category, date) {
            document.getElementById('expense_id').value = id;
            document.getElementById('amount').value = amount;
            document.getElementById('category').value = category;
            document.getElementById('expense_date').value = date;
            document.getElementById('add_btn').style.display = 'none';
            document.getElementById('update_btn').style.display = 'inline-block';
            // Scroll to the form after clicking edit
            document.getElementById('expense_form').scrollIntoView({ behavior: 'smooth' });
        }
        function resetForm() {
            document.getElementById('expense_form').reset();
            document.getElementById('expense_id').value = '';
            document.getElementById('add_btn').style.display = 'inline-block';
            document.getElementById('update_btn').style.display = 'none';
        }
        // Set default date to today for add form on page load
        document.addEventListener('DOMContentLoaded', (event) => {
            const dateField = document.getElementById('expense_date');
            if (!dateField.value) { // Only set if it's not already populated (e.g., from edit)
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
                const day = String(today.getDate()).padStart(2, '0');
                dateField.value = `${year}-${month}-${day}`;
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Dashboard</h2>
                <form method="POST" class="logout">
                    <button type="submit" name="logout" class="btn btn-logout">Logout</button>
                </form>
            </div>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                <div class="admin-link">
                    <a href="admin.php" class="btn">Admin Panel</a>
                </div>
            <?php } ?>

            <div class="info-card prediction">
                <h3>Next Month's Predicted Expense: <span style="color: var(--primary-dark);">₨<?php echo number_format($prediction, 2); ?></span></h3>
            </div>
            <div class="info-card current-month">
                <h3>Current Month's Expenses (<?php echo date('F Y'); ?>): <span style="color: var(--primary-dark);">₨<?php echo number_format($current_month_total, 2); ?></span></h3>
            </div>

            <div class="form-section">
                <h3>Add/Edit Expense</h3>
                <form id="expense_form" method="POST">
                    <input type="hidden" id="expense_id" name="id">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" step="0.01" id="amount" name="amount" required placeholder="e.g., 500.25">
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
                    <button type="button" onclick="resetForm()" class="btn secondary">Reset Form</button>
                </form>
            </div>

            <h3>Your Recent Expenses</h3>
            <?php if ($expenses) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense) { ?>
                            <tr>
                                <td data-label="Amount">₨<?php echo number_format($expense['amount'], 2); ?></td>
                                <td data-label="Category"><?php echo $expense['category']; ?></td>
                                <td data-label="Date"><?php echo $expense['expense_date']; ?></td>
                                <td data-label="Actions">
                                    <button onclick="editExpense(<?php echo $expense['id']; ?>, <?php echo $expense['amount']; ?>, '<?php echo $expense['category']; ?>', '<?php echo $expense['expense_date']; ?>')" class="btn">Edit</button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $expense['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="no-records">No expenses recorded yet. Start by adding one above!</p>
            <?php } ?>
        </div>

        <div class="sidebar">
            <div class="info-card previous-months">
                <h3>Previous Months' Expenses</h3>
                <?php if ($previous_months) { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total (₨)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($previous_months as $month) { ?>
                                <tr>
                                    <td data-label="Month"><?php echo date('F Y', mktime(0, 0, 0, $month['month'], 1, $month['year'])); ?></td>
                                    <td data-label="Total (₨)">₨<?php echo number_format($month['total'], 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p class="no-records">No previous months' expense data available for analysis.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>