<?php
// fetch_data.php

header('Content-Type: application/json');

// Fetch data from the API
$url = 'https://data.wa.gov/resource/3d5d-sdqb.json';
$data = file_get_contents($url);
$json_data = json_decode($data, true);

// Process data to remove unwanted columns
$filtered_data = array_map(function($entry) {
    return [
        'date' => $entry['date'],
        'county' => $entry['county'],
        'state' => $entry['state'],
        'vehicle_primary_use' => $entry['vehicle_primary_use'],
        'electric_vehicle_ev_total' => $entry['electric_vehicle_ev_total'],
        'non_electric_vehicles' => $entry['non_electric_vehicles'],
        'total_vehicles' => $entry['total_vehicles'],
        'percent_electric_vehicles' => $entry['percent_electric_vehicles']
    ];
}, $json_data);

echo json_encode($filtered_data);
?>
