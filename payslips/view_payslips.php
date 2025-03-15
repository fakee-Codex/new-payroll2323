<?php

$conn = new mysqli("localhost", "root", "", "gfi_exel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_employees'])) {
    echo '<!DOCTYPE html>
          <html lang="en">
          <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Payslips</title>
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
              <style>
                   .payslip-container {
                      display: flex;
                      flex-wrap: wrap;
                      justify-content: center;
                  }
                  .payslip {
                      width: 25%;
                      max-width: 350px;
                      border: 2px solid #333;
                      padding: 8px;
                      margin: 3px;
                      border-radius: 8px;
                      background: #f9f9f9;
                      box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
                      font-size: 9px;
                  }
                  .header {
                      text-align: center;
                      margin-bottom: 10px;
                  }
                  .table {
                      width: 100%;
                  }
                  .table td, .table th {
                      border: 1px solid #ddd;
                      padding: 4px;
                  }
                  .table th {
                      background-color: #f2f2f2;
                      text-align: left;
                  }
                  .text-danger {
                      color: red;
                  }
                  .signature {
                      margin-top: 10px;
                      display: flex;
                      justify-content: space-between;
                      font-size: 10px;
                  }
                  .inline {
                      display: flex;
                      justify-content: space-between;
               }
         
              </style>
          </head>
          <body>
          <div class="container payslip-container mt-4">';

    foreach ($_POST['selected_employees'] as $employee_id) {
        $query = "SELECT f.employee_id, 
                         CONCAT_WS(' ', e.last_name, e.first_name, e.suffix_title) AS name,
                         e.basic_salary, e.honorarium, 
                         f.retirement, f.medical_savings, c.pagibig, c.mp2, 
                         f.sss_ee, f.sss_er, f.philhealth_ee, f.philhealth_er, 
                         f.pag_ibig_ee, f.pag_ibig_er, c.absent_late_total 
                  FROM employees e
                  JOIN computation c ON e.employee_id = c.employee_id
                  JOIN contributions f ON e.employee_id = f.employee_id
                  WHERE e.employee_id = '$employee_id'";

        $result = $conn->query($query);

        if ($row = $result->fetch_assoc()) {
            $gross_pay = $row['basic_salary'] + $row['honorarium'];
            $sss_total = $row['sss_ee'] + $row['sss_er'];
            $philhealth_total = $row['philhealth_ee'] + $row['philhealth_er'];
            $pagibig_total = $row['pag_ibig_ee'] + $row['pag_ibig_er'];

            $total_deduction = $row['retirement'] + $row['medical_savings'] + $row['pagibig'] +
                $row['mp2'] + $sss_total + $philhealth_total + $pagibig_total +
                $row['absent_late_total'];

            $net_pay = $gross_pay - $total_deduction;

            echo "<div class='payslip'>
                    <div class='header'>
                        <h5>GENSANTOS FOUNDATION COLLEGE, INC.</h5>
                        <h6>PAYSLIP</h6>
                    </div>
                    <table class='table table-bordered'>
            <div class='inline'><strong>Name:</strong> {$row['name']} <strong>Ctrl no:</strong> <span class='text-danger'>309</span></div>
                    <div class='inline'><strong>Period:</strong> SEPTEMBER 1-15, 2024 <strong>Basic Salary:</strong> P {$row['basic_salary']}</div>
                    <table class='table table-bordered'>
                <tr><td><strong>Faculty and Staff Development:</strong></td><td>P " . number_format($row[''], 2) . "</td></tr>
                <tr><td><strong>GROSS:</strong></td><td><strong>P " . number_format() . "</strong></td></tr>
                <tr><td colspan='2' class='fw-bold'>DEDUCTIONS</td></tr>
                <tr><td>Ret. Plan:</td><td>P " . number_format($row[''], 2) . "</td></tr>
                <tr><td>Medical Savings:</td><td>P " . number_format($row[''], 2) . "</td></tr>
                <tr><td>PAG-IBIG Loan:</td><td>P " . number_format($row[''], 2) . "</td></tr>
                <tr><td>MP2:</td><td>P " . number_format($row[''], 2) . "</td></tr>
                <tr><td>SSS Contri'n:</td><td>P " . number_format() . "</td></tr>
                <tr><td>Phil Health:</td><td>P " . number_format() . "</td></tr>
                <tr><td>PAG-IBIG:</td><td>P " . number_format() . "</td></tr>
                <tr><td>Absent/Late:</td><td>P " . number_format($row[''], 2) . "</td></tr>
                <tr><td><strong>Total Deduction:</strong></td><td><strong>P " . number_format() . "</strong></td></tr>
                <tr><td><strong>NET PAY:</strong></td><td><strong>P " . number_format() . "</strong></td></tr>
                    </table>
                    <div class='signature'>
                        <span>Prepared by: _______________</span>
                        <span>Approved by: _______________</span>
                        <span>Noted by: _______________</span>
                    </div>
                  </div>";
        }
    }

    echo '</div></body></html>';
}
