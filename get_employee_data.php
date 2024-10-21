<?php
session_start();

// Database connection
require 'database_connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Prepare the query to fetch payroll data grouped by month and year
$query = "
    SELECT 
        DATE_FORMAT(a.date, '%M %Y') AS month_year, 
        SUM((a.regular_hours * (dr.daily_rate / 8)) + (a.overtime_hours * (dr.daily_rate / 8 * 1.5))) AS total_pay,
        IFNULL(SUM(l.loan_amount / l.loan_terms), 0) AS total_loan_deduction
    FROM employees e
    LEFT JOIN attendance a ON e.employee_id = a.employee_id
    LEFT JOIN daily_rate dr ON e.employee_id = dr.employee_id AND dr.end_date IS NULL
    LEFT JOIN loans l ON e.employee_id = l.employee_id AND l.remaining_balance > 0
    WHERE a.date IS NOT NULL
    GROUP BY DATE_FORMAT(a.date, '%Y-%M')  -- Group by year and month
    ORDER BY DATE_FORMAT(a.date, '%Y-%m')  -- Order by year and month (numeric)
";

// Execute the query
$result = mysqli_query($conn, $query);

if (!$result) {
    // If the query fails, return an error message
    echo json_encode([
        'error' => 'Error executing query: ' . mysqli_error($conn)
    ]);
    exit;
}

// Initialize arrays to hold months/years and total net pays
$months = [];
$total_net_pays = [];

// Fetch the results
while ($row = mysqli_fetch_assoc($result)) {
    $net_pay = $row['total_pay'] - $row['total_loan_deduction']; // Subtract loan deductions from total pay
    $months[] = $row['month_year']; // Add the month and year to the list
    $total_net_pays[] = (float)$net_pay; // Add the net pay
}

// Return the data as JSON
echo json_encode([
    'months' => $months,
    'total_net_pays' => $total_net_pays
]);

?>
