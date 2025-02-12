<?php
require 'database_connection.php';

if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];

    // Fetch employee details
    $employee_sql = "SELECT * FROM employees WHERE employee_id = '$employee_id'";
    $employee_result = $conn->query($employee_sql);
    $employee_data = $employee_result->fetch_assoc();

    // Fetch contribution percentages
    $percentage_sql = "SELECT * FROM contribution_percentages ORDER BY percentage_id DESC LIMIT 1";
    $percentage_result = $conn->query($percentage_sql);
    $percentage_data = $percentage_result->fetch_assoc();

    // Prepare response
    $response = array(
        'employee' => $employee_data,
        'percentages' => $percentage_data
    );

    echo json_encode($response);
} else {
    echo json_encode(array('error' => 'Employee ID not provided'));
}
?>
