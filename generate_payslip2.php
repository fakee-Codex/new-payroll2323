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
            $query = "SELECT e.employee_id, 
                             CONCAT_WS(' ', e.last_name, e.first_name, e.suffix_title) AS name,
                              e.incentives, e.watch_reward
                      FROM employees e
                      WHERE e.employee_id = '$employee_id'";
        
            $result = $conn->query($query);
        
            if ($row = $result->fetch_assoc()) {
                $facultystaff = $row['watch_reward'] + $row['incentives'];
          
                $total = $facultystaff;
        
                echo "<div class='payslip'>
                        <div class='header'>
                            <h5>GENSANTOS FOUNDATION COLLEGE, INC.</h5>
                            <h6>PAYSLIP</h6>
                        </div>
                        <div class='inline'>
                            <strong>Name:</strong> {$row['name']} 
                            <strong>Ctrl no:</strong> <span class='text-danger'>309</span>
                        </div>
                        <div class='inline'>
                            <strong>Period:</strong> SEPTEMBER 1-15, 2024
                        </div>
                        <table class='table table-bordered'>
                            <tr><td><strong>Extra Load:</strong></td><td>
                            <tr><td colspan='2' class='fw-bold'>Faculty and Staff Development</td></tr>
                            <tr><td>WATCH:</td><td>P " . number_format($row['watch_reward'], 2) . "</td></tr>
                            <tr><td>SPECIAL TASK:</td><td>P " . number_format($row['incentives'], 2) . "</td></tr>
                            <tr><td colspan='2' class='fw-bold'>Additional Pay</td></tr>
                            <tr><td>ModRev:</td><td>s
                            <tr><td>Adjustment:</td><td>
                            <tr><td><strong>Total:</strong></td><td><strong>P " . number_format($total, 2) . "</strong></td></tr>
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
?>
