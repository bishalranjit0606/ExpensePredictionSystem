<?php
session_start();
require_once '../includes/db_config.php';
require_once '../includes/RecommendationEngine.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$recommender = new RecommendationEngine($conn);
$recommendations = $recommender->getRecommendations($user_id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Recommendations</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-dark: #388E3C;
            --secondary-color: #2196F3;
            --background-light: #f7f9fc;
            --card-background: #ffffff;
            --text-dark: #333333;
            --text-light: #555555;
            --border-color: #e0e0e0;
            --shadow-light: rgba(0, 0, 0, 0.08);
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
            max-width: 800px;
            margin: 30px auto;
            background: var(--card-background);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px var(--shadow-light);
        }

        h2 {
            color: var(--text-dark);
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 2.2em;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            display: inline-block;
        }

        .btn {
            padding: 10px 20px;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #1976D2;
        }

        .recommendation-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 5px solid var(--primary-color);
        }

        .recommendation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .category-tag {
            display: inline-block;
            padding: 4px 10px;
            background: #e8f5e9;
            color: var(--primary-dark);
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .tip-content {
            font-size: 1.1em;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .action-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9em;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        .no-recs {
            text-align: center;
            padding: 40px;
            color: var(--text-light);
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="btn">&larr; Back to Dashboard</a>
        <br>
        <h2>Smart Recommendations</h2>
        <p>Based on your recent spending habits, here are some personalized tips to help you save.</p>

        <?php if (!empty($recommendations)): ?>
            <?php foreach ($recommendations as $rec): ?>
                <div class="recommendation-card">
                    <span class="category-tag"><?php echo htmlspecialchars($rec['category']); ?></span>
                    <div class="tip-content">
                        <?php echo htmlspecialchars($rec['tip']); ?>
                    </div>
                    <?php if (!empty($rec['action_link'])): ?>
                        <a href="<?php echo htmlspecialchars($rec['action_link']); ?>" target="_blank" class="action-link">Learn More &rarr;</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-recs">
                <p>Start adding expenses to get personalized recommendations!</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
