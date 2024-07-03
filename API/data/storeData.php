<?php
require_once('../../db/dbconn.php');
require_once('../../models/user.php');

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
    // Connect to database
    $db = new Database();
    $conn = $db->getConnection();

    // Instantiate User class
    $user = new User($conn);

    // Fetch data from API
    $data = fetchDataFromAPI();

    // Define columns and prepare data for insertion
    $table = 'vehicles'; // Replace with your target table name
    $columns = ['date', 'county', 'state', 'vehicle_primary_use', 'electric_vehicle_ev_total', 'non_electric_vehicles', 'total_vehicles', 'percent_electric_vehicles'];
    $successCount = 0;

    foreach ($data as $entry) {
        // Prepare values for insertion
        $values = [
            $entry['date'],
            $entry['county'],
            $entry['state'],
            $entry['vehicle_primary_use'],
            $entry['electric_vehicle_ev_total'],
            $entry['non_electric_vehicles'],
            $entry['total_vehicles'],
            $entry['percent_electric_vehicles']
        ];

        // Insert data into the specified table
        if ($user->insertData($table, $columns, $values)) {
            $successCount++;
        }
    }

    // Return success response
    echo json_encode(['success' => true, 'message' => "$successCount records stored successfully"]);

} catch (Exception $e) {
    // Return error response
    $errorMessage = $e->getMessage();
    error_log("Error storing data: $errorMessage");
    echo json_encode(['success' => false, 'message' => $errorMessage]);
}
?>
