<?php
require 'database_connection.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

    // Debugging - Check if the POST data is received
    if (is_null($start_date) || is_null($end_date)) {
        echo json_encode(['error' => 'Missing start or end date']);
        exit();
    }

    // Check if the payroll history for the given date range already exists
    $check_query = "SELECT * FROM payroll_history WHERE start_date = ? AND end_date = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param('ss', $start_date, $end_date);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Record already exists
        echo json_encode(['error' => 'Payroll history for this date range already exists.']);
    } else {
        // Insert the new payroll history record
        $query = "INSERT INTO payroll_history (start_date, end_date, date_generated) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if ($stmt->execute()) {
            echo json_encode(['success' => 'Payroll history added successfully.']);
        } else {
            echo json_encode(['error' => 'Failed to add payroll history. Error: ' . $stmt->error]);
        }
    }
}
?>
