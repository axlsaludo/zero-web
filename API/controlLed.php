<?php
header('Content-Type: application/json');

// Get POST data
$index = isset($_POST['index']) ? intval($_POST['index']) : null;
$state = isset($_POST['state']) ? $_POST['state'] : null;

// Validate input
if ($index === null || !in_array($state, ['on', 'off'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Prepare command to write
$command = "toggle led " . $index;

// Write to file
file_put_contents('led_command.txt', $command);

// Example response
$response = [
    'index' => $index,
    'state' => $state
];
echo json_encode($response);
?>
