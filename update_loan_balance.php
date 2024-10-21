<?php
session_start();
require 'database_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employee_id'], $_POST['loan_deduction'])) {
    $employee_id = $_POST['employee_id'];
    $loan_deduction = floatval($_POST['loan_deduction']); // Ensure loan deduction is a float value

    error_log("Updating loan balance for Employee ID: $employee_id with Loan Deduction: $loan_deduction");

    // Fetch the current loan information for the employee, including the original loan amount
    $loan_query = "SELECT loan_id, remaining_balance, loan_amount, loan_terms FROM loans WHERE employee_id = ? AND remaining_balance > 0 LIMIT 1";
    $stmt = $conn->prepare($loan_query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $loan = $result->fetch_assoc();
        $loan_id = $loan['loan_id'];
        $remaining_balance = floatval($loan['remaining_balance']); // Ensure remaining balance is a float
        $loan_amount = floatval($loan['loan_amount']); // Original loan amount
        $loan_terms = intval($loan['loan_terms']); // Number of terms

        error_log("Current Remaining Balance: $remaining_balance");
        error_log("Original Loan Amount: $loan_amount");
        error_log("Loan Terms: $loan_terms");

        // Calculate the correct deduction based on the original loan amount and loan terms
        $expected_deduction = $loan_amount / $loan_terms;
        error_log("Expected Loan Deduction Per Term: $expected_deduction");

        // If the passed deduction doesn't match expected, use the expected deduction
        if ($loan_deduction != $expected_deduction) {
            $loan_deduction = $expected_deduction;
            error_log("Corrected Loan Deduction: $loan_deduction");
        }

        // Calculate new remaining balance after the correct deduction
        $new_remaining_balance = $remaining_balance - $loan_deduction;
        if ($new_remaining_balance < 0) {
            $new_remaining_balance = 0; // Ensure the balance doesn't go below zero
        }

        error_log("New Remaining Balance: $new_remaining_balance");

        // Update the loan's remaining balance
        $update_query = "UPDATE loans SET remaining_balance = ? WHERE loan_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("di", $new_remaining_balance, $loan_id);
        if ($update_stmt->execute()) {
            // Return a success message
            echo json_encode(['status' => 'success', 'message' => 'Loan balance updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating loan balance']);
        }
    } else {
        // No active loan found for this employee
        echo json_encode(['status' => 'error', 'message' => 'No active loan found for this employee']);
    }
} else {
    // Invalid request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
