<?php
require_once('../../db/dbconn.php');

function fetchDataFromAPI() {
    $url = "https://data.wa.gov/resource/3d5d-sdqb.json";
    $url .= "?\$limit=5000"; // Add the limit parameter to fetch 5000 records
    $data = file_get_contents($url);

    // Check if data retrieval was successful
    if ($data === false) {
        throw new Exception("Failed to fetch data from API: " . error_get_last()['message']);
    }

    // Validate content type
    $contentType = mime_content_type($url);
    if ($contentType !== 'application/json') {
        throw new Exception("Unexpected content type: $contentType");
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

    // Return success response
    echo json_encode(['success' => true, 'message' => 'Data stored successfully']);

} catch (Exception $e) {
    // Return error response
    $errorMessage = $e->getMessage();
    error_log("Error storing data: $errorMessage");
    echo json_encode(['success' => false, 'message' => $errorMessage]);
}
?>
