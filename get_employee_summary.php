<?php
require 'database_connection.php'; // Include the database connection

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

$employee_id = isset($_GET['employee_id']) ? intval($_GET['employee_id']) : 0;

if ($employee_id > 0) {
    // Fetch employee basic info
    $sql = "SELECT first_name, last_name, employee_id_number, date_of_joining, date_inactive, status, reason
            FROM employees
            WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employeeData = $result->fetch_assoc();

        // Fetch total hours for the employee from the attendance table
        $hours_sql = "SELECT SUM(total_hours) AS total_hours 
                      FROM attendance
                      WHERE employee_id = ?";
        $stmt_hours = $conn->prepare($hours_sql);
        $stmt_hours->bind_param("i", $employee_id);
        $stmt_hours->execute();
        $hours_result = $stmt_hours->get_result();

        // Fetch the current hourly rate for the employee from the daily_rate table
        $rate_sql = "SELECT hourly_rate 
                     FROM daily_rate 
                     WHERE employee_id = ? 
                     AND (end_date IS NULL OR end_date >= CURDATE()) 
                     ORDER BY start_date DESC LIMIT 1";
        $stmt_rate = $conn->prepare($rate_sql);
        $stmt_rate->bind_param("i", $employee_id);
        $stmt_rate->execute();
        $rate_result = $stmt_rate->get_result();

        // Calculate total pay
        if ($hours_result->num_rows > 0 && $rate_result->num_rows > 0) {
            $hoursData = $hours_result->fetch_assoc();
            $rateData = $rate_result->fetch_assoc();

            $total_hours = $hoursData['total_hours'];
            $hourly_rate = $rateData['hourly_rate'];

            // Calculate total pay
            $total_pay = $total_hours * $hourly_rate;

            $employeeData['total_hours'] = $total_hours;
            $employeeData['total_pay'] = $total_pay;
        } else {
            $employeeData['total_hours'] = 0;
            $employeeData['total_pay'] = 0.00;
        }

        echo json_encode($employeeData); // Output the employee data as JSON
    } else {
        echo json_encode(['error' => 'No employee found.']); // Error if no employee
    }
} else {
    echo json_encode(['error' => 'Invalid employee ID.']); // Error if invalid employee ID
}

$conn->close();
