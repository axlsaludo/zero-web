<?php
include '../db/dbconn.php';
include '../db/sessionCheck.php';
?>

<!DOCTYPE html>
<html lang="en">

<?php include '../components/headpages.html';?>
<link rel="stylesheet" href="../css/landing.css">

<header>
    <h1>Dashboard</h1>
</header>

<!-- Logout Button -->
<div class="logout">
    <form action="../db/logout.php" method="post">
        <button type="submit">Logout</button>
    </form>
</div>


<body>
    <main>      
        <div class="card-container">
            <div class="reg-data-table">
                <h3>Registered Users</h3>
                <?php
                // Fetch registered users
                $stmt = $conn->prepare("SELECT id, first_name, last_name FROM users");
                $stmt->execute();
                $registeredUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($registeredUsers) {
                    echo '<ul>';
                    foreach ($registeredUsers as $user) {
                        echo '<li>' . $user['id'] . ' | ' . $user['first_name'] . ' ' . $user['last_name'] . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>No registered users found.</p>';
                }
                ?>
            </div>

            <div class="active-user-data">
                <h3>Active Users</h3>
                <?php
                // Assuming you have a mechanism to track active users, e.g., a field 'is_active' in the users table
                $stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE is_active = 1");
                $stmt->execute();
                $activeUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($activeUsers) {
                    echo '<ul>';
                    foreach ($activeUsers as $user) {
                        echo '<li>Status: Online | ' . $user['first_name'] . ' ' . $user['last_name'] . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>No active users found.</p>';
                }
                ?>
            </div>
        </div>
    </main>

    

</body>

<?php include '../components/footer.html';?>

</html>
