<?php
// Include the Database class
require_once 'Database.php';

try {
    // Create a new instance of the Database class
    $database = new Database();

    // Function to fetch leaderboard data
    function fetchLeaderboardData($pdo) {
        $stmt = $pdo->query("SELECT u.first_name, u.last_name, ls.score
                             FROM leaderboard_scores ls
                             INNER JOIN users u ON ls.user_id = u.id
                             ORDER BY ls.score DESC
                             LIMIT 10"); // Limit to top 10 scores
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get PDO connection from the Database instance
    $pdo = $database->getConnection();

    // Fetch leaderboard data using the function
    $leaderboardData = fetchLeaderboardData($pdo);

    // Output leaderboard data
    echo "<h1>Leaderboard</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Rank</th><th>Name</th><th>Score</th></tr>";
    $rank = 1;
    foreach ($leaderboardData as $row) {
        echo "<tr>";
        echo "<td>" . $rank . "</td>";
        echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
        echo "<td>" . $row['score'] . "</td>";
        echo "</tr>";
        $rank++;
    }
    echo "</table>";

} catch (Exception $e) {
    echo "Connection failed or query error: " . $e->getMessage();
    exit;
}
?>
