<?php
require_once 'includes/db_config.php';

try {
    // Update existing users with empty email/phone to have unique values
    $stmt = $conn->query("SELECT id FROM users WHERE email = '' OR email IS NULL");
    while ($row = $stmt->fetch()) {
        $uniqueEmail = "user" . $row['id'] . "@example.com";
        $conn->exec("UPDATE users SET email = '$uniqueEmail' WHERE id = " . $row['id']);
    }
    
    $stmt = $conn->query("SELECT id FROM users WHERE phone_number = '' OR phone_number IS NULL");
    while ($row = $stmt->fetch()) {
        $uniquePhone = "000000000" . $row['id'];
        $conn->exec("UPDATE users SET phone_number = '$uniquePhone' WHERE id = " . $row['id']);
    }
    
    echo "Data cleanup completed.<br>";

    // Add UNIQUE constraint to email
    $sql = "ALTER TABLE users ADD UNIQUE (email)";
    $conn->exec($sql);
    echo "Added UNIQUE constraint to email successfully.<br>";
} catch(PDOException $e) {
    echo "Error adding UNIQUE constraint to email: " . $e->getMessage() . "<br>";
}

try {
    // Add UNIQUE constraint to phone_number
    $sql = "ALTER TABLE users ADD UNIQUE (phone_number)";
    $conn->exec($sql);
    echo "Added UNIQUE constraint to phone_number successfully.<br>";
} catch(PDOException $e) {
    echo "Error adding UNIQUE constraint to phone_number: " . $e->getMessage() . "<br>";
}

echo "Schema update process completed.";
?>
