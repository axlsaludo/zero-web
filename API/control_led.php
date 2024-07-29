<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $index = $_POST['index'];
    $state = $_POST['state'];

    $url = 'http://localhost:8000/API/control_led.php';
    $data = array('index' => $index, 'state' => $state);

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to communicate with the server'));
    } else {
        echo $result;
    }
}
?>
