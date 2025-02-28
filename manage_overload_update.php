<?php
// Database connection details
$host = 'localhost';
$dbname = 'gfi_exel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Validate POST data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $row_id = isset($_POST['row_id']) ? intval($_POST['row_id']) : 0;
        if ($row_id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid Row ID.']);
            exit;
        }

        // Prepare and execute update query
        $query = "
        UPDATE overload
        SET 
            mwf_days = :mwf_days,
            mwf_hrs = :mwf_hrs,
            mwf_total = :mwf_total,
            tth_days = :tth_days,
            tth_hrs = :tth_hrs,
            tth_total = :tth_total,
            ss_days = :ss_days,
            ss_hrs = :ss_hrs,
            ss_total = :ss_total,
            monday_days = :monday_days,
            monday_hrs = :monday_hrs,
            monday_total = :monday_total,
            tuesday_days = :tuesday_days,
            tuesday_hrs = :tuesday_hrs,
            tuesday_total = :tuesday_total,
            wednesday_days = :wednesday_days,
            wednesday_hrs = :wednesday_hrs,
            wednesday_total = :wednesday_total,
            thursday_days = :thursday_days,
            thursday_hrs = :thursday_hrs,
            thursday_total = :thursday_total,
            friday_days = :friday_days,
            friday_hrs = :friday_hrs,
            friday_total = :friday_total,
            saturday_days = :saturday_days,
            saturday_hrs = :saturday_hrs,
            saturday_total = :saturday_total,
            sunday_days = :sunday_days,
            sunday_hrs = :sunday_hrs,
            sunday_total = :sunday_total,
            mtth_days = :mtth_days,
            mtth_hrs = :mtth_hrs,
            mtth_total = :mtth_total,
            mtwf_days = :mtwf_days,
            mtwf_hrs = :mtwf_hrs,
            mtwf_total = :mtwf_total,
            twthf_days = :twthf_days,
            twthf_hrs = :twthf_hrs,
            twthf_total = :twthf_total,
            mw_days = :mw_days,
            mw_hrs = :mw_hrs,
            mw_total = :mw_total,
            less_lateOL = :less_lateOL,
            additional = :additional,
            adjustment_less = :adjustment_less,
            grand_total = :grand_total
        WHERE overload_id = :row_id
    ";
    
    $stmt = $pdo->prepare($query);
    
    // Execute the statement
    $stmt->execute([
        ':mwf_days' => $_POST['mwf_days'] ?? 0,
        ':mwf_hrs' => $_POST['mwf_hrs'] ?? 0,
        ':mwf_total' => $_POST['mwf_total'] ?? 0,
        ':tth_days' => $_POST['tth_days'] ?? 0,
        ':tth_hrs' => $_POST['tth_hrs'] ?? 0,
        ':tth_total' => $_POST['tth_total'] ?? 0,
        ':ss_days' => $_POST['ss_days'] ?? 0,
        ':ss_hrs' => $_POST['ss_hrs'] ?? 0,
        ':ss_total' => $_POST['ss_total'] ?? 0,
        ':monday_days' => $_POST['monday_days'] ?? 0,
        ':monday_hrs' => $_POST['monday_hrs'] ?? 0,
        ':monday_total' => $_POST['monday_total'] ?? 0,
        ':tuesday_days' => $_POST['tuesday_days'] ?? 0,
        ':tuesday_hrs' => $_POST['tuesday_hrs'] ?? 0,
        ':tuesday_total' => $_POST['tuesday_total'] ?? 0,
        ':wednesday_days' => $_POST['wednesday_days'] ?? 0,
        ':wednesday_hrs' => $_POST['wednesday_hrs'] ?? 0,
        ':wednesday_total' => $_POST['wednesday_total'] ?? 0,
        ':thursday_days' => $_POST['thursday_days'] ?? 0,
        ':thursday_hrs' => $_POST['thursday_hrs'] ?? 0,
        ':thursday_total' => $_POST['thursday_total'] ?? 0,
        ':friday_days' => $_POST['friday_days'] ?? 0,
        ':friday_hrs' => $_POST['friday_hrs'] ?? 0,
        ':friday_total' => $_POST['friday_total'] ?? 0,
        ':saturday_days' => $_POST['saturday_days'] ?? 0,
        ':saturday_hrs' => $_POST['saturday_hrs'] ?? 0,
        ':saturday_total' => $_POST['saturday_total'] ?? 0,
        ':sunday_days' => $_POST['sunday_days'] ?? 0,
        ':sunday_hrs' => $_POST['sunday_hrs'] ?? 0,
        ':sunday_total' => $_POST['sunday_total'] ?? 0,
        ':mtth_days' => $_POST['mtth_days'] ?? 0,
        ':mtth_hrs' => $_POST['mtth_hrs'] ?? 0,
        ':mtth_total' => $_POST['mtth_total'] ?? 0,
        ':mtwf_days' => $_POST['mtwf_days'] ?? 0,
        ':mtwf_hrs' => $_POST['mtwf_hrs'] ?? 0,
        ':mtwf_total' => $_POST['mtwf_total'] ?? 0,
        ':twthf_days' => $_POST['twthf_days'] ?? 0,
        ':twthf_hrs' => $_POST['twthf_hrs'] ?? 0,
        ':twthf_total' => $_POST['twthf_total'] ?? 0,
        ':mw_days' => $_POST['mw_days'] ?? 0,
        ':mw_hrs' => $_POST['mw_hrs'] ?? 0,
        ':mw_total' => $_POST['mw_total'] ?? 0,
        ':less_lateOL' => $_POST['less_lateOL'] ?? 0,
        ':additional' => $_POST['additional'] ?? 0,
        ':adjustment_less' => $_POST['adjustment_less'] ?? 0,
        ':grand_total' => $_POST['grand_total'] ?? 0,
        ':row_id' => $row_id
    ]);
    

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
