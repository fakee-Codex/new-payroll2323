<?php
require 'database_connection.php'; // Include the database connection

if (isset($_POST['department_id'])) {
    $department_id = intval($_POST['department_id']);
    
    // Fetch job titles based on the selected department
    $sql = "SELECT job_title_id, job_title FROM job_titles WHERE department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Create an array to store the job titles
    $job_titles = [];
    
    // Fetch each row and add it to the array
    while ($row = $result->fetch_assoc()) {
        $job_titles[] = $row;
    }
    
    // Return job titles as JSON
    echo json_encode($job_titles);
}
?>
