<?php
header('Content-Type: application/json');

require_once '../../db/data.php'; // Adjust the path as needed
require_once '../../db/dbconn.php'; // Adjust the path as needed

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

$success = true;
$error = null;

try {
    $conn->beginTransaction();
    $stmt = $conn->prepare("INSERT INTO vehicles (date, county, state, vehicle_primary_use, electric_vehicle_ev_total, non_electric_vehicles, total_vehicles, percent_electric_vehicles) VALUES (:date, :county, :state, :vehicle_primary_use, :electric_vehicle_ev_total, :non_electric_vehicles, :total_vehicles, :percent_electric_vehicles)");
    foreach ($data as $entry) {
        $stmt->execute([
            ':date' => $entry['date'],
            ':county' => $entry['county'],
            ':state' => $entry['state'],
            ':vehicle_primary_use' => $entry['vehicle_primary_use'],
            ':electric_vehicle_ev_total' => $entry['electric_vehicle_ev_total'],
            ':non_electric_vehicles' => $entry['non_electric_vehicles'],
            ':total_vehicles' => $entry['total_vehicles'],
            ':percent_electric_vehicles' => $entry['percent_electric_vehicles']
        ]);
    }
    $conn->commit();
} catch (Exception $e) {
    $conn->rollBack();
    $success = false;
    $error = $e->getMessage();
}

echo json_encode(['success' => $success, 'error' => $error]);
?>
