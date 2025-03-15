<?php

require 'database_connection.php';

if (!$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . mysqli_connect_error()]);
    file_put_contents("error_log.txt", "Database connection failed: " . mysqli_connect_error() . "\n", FILE_APPEND);
    exit;
}

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["success" => false, "message" => "No data received"]);
    file_put_contents("error_log.txt", "No data received at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    exit;
}

// Debugging: Log received data
file_put_contents("data_log.txt1", print_r($data, true));

$success = true;
$errorMessage = "";

foreach ($data as $row) {
    $employee_id = $row['employee_id'];
    $full_name = $row['full_name'];
    $basic_salary = $row['basic_salary'];
    $honorarium = $row['honorarium'];
    $overload_hr = $row['overload_hr'];
    $overload_rate = $row['overload_rate'];
    $overload_total = $row['overload_total'];
    $wr_hr = $row['wr_hr'];
    $wr_rate = $row['wr_rate'];
    $wr_total = $row['wr_total'];
    $adjust_hr = $row['adjust_hr'];
    $adjust_rate = $row['adjust_rate'];
    $adjust_total = $row['adjust_total'];
    $watch_hr = $row['watch_hr'];
    $watch_rate = $row['watch_reward'];
    $watch_total = $row['watch_total'];
    $gross_pay = $row['gross_pay'];
    $absent_late_hr = $row['absent_late_hr'];
    $absent_late_rate = $row['absent_lateRate'];
    $absent_late_total = $row['absent_late_total'];
   
    $mp2 = $row['mp2'];
    $sss = $row['sss'];
    $ret = $row['ret'];
    $canteen = $row['canteen'];
    $others = $row['others'];
    $total_deduction = $row['total_deduction'];
    $net_pay = $row['net_pay'];

    $check_sql = "SELECT employee_id FROM computation WHERE employee_id = '$employee_id'";
    $check_result = $conn->query($check_sql);

    if (!$check_result) {
        $success = false;
        $errorMessage = "Error with SELECT query for employee ID: $employee_id - " . mysqli_error($conn);
        file_put_contents("sql_error_log.txt", $errorMessage . "\n", FILE_APPEND);
    }

    if ($check_result && $check_result->num_rows > 0) {
        // If the employee_id exists, update the data
        $update_sql = "UPDATE computation SET
            overload_rate = '$overload_rate', overload_total = '$overload_total', 
            wr_hr = '$wr_hr', wr_rate = '$wr_rate', wr_total = '$wr_total', 
            adjust_hr = '$adjust_hr', adjust_rate = '$adjust_rate', adjust_total = '$adjust_total', 
            watch_hr = '$watch_hr', watch_rate = '$watch_rate', watch_total = '$watch_total', 
            gross_pay = '$gross_pay', absent_late_hr = '$absent_late_hr', absent_late_rate = '$absent_late_rate', 
            absent_late_total = '$absent_late_total', mp2 = '$mp2', sss = '$sss', ret = '$ret',
            canteen = '$canteen', others = '$others', total_deduction = '$total_deduction', net_pay = '$net_pay' 
            WHERE employee_id = '$employee_id'";

        if (!mysqli_query($conn, $update_sql)) {
            $success = false;
            $errorMessage = "Error updating data for employee ID: $employee_id - " . mysqli_error($conn);
            file_put_contents("sql_error_log.txt", $errorMessage . "\n", FILE_APPEND);
        }
    } else {
        // If employee_id does not exist, insert new data
        $insert_sql = "INSERT INTO computation (
            employee_id, overload_rate, overload_total, wr_hr, wr_rate, wr_total, 
            adjust_hr, adjust_rate, adjust_total, watch_hr, watch_rate, watch_total, 
            gross_pay, absent_late_hr, absent_late_rate, absent_late_total,
            mp2, sss, ret, canteen, others, total_deduction, net_pay
        ) VALUES (
            '$employee_id', '$overload_rate', '$overload_total', '$wr_hr', '$wr_rate', '$wr_total', 
            '$adjust_hr', '$adjust_rate', '$adjust_total', '$watch_hr', '$watch_rate', '$watch_total', 
            '$gross_pay', '$absent_late_hr', '$absent_late_rate', '$absent_late_total',
            '$mp2', '$sss', '$ret', '$canteen', '$others', '$total_deduction', '$net_pay'
        )";

        if (!mysqli_query($conn, $insert_sql)) {
            $success = false;
            $errorMessage = "Error inserting data for employee ID: $employee_id - " . mysqli_error($conn);
            file_put_contents("sql_error_log.txt", $errorMessage . "\n", FILE_APPEND);
        }
    }
}

// Return JSON response
$response = json_encode(["success" => $success, "message" => $success ? "Data saved successfully!" : $errorMessage]);

// Handle JSON encoding errors
if (json_last_error() != JSON_ERROR_NONE) {
    $jsonError = "JSON encoding error: " . json_last_error_msg();
    file_put_contents("error_log.txt", $jsonError . "\n", FILE_APPEND);
    echo json_encode(["success" => false, "message" => $jsonError]);
    exit;
}

echo $response;
?>
