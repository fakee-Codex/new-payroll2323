<?php
// log_error.php

// Get the raw POST data (JSON format)
$inputData = file_get_contents('php://input');
$data = json_decode($inputData, true);

// Check if the data exists
if (isset($data['data'])) {
    // Convert the data into a string
    $logData = json_encode($data['data'], JSON_PRETTY_PRINT);

    // Append the log data to error_log.txt
    $logFile = 'error_log.txt';  // Make sure this file is writable by the web server
    file_put_contents($logFile, $logData . PHP_EOL, FILE_APPEND);

    // Send a JSON response back to JavaScript
    echo json_encode(['status' => 'success', 'message' => 'Data logged successfully']);
} else {
    // If no data is received, send an error response
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
?>
