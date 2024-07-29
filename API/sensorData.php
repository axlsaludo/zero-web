<?php
header('Content-Type: application/json');

// Specify the file where data will be stored
$filename = 'sensor_data.txt';

// Handle POST request to save incoming JSON data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    file_put_contents($filename, $input);
}

// Read data from the file
if (file_exists($filename)) {
    echo file_get_contents($filename);
} else {
    echo json_encode([
        'temperature' => '--',
        'humidity' => '--',
        'ldr' => '--',
        'ir' => '--'
    ]);
}
?>
