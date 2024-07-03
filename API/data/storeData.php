<?php
require_once('../../db/dbconn.php');

function fetchDataFromAPI() {
    $url = "https://data.wa.gov/resource/3d5d-sdqb.json?\$limit=5000";
    $data = file_get_contents($url);

    // Check if data retrieval was successful
    if ($data === false) {
        throw new Exception("Failed to fetch data from API: " . error_get_last()['message']);
    }

    // Validate content type
    $headers = get_headers($url, 1);
    if (strpos($headers['Content-Type'], 'application/json') === false) {
        throw new Exception("Unexpected content type: " . $headers['Content-Type']);
    }

    // Attempt to decode JSON
    $jsonData = json_decode($data, true);
    if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Failed to decode JSON data: " . json_last_error_msg());
    }

    return $jsonData;
}

try {
    $data = fetchDataFromAPI();

    // Connect to database
    $db = new Database();
    $conn = $db->getConnection();

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO vehicles (date, county, state, vehicle_primary_use, electric_vehicle_ev_total, non_electric_vehicles, total_vehicles, percent_electric_vehicles) 
                            VALUES (:date, :county, :state, :vehicle_primary_use, :electric_vehicle_ev_total, :non_electric_vehicles, :total_vehicles, :percent_electric_vehicles)");

    // Insert each entry into the database
    foreach ($data as $entry) {
        // Check and filter required fields
        if (isset($entry['date']) && isset($entry['county']) && isset($entry['state']) &&
            isset($entry['vehicle_primary_use']) && isset($entry['electric_vehicle_ev_total']) &&
            isset($entry['non_electric_vehicles']) && isset($entry['total_vehicles']) &&
            isset($entry['percent_electric_vehicles'])) {
            
            $stmt->bindParam(':date', $entry['date']);
            $stmt->bindParam(':county', $entry['county']);
            $stmt->bindParam(':state', $entry['state']);
            $stmt->bindParam(':vehicle_primary_use', $entry['vehicle_primary_use']);
            $stmt->bindParam(':electric_vehicle_ev_total', $entry['electric_vehicle_ev_total']);
            $stmt->bindParam(':non_electric_vehicles', $entry['non_electric_vehicles']);
            $stmt->bindParam(':total_vehicles', $entry['total_vehicles']);
            $stmt->bindParam(':percent_electric_vehicles', $entry['percent_electric_vehicles']);
            $stmt->execute();
        }
    }

    // Return success response
    echo json_encode(['success' => true, 'message' => 'Data stored successfully']);

} catch (Exception $e) {
    // Return error response
    $errorMessage = $e->getMessage();
    error_log("Error storing data: $errorMessage");
    echo json_encode(['success' => false, 'message' => $errorMessage]);
}
?>
