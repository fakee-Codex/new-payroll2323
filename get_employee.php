<?php
require 'database_connection.php'; // Include the database connection

// Check if employee_id is provided
if (isset($_GET['employee_id'])) {
    $employee_id = intval($_GET['employee_id']);

    // Fetch employee details from the database
    $sql = "SELECT employee_id, employee_id_number, first_name, last_name, job_title_id, department_id, status 
            FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch employee data as an associative array
    $employee = $result->fetch_assoc();

    // Return employee data as JSON
    echo json_encode($employee);
}
?>
