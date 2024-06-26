<?php
session_start();
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
    <div>
        <h2>Welcome 
            <?php
            if (isset($_SESSION['user_firstname'])) {
                echo htmlspecialchars($_SESSION['user_firstname']);
            } else {
                echo "Guest";
            }
            ?>!
        </h2>

    </div>
</body>

<footer>
    <div class="foot">
        <a href="https://github.com/axlsaludo">github</a>
        <a href="https://www.youtube.com/@logiclaboratories">youtube</a>
        <a href="https://open.spotify.com/artist/2fn8GXn4sJ3MPYOe9MJJjm?si=SXnMZoZ2S8io3Um86cotFA">spotify</a>   
    </div>
    <div>
        <a class="dot" href="https://logiclaboratories.vercel.app/">...</a>
    </div>
</footer>



</html>
