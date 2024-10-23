<?php
require 'database_connection.php'; // Include the database connection

// Get the filter from the request
$filter = $_POST['filter'];

// Initialize SQL query based on the filter
if ($filter == 'full-time') {
    // Fetch only active full-time employees
    $sql = "SELECT e.employee_id, e.employee_id_number,e.first_name, e.last_name, jt.job_title, d.department_name, e.status, e.employment_status, e.date_of_joining 
            FROM employees e
            JOIN job_titles jt ON e.job_title_id = jt.job_title_id
            JOIN departments d ON e.department_id = d.department_id
            WHERE e.employment_status = 'full-time' AND e.status = 'active'"; // Active full-time employees only
} elseif ($filter == 'part-time') {
    // Fetch only active part-time employees
    $sql = "SELECT e.employee_id, e.employee_id_number,e.first_name, e.last_name, jt.job_title, d.department_name, e.status, e.employment_status, e.date_of_joining 
            FROM employees e
            JOIN job_titles jt ON e.job_title_id = jt.job_title_id
            JOIN departments d ON e.department_id = d.department_id
            WHERE e.employment_status = 'part-time' AND e.status = 'active'"; // Active part-time employees only
} else {
    // Fetch all active employees
    $sql = "SELECT e.employee_id, e.employee_id_number, e.first_name, e.last_name, jt.job_title, d.department_name, e.status, e.employment_status, e.date_of_joining 
            FROM employees e
            JOIN job_titles jt ON e.job_title_id = jt.job_title_id
            JOIN departments d ON e.department_id = d.department_id
            WHERE e.status = 'active'"; // Active employees only
}

$result = $conn->query($sql);

// Generate the table rows dynamically based on the result
while ($row = $result->fetch_assoc()) {
    // Display for active employees
    echo "<tr>";
    echo "<td style='display:none;'>" . htmlspecialchars($row['employee_id']) . "</td>";
    
    echo "<td>" . htmlspecialchars($row['employee_id_number']) . "</td>";
    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
    echo "<td>" . htmlspecialchars($row['department_name']) . "</td>";
    echo "<td>" . date("F j, Y", strtotime($row['date_of_joining'])) . "</td>";
    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
    echo "<td>" . htmlspecialchars($row['employment_status']) . "</td>";
    echo "<td><a href='javascript:void(0);' onclick='openModalForEdit(" . $row['employee_id'] . ")'>Edit</a></td>";
    echo "</tr>";
}
?>
