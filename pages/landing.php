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

<?php include '../components/head.html';?>

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

<?php include '../components/footer.html';?>

</html>
