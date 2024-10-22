<?php
require 'database_connection.php'; // Include your database connection

$employee_id = $_GET['employee_id'];
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Basic query to fetch logs
$query = "SELECT date, total_hours, regular_hours, overtime_hours, status FROM attendance WHERE employee_id = $employee_id";

// Add date filtering to the query if both start_date and end_date are provided
if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND date BETWEEN '$start_date' AND '$end_date'";
}

$logs = [];
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $logs[] = [
        'date' => date("F j, Y", strtotime($row['date'])),
        'total_hours' => $row['total_hours'],
        'regular_hours' => $row['regular_hours'],
        'overtime_hours' => $row['overtime_hours'],
        'status' => $row['status']
    ];
}

echo json_encode(['logs' => $logs]);
?>
