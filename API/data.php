<?php
// Fetch data from the API
$api_url = 'https://data.wa.gov/resource/3d5d-sdqb.json';
$data = file_get_contents($api_url);
$vehicles = json_decode($data, true);

// Process the data as needed
$processed_data = array_map(function($item) {
    return [
        'date' => date("m/d/Y", strtotime($item['date'])),
        'county' => $item['county'],
        'state' => $item['state'],
        'non_electric_vehicles' => $item['non_electric_vehicles'],
        'total_vehicles' => $item['total_vehicles'],
        'percent_non_electric' => ($item['total_vehicles'] > 0) ? number_format(($item['non_electric_vehicles'] / $item['total_vehicles']) * 100, 2) . '%' : 'N/A'
    ];
}, $vehicles);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($processed_data);
?>
