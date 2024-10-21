<?php
require 'database_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];

    // Update other deductions status to 'paid'
    $query = "UPDATE other_deductions SET status = 'paid' WHERE employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $employee_id);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Other deductions marked as paid successfully']);
    } else {
        echo json_encode(['message' => 'Failed to update deductions status']);
    }

    $stmt->close();
}
?>
