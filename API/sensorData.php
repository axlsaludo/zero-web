<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET");  // Allow GET and POST requests
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Database configuration
$host = 'localhost';
$db_name = 'accounts';
$username = 'root';
$password = '';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('error' => 'Database connection failed'));
    exit();
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prepare and execute the query to get the latest sensor data
        $stmt = $pdo->prepare("SELECT temperature, humidity, ir, ldr, leds FROM sensor_data ORDER BY created_at DESC LIMIT 1");
        $stmt->execute();
        
        // Fetch the latest data
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            // Output the JSON data
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'No data found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Query failed'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST requests to save data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() === JSON_ERROR_NONE && isset($input['temperature'], $input['humidity'], $input['ir'], $input['ldr'], $input['leds'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO sensor_data (temperature, humidity, ir, ldr, leds) VALUES (:temperature, :humidity, :ir, :ldr, :leds)");
            $stmt->execute(array(
                ':temperature' => $input['temperature'],
                ':humidity' => $input['humidity'],
                ':ir' => $input['ir'],
                ':ldr' => $input['ldr'],
                ':leds' => json_encode($input['leds'])
            ));
            echo json_encode(array('status' => 'success'));
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('error' => 'Insert failed'));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
    }
} else {
    // If not a GET or POST request, return an error response
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}
?>
