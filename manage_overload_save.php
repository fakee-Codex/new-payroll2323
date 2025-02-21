<?php
// Database connection details
$host = 'localhost';
$dbname = 'gfi_exel';
$username = 'root';
$password = '';

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve the posted data
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || empty($data)) {
        http_response_code(400);
        echo json_encode(["error" => "No data received."]);
        exit;
    }

   // Prepare the SQL statement
$sql = "INSERT INTO overload (
    employee_id, mwf_days, mwf_hrs, mwf_total, tth_days, tth_hrs, tth_total,
    ss_days, ss_hrs, ss_total, monday_days, monday_hrs, monday_total,
    tuesday_days, tuesday_hrs, tuesday_total, wednesday_days, wednesday_hrs, wednesday_total,
    thursday_days, thursday_hrs, thursday_total, friday_days, friday_hrs, friday_total,
    saturday_days, saturday_hrs, saturday_total, sunday_days, sunday_hrs, sunday_total,
    mtth_days, mtth_hrs, mtth_total, mtwf_days, mtwf_hrs, mtwf_total,
    twthf_days, twthf_hrs, twthf_total, mw_days, mw_hrs, mw_total,
    less_lateOL, additional, adjustment_less, grand_total
) VALUES (
    :employee_id, :mwf_days, :mwf_hrs, :mwf_total, :tth_days, :tth_hrs, :tth_total,
    :ss_days, :ss_hrs, :ss_total, :monday_days, :monday_hrs, :monday_total,
    :tuesday_days, :tuesday_hrs, :tuesday_total, :wednesday_days, :wednesday_hrs, :wednesday_total,
    :thursday_days, :thursday_hrs, :thursday_total, :friday_days, :friday_hrs, :friday_total,
    :saturday_days, :saturday_hrs, :saturday_total, :sunday_days, :sunday_hrs, :sunday_total,
    :mtth_days, :mtth_hrs, :mtth_total, :mtwf_days, :mtwf_hrs, :mtwf_total,
    :twthf_days, :twthf_hrs, :twthf_total, :mw_days, :mw_hrs, :mw_total,
    :less_lateOL, :additional, :adjustment_less, :grand_total
)";

$stmt = $pdo->prepare($sql);

// Iterate through the data and insert each row
foreach ($data as $row) {
    $stmt->execute([
        ':employee_id' => $row['employee_id'],
        ':mwf_days' => $row['column_1'] ?? 0,
        ':mwf_hrs' => $row['column_2'] ?? 0,
        ':mwf_total' => $row['column_3'] ?? 0,
        ':tth_days' => $row['column_4'] ?? 0,
        ':tth_hrs' => $row['column_5'] ?? 0,
        ':tth_total' => $row['column_6'] ?? 0,
        ':ss_days' => $row['column_7'] ?? 0,
        ':ss_hrs' => $row['column_8'] ?? 0,
        ':ss_total' => $row['column_9'] ?? 0,
        ':monday_days' => $row['column_10'] ?? 0,
        ':monday_hrs' => $row['column_11'] ?? 0,
        ':monday_total' => $row['column_12'] ?? 0,
        ':tuesday_days' => $row['column_13'] ?? 0,
        ':tuesday_hrs' => $row['column_14'] ?? 0,
        ':tuesday_total' => $row['column_15'] ?? 0,
        ':wednesday_days' => $row['column_16'] ?? 0,
        ':wednesday_hrs' => $row['column_17'] ?? 0,
        ':wednesday_total' => $row['column_18'] ?? 0,
        ':thursday_days' => $row['column_19'] ?? 0,
        ':thursday_hrs' => $row['column_20'] ?? 0,
        ':thursday_total' => $row['column_21'] ?? 0,
        ':friday_days' => $row['column_22'] ?? 0,
        ':friday_hrs' => $row['column_23'] ?? 0,
        ':friday_total' => $row['column_24'] ?? 0,
        ':saturday_days' => $row['column_25'] ?? 0,
        ':saturday_hrs' => $row['column_26'] ?? 0,
        ':saturday_total' => $row['column_27'] ?? 0,
        ':sunday_days' => $row['column_28'] ?? 0,
        ':sunday_hrs' => $row['column_29'] ?? 0,
        ':sunday_total' => $row['column_30'] ?? 0,
        ':mtth_days' => $row['column_31'] ?? 0,
        ':mtth_hrs' => $row['column_32'] ?? 0,
        ':mtth_total' => $row['column_33'] ?? 0,
        ':mtwf_days' => $row['column_34'] ?? 0,
        ':mtwf_hrs' => $row['column_35'] ?? 0,
        ':mtwf_total' => $row['column_36'] ?? 0,
        ':twthf_days' => $row['column_37'] ?? 0,
        ':twthf_hrs' => $row['column_38'] ?? 0,
        ':twthf_total' => $row['column_39'] ?? 0,
        ':mw_days' => $row['column_40'] ?? 0,
        ':mw_hrs' => $row['column_41'] ?? 0,
        ':mw_total' => $row['column_42'] ?? 0,
        ':less_lateOL' => $row['column_43'] ?? 0,
        ':additional' => $row['column_44'] ?? 0,
        ':adjustment_less' => $row['column_45'] ?? 0,
        ':grand_total' => $row['column_46'] ?? 0
    ]);
}

    // Return success response
    echo json_encode(["success" => true, "message" => "Data saved successfully."]);
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
