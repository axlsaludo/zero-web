<?php
header('Content-Type: application/json');

// Read the parameters from the URL
$index = isset($_GET['index']) ? intval($_GET['index']) : null;
$state = isset($_GET['state']) ? $_GET['state'] : null;

if ($index !== null && $state !== null) {
    // Process the LED control commands
    // For example, write the command to a file or send it to Arduino via a serial port
    $command = "toggle led " . $index;
    if ($state === 'toggle') {
        $command = "toggle all leds";
    }
    
    // Log the command or send it to Arduino
    file_put_contents('led_command.txt', $command);
    
    echo json_encode(['message' => "LED $index turned $state"]);
} else {
    echo json_encode(['message' => 'Invalid parameters']);
}
?>
