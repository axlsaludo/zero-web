<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $ir = $_POST['ir'];
    $ldr = $_POST['ldr'];
    $leds = $_POST['leds'];

    // Store the data or perform any desired operations
    // For simplicity, we'll just echo the data back
    echo json_encode(array(
        'temperature' => $temperature,
        'humidity' => $humidity,
        'ir' => $ir,
        'ldr' => $ldr,
        'leds' => $leds
    ));
}
?>
