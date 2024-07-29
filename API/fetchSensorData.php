<?php
include '../db/Database.php';

$db = new Database();
$connection = $db->getConnection();

$query = "SELECT * FROM sensor_data ORDER BY id DESC LIMIT 1";
$stmt = $connection->prepare($query);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
    echo json_encode($data);
} else {
    echo json_encode([]);
}
?>
