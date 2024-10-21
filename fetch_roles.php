<?php
require 'database_connection.php'; // Include your database connection

// Fetch all roles
$sql = "SELECT * FROM roles";
$result = $conn->query($sql);

// Prepare roles array
$roles = [];

while ($row = $result->fetch_assoc()) {
    $roles[] = [
        'role_id' => $row['role_id'],
        'role_name' => $row['role_name']
    ];
}

// Return the roles in JSON format
header('Content-Type: application/json');
echo json_encode($roles);

$conn->close();
