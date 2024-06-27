<?php
include '../db/dbconn.php';
include '../db/sessionCheck.php'; // Include the session check

// Fetch the current logged-in user details
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donut</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main>
        <div>
            <h2>Welcome 
                <?php
                if ($user) {
                    // Process user data
                    echo htmlspecialchars($user['first_name']) . " " . htmlspecialchars($user['last_name']);
                } else {
                    echo "User not found.";
                }
                ?>
            </h2>
            <a href="../db/logout.php">Logout</a>
        </div>
    </main>
</body>

<div id="footerDiv"><object type="text/html" data="footer.html" style="overflow:auto; width: 100%; height: 100%"></object></div>

<footer>
    <div class="foot">
        <a class="ft-link" href="https://github.com/axlsaludo">github</a>
        <a class="ft-link" href="https://www.youtube.com/@logiclaboratories">youtube</a>
        <a class="ft-link" href="https://open.spotify.com/artist/2fn8GXn4sJ3MPYOe9MJJjm?si=SXnMZoZ2S8io3Um86cotFA">spotify</a>   
    </div>
    <div>
        <a class="dot" href="https://logiclaboratories.vercel.app/">...</a>
    </div>
</footer>
</html>
