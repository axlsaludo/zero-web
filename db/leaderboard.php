<?php
// Database credentials
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'accounts'; // Database name for leaderboards

// PDO connection
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    // Connect to MySQL using PDO
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create leaderboard_scores table if not exists
    $createTableSql = "
        CREATE TABLE IF NOT EXISTS leaderboard_scores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            score INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    // Execute the create table query
    $pdo->exec($createTableSql);

    // Query to fetch leaderboard scores
    $stmt = $pdo->query("SELECT u.first_name, u.last_name, ls.score
                         FROM leaderboard_scores ls
                         INNER JOIN users u ON ls.user_id = u.id
                         ORDER BY ls.score DESC
                         LIMIT 10"); // Limit to top 10 scores

    // Fetch leaderboard data into an array
    $leaderboardData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed or query error: " . $e->getMessage();
    exit;
}
?>
