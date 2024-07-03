<?php
require_once('../../db/dbconn.php');
require_once('../../models/user.php');

function fetchDataFromAPI() {
    $url = "https://data.wa.gov/resource/3d5d-sdqb.json?\$limit=5000";
    $data = file_get_contents($url);

    if ($data === false) {
        throw new Exception("Failed to fetch data from API: " . error_get_last()['message']);
    }

    $headers = get_headers($url, 1);
    if (strpos($headers['Content-Type'], 'application/json') === false) {
        throw new Exception("Unexpected content type: " . $headers['Content-Type']);
    }

    $jsonData = json_decode($data, true);
    if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Failed to decode JSON data: " . json_last_error_msg());
    }
    return $jsonData;
}   

try {

    $db = new Database();
    $conn = $db->getConnection();

    $user = new User($conn);
    $data = fetchDataFromAPI();

    $table = 'vehicles';
    $columns = ['date', 'county', 'state', 'vehicle_primary_use', 'electric_vehicle_ev_total', 'non_electric_vehicles', 'total_vehicles', 'percent_electric_vehicles'];
    $successCount = 0;

    foreach ($data as $entry) {
        $values = [
            'date' => $entry['date'],
            'county' => $entry['county'],
            'state' => $entry['state'],
            'vehicle_primary_use' => $entry['vehicle_primary_use'],
            'electric_vehicle_ev_total' => $entry['electric_vehicle_ev_total'],
            'non_electric_vehicles' => $entry['non_electric_vehicles'],
            'total_vehicles' => $entry['total_vehicles'],
            'percent_electric_vehicles' => $entry['percent_electric_vehicles']
        ];

        if ($user->insertData($table, $columns, $values)) {
            $successCount++;
        }
    }


    echo json_encode(['success' => true, 'message' => "$successCount records stored successfully"]);

} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    error_log("Error storing data: $errorMessage");
    echo json_encode(['success' => false, 'message' => $errorMessage]);
}
?>
