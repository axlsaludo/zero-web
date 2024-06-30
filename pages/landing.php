<?php
    // Include headpages.html for common head elements
    include '../components/headpages.html';

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

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../css/landing.css">
    <link rel="stylesheet" href="../css/game.css">
</head>

<body>
    <header>
        <h1>Snakes Game</h1>
    </header>

    <!-- Logout Button -->
    <div class="logout">
        <form action="../db/logout.php" method="post">
            <button type="submit">Logout</button>
        </form>
    </div>

    <main>
        <div class="card-container">

            <div class="stage"></div> 

            <div class="leaderboards">
                <h3>Leaderboards</h3>
                <p>Rank  |  Name -  Score</p>
                <p>------------------------</p>
                <p> 1 | Kurt Axl Saludo - 50</p>
                <p>------------------------</p>
                <p>this is mock data only</p>
                <p>(waiting for ajax support)</p>    
                <?php
                    // Display leaderboard scores dynamically
                    foreach ($leaderboardData as $index => $row) {
                        $rank = $index + 1;
                        $fullName = $row['first_name'] . ' ' . $row['last_name'];
                        $score = $row['score'];
                        echo "<p>$rank | $fullName - $score</p>";
                    }
                ?>
            </div>

            <div class="controls">
                <h3>Score</h3>
                <div class="score">0</div>
            </div>

        </div>    
    </main> 
    <script src="../js/game.js"></script>
</body>
</html>
