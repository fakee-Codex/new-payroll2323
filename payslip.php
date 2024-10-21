<?php

// Fetch the data passed via POST
$employeeName = $_POST['employeeName'];
$regularHours = $_POST['regularHours'];
$overtimeHours = $_POST['overtimeHours'];
$dailyRate = $_POST['dailyRate'];
$totalPay = $_POST['totalPay'];
$loanDeduction = $_POST['loanDeduction'];
$medicalSavings = $_POST['medicalSavings'];
$canteen = $_POST['canteen'];
$absenceLate = $_POST['absenceLate'];
$otherDeduction = $_POST['otherDeduction'];
$overloadPay = $_POST['overloadPay'];
$watchPay = $_POST['watchPay'];
$fs15thPay = $_POST['fs15thPay'];
$netPay = $_POST['netPay'];
$contributionsList = $_POST['contributionsList'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// Convert all numeric values to floats to avoid number_format errors
$totalPay = floatval(str_replace(',', '', $totalPay));  // Removing commas before conversion
$loanDeduction = floatval($loanDeduction);
$medicalSavings = floatval($medicalSavings);
$canteen = floatval($canteen);
$absenceLate = floatval($absenceLate);
$otherDeduction = floatval($otherDeduction);
$overloadPay = floatval($overloadPay);
$watchPay = floatval($watchPay);
$fs15thPay = floatval($fs15thPay);
$netPay = floatval(str_replace(',', '', $netPay)); // Removing commas before conversion
$dailyRate = floatval($dailyRate);

// Generate a control number based on employee name and other data
$controlData = $employeeName . $startDate . $endDate;
$controlNumber = substr(abs(crc32($controlData)), 0, 7);  // Generate an 8-digit control number from the hash
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
            width: 50%;
        }
        .payslip-header {
            text-align: center;
        }
        .payslip-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .payslip-table th, .payslip-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .payslip-table th {
            background-color: #f2f2f2;
        }
        .right-align {
            text-align: right;
        }
        .center-align {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="payslip-header">
        <h2>GENSANTOS FOUNDATION COLLEGE, INC.</h2>
        <h3>PAY SLIP</h3>
    </div>

    <table class="payslip-table">
        <tr>
            <td>Name: <strong><?php echo htmlspecialchars($employeeName); ?></strong></td>
            
            <!-- Use the generated control number -->
            <td class="right-align">Ctrl no: <strong><?php echo htmlspecialchars($controlNumber); ?></strong></td>
        </tr>
        <tr>
            <td>Period: <?php echo date('F j, Y', strtotime($startDate)) . " - " . date('F j, Y', strtotime($endDate)); ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <th colspan="2" class="center-align">Basic Salary and Gross Pay</th>
        </tr>
        <tr>
            <td>Hours Rendered</td>
            <td class="right-align"><?php echo htmlspecialchars($regularHours); ?></td>
        </tr>
        <tr>
            <td>Overtime Hours</td>
            <td class="right-align"><?php echo htmlspecialchars($overtimeHours); ?></td>
        </tr>
        <tr>
            <td>Daily Rate</td>
            <td class="right-align">₱ <?php echo number_format($dailyRate, 2); ?></td>
        </tr>
        <tr>
            <td>Overload Pay</td>
            <td class="right-align">₱ <?php echo number_format($overloadPay, 2); ?></td>
        </tr>
        <tr>
            <td>Watch Pay</td>
            <td class="right-align">₱ <?php echo number_format($watchPay, 2); ?></td>
        </tr>
        <tr>
            <td>F & S 15th Pay</td>
            <td class="right-align">₱ <?php echo number_format($fs15thPay, 2); ?></td>
        </tr>
        <tr>
            <th>GROSS</th>
            <th class="right-align">₱ <?php echo number_format($totalPay, 2); ?></th>
        </tr>

        <tr>
            <th colspan="2" class="center-align">Deductions</th>
        </tr>
        <tr>
            <td>Loan Deduction</td>
            <td class="right-align">₱ <?php echo number_format($loanDeduction, 2); ?></td>
        </tr>
        <tr>
            <td>Medical Savings</td>
            <td class="right-align">₱ <?php echo number_format($medicalSavings, 2); ?></td>
        </tr>
        <tr>
            <td>Canteen</td>
            <td class="right-align">₱ <?php echo number_format($canteen, 2); ?></td>
        </tr>
        <tr>
            <td>Absence/Late</td>
            <td class="right-align">₱ <?php echo number_format($absenceLate, 2); ?></td>
        </tr>
        <tr>
            <td>Other Deductions</td>
            <td class="right-align">₱ <?php echo number_format($otherDeduction, 2); ?></td>
        </tr>
        <tr>
            <td>Contributions</td>
            <td class="right-align"><?php echo $contributionsList; ?></td> <!-- No need to escape HTML here -->
        </tr>

        <tr>
            <th>NET</th>
            <th class="right-align">₱ <?php echo number_format($netPay, 2); ?></th>
        </tr>
    </table>

    <table class="payslip-table" style="margin-top: 40px;">
        <tr>
            <td>Prepared by:</td>
            <td>Approved by:</td>
            <td>Noted by:</td>
        </tr>
        <tr style="height: 50px;">
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

</body>
</html>

 
