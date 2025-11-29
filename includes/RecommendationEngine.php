<?php
class RecommendationEngine {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Analyze user's expenses to find top spending categories.
     * 
     * @param int $userId
     * @param int $limit Number of top categories to return
     * @return array List of categories
     */
    public function getTopCategories($userId, $limit = 3) {
        // Look at expenses from the last 3 months
        $threeMonthsAgo = date('Y-m-d', strtotime('-3 months'));
        
        $stmt = $this->conn->prepare("
            SELECT category, SUM(amount) as total_amount
            FROM expenses
            WHERE user_id = ? AND expense_date >= ?
            GROUP BY category
            ORDER BY total_amount DESC
            LIMIT ?
        ");
        
        // PDO limit needs to be bound as integer
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->bindParam(2, $threeMonthsAgo);
        $stmt->bindParam(3, $limit, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get recommendations based on user's spending habits.
     * Content-Based Filtering: Matches high-spending categories with relevant tips.
     * 
     * @param int $userId
     * @return array List of recommendations
     */
    public function getRecommendations($userId) {
        $topCategories = $this->getTopCategories($userId);
        
        if (empty($topCategories)) {
            // Cold start: Return generic tips if no expense data
            return $this->getGenericTips();
        }

        // Fetch tips for these categories
        $placeholders = str_repeat('?,', count($topCategories) - 1) . '?';
        $sql = "SELECT * FROM financial_tips WHERE category IN ($placeholders) ORDER BY RAND() LIMIT 5";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($topCategories);
        $recommendations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If we have fewer than 3 recommendations, add some generic ones
        if (count($recommendations) < 3) {
            $generic = $this->getGenericTips(3 - count($recommendations), $topCategories);
            $recommendations = array_merge($recommendations, $generic);
        }

        return $recommendations;
    }

    /**
     * Get generic tips, optionally excluding certain categories.
     */
    private function getGenericTips($limit = 3, $excludeCategories = []) {
        $sql = "SELECT * FROM financial_tips";
        $params = [];
        
        if (!empty($excludeCategories)) {
            $placeholders = str_repeat('?,', count($excludeCategories) - 1) . '?';
            $sql .= " WHERE category NOT IN ($placeholders)";
            $params = $excludeCategories;
        }
        
        $sql .= " ORDER BY RAND() LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k + 1, $v);
        }
        $stmt->bindValue(count($params) + 1, $limit, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
