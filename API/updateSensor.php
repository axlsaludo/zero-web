<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = file_get_contents('php://input');

    // Save data to a file
    file_put_contents('sensor_data.txt', $data);

    echo "Sensor data updated.";
}
?>
