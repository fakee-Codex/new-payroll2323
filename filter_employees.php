<?php
require 'database_connection.php'; // Include your DB connection

$filter = isset($_POST['filter']) ? $_POST['filter'] : 'all';

// Prepare SQL query based on filter
if ($filter === 'full-time') {
    $sql = "
        SELECT e.employee_id, e.first_name, e.last_name, jt.job_title, d.department_name, e.status, e.employment_status, e.date_of_joining
        FROM employees e
        JOIN job_titles jt ON e.job_title_id = jt.job_title_id
        JOIN departments d ON e.department_id = d.department_id
        WHERE e.employment_status = 'full-time'";
} elseif ($filter === 'part-time') {
    $sql = "
        SELECT e.employee_id, e.first_name, e.last_name, jt.job_title, d.department_name, e.status, e.employment_status, e.date_of_joining
        FROM employees e
        JOIN job_titles jt ON e.job_title_id = jt.job_title_id
        JOIN departments d ON e.department_id = d.department_id
        WHERE e.employment_status = 'part-time'";
} else {
    $sql = "
        SELECT e.employee_id, e.first_name, e.last_name, jt.job_title, d.department_name, e.status, e.employment_status, e.date_of_joining
        FROM employees e
        JOIN job_titles jt ON e.job_title_id = jt.job_title_id
        JOIN departments d ON e.department_id = d.department_id";
}

$result = $conn->query($sql);

// Generate HTML table rows for the filtered employees
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['employee_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";  // Ensure job_title exists in the query
        echo "<td>" . htmlspecialchars($row['department_name']) . "</td>";  // Ensure department_name exists in the query
        echo "<td>" . date("F j, Y", strtotime($row['date_of_joining'])) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . htmlspecialchars($row['employment_status']) . "</td>";
        echo "<td><a href='javascript:void(0);' onclick='openModalForEdit(" . $row['employee_id'] . ")'>Edit</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9'>No employees found</td></tr>";
}

$conn->close();
?>
