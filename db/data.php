<?php

function fetchDataFromAPI() {
    $url = "https://data.wa.gov/resource/3d5d-sdqb.json";
    $url .= "?\$limit=5000"; // Add the limit parameter to fetch 5000 records
    $data = file_get_contents($url);
    return json_decode($data, true);
}

$data = fetchDataFromAPI();

// Output the fetched data for testing purposes
header('Content-Type: application/json');
echo json_encode($data);

?>
