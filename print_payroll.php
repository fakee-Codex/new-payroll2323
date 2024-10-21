<?php
session_start();
require 'database_connection.php'; // Include the database connection

if (isset($_GET['start_date'], $_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];

    // Fetch payroll data within the date range
    $query = "
        SELECT e.employee_id, CONCAT(e.first_name, ' ', e.last_name) AS name, 
               SUM(a.regular_hours) AS total_regular_hours, SUM(a.overtime_hours) AS total_overtime_hours, 
               dr.daily_rate, 
               (SELECT SUM(amount) FROM overload_pay WHERE employee_id = e.employee_id AND start_date <= '$end_date' AND end_date >= '$start_date') AS total_overload_pay,
               (SELECT SUM(amount) FROM watch_pay WHERE employee_id = e.employee_id AND date_added BETWEEN '$start_date' AND '$end_date') AS total_watch_pay,
               (SELECT SUM(amount) FROM fs_15th_pay WHERE employee_id = e.employee_id AND date BETWEEN '$start_date' AND '$end_date') AS total_fs_15th_pay
        FROM employees e 
        LEFT JOIN attendance a ON e.employee_id = a.employee_id 
        LEFT JOIN daily_rate dr ON e.employee_id = dr.employee_id AND dr.end_date IS NULL
        WHERE a.date BETWEEN '$start_date' AND '$end_date'
        GROUP BY e.employee_id
    ";

    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<!DOCTYPE html><html><head><title>Print Payroll</title>";
        echo '<style>
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 10px; text-align: left; }
                .header { font-size: 1.5em; font-weight: bold; }
                .bold { font-weight: bold; }
                .right-align { text-align: right; }
                .border { border-top: 1px solid #000; padding-top: 5px; }
                tfoot { font-weight: bold; }
              </style>';
        echo "</head><body>";

        echo "<h1>Payroll Report</h1>";
        echo "<p><strong>Payroll Period:</strong> " . date('F j, Y', strtotime($start_date)) . " - " . date('F j, Y', strtotime($end_date)) . "</p>";

        echo "<table border='1'>";
        echo "<thead><tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Total Regular Hours</th>
                <th>Total Overtime Hours</th>
                <th>Daily Rate</th>
                <th>Total Overload Pay</th>
                <th>Total Watch Pay</th>
                <th>F&S 15th Pay</th>
                <th>Gross Pay</th>
                <th>Loan Deduction</th>
                <th>Medical Savings</th>
                <th>Canteen Deduction</th>
                <th>SSS Contribution</th>
                <th>PAG-IBIG Contribution</th>
                <th>PHIC Contribution</th>
                <th>Total Contributions</th>
                <th>Other Deductions</th>
                <th>Total Deductions</th>
                <th>Net Pay</th>
              </tr></thead>";
        echo "<tbody>";

        $total_net_pay = 0; // Variable to store total net pay

        while ($row = mysqli_fetch_assoc($result)) {
            // Get contributions for the employee
            $contributions_query = "
                SELECT 
                    SUM(CASE WHEN contribution_name = 'SSS' THEN amount ELSE 0 END) AS sss_contribution,
                    SUM(CASE WHEN contribution_name = 'PAG-IBIG' THEN amount ELSE 0 END) AS pagibig_contribution,
                    SUM(CASE WHEN contribution_name = 'PHIC' THEN amount ELSE 0 END) AS phic_contribution
                FROM contributions
            ";
            $contributions_result = mysqli_query($conn, $contributions_query);
            $contributions = mysqli_fetch_assoc($contributions_result);

            // Calculate total contributions
            $total_contributions = $contributions['sss_contribution'] + $contributions['pagibig_contribution'] + $contributions['phic_contribution'];

            // Fetch employee's loan
            $loan_query = "SELECT loan_amount, loan_terms, remaining_balance FROM loans WHERE employee_id = {$row['employee_id']} AND remaining_balance > 0 LIMIT 1";
            $loan_result = mysqli_query($conn, $loan_query);
            $loan = mysqli_fetch_assoc($loan_result);
            $loan_deduction = 0;
            if ($loan) {
                $loan_deduction = $loan['loan_amount'] / $loan['loan_terms'];
            }

            // Fetch other deductions, including medical savings, canteen, and absence/late
            $deductions_query = "SELECT * FROM other_deductions WHERE employee_id = {$row['employee_id']} AND status != 'paid'";
            $deductions_result = mysqli_query($conn, $deductions_query);
            $medical_savings = 0;
            $canteen = 0;
            $absence_late = 0;
            while ($deduction_row = mysqli_fetch_assoc($deductions_result)) {
                $medical_savings += (float)$deduction_row['medical_savings'];
                $canteen += (float)$deduction_row['canteen'];
                $absence_late += (float)$deduction_row['absence_late'];
            }
            $other_deductions = $medical_savings + $canteen + $absence_late;

            // Calculate total pay and gross pay
            $total_regular_pay = $row['total_regular_hours'] * ($row['daily_rate'] / 8);
            $total_overtime_pay = $row['total_overtime_hours'] * ($row['daily_rate'] / 8 * 1.5);
            $gross_pay = $total_regular_pay + $total_overtime_pay + $row['total_overload_pay'] + $row['total_watch_pay'] + $row['total_fs_15th_pay'];

            // Calculate net pay
            $total_deductions = $loan_deduction + $total_contributions + $other_deductions;
            $net_pay = $gross_pay - $total_deductions;

            // Add to total net pay
            $total_net_pay += $net_pay;

            // Output payroll data for each employee
            echo "<tr>";
            echo "<td>{$row['employee_id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td class='right-align'>" . number_format($row['total_regular_hours'], 2) . "</td>";
            echo "<td class='right-align'>" . number_format($row['total_overtime_hours'], 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($row['daily_rate'], 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($row['total_overload_pay'], 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($row['total_watch_pay'], 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($row['total_fs_15th_pay'], 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($gross_pay, 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($loan_deduction, 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($medical_savings, 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($canteen, 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($contributions['sss_contribution'], 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($contributions['pagibig_contribution'], 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($contributions['phic_contribution'], 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($total_contributions, 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($other_deductions, 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($total_deductions, 2) . "</td>";
            echo "<td class='right-align'>₱" . number_format($net_pay, 2) . "</td>";
            echo "</tr>";
        }

        // Add the total net pay at the bottom
        echo "<tfoot><tr>";
        echo "<td colspan='18' class='right-align bold'>Total Net Pay:</td>";
        echo "<td class='right-align bold border'>₱" . number_format($total_net_pay, 2) . "</td>";
        echo "</tr></tfoot>";

        echo "</tbody></table>";

        echo "</body></html>";
    } else {
        echo "No payroll data found for the specified date range.";
    }
} else {
    echo "Please provide a valid start and end date.";
}
?>
