<?php

require '../database_connection.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $employee_id = $_GET['id'];

    $sql = "SELECT 
        ct.computation_id,
        e.employee_id,
        CONCAT(e.last_name, ', ', e.first_name, ' ', e.suffix_title) AS full_name,
        COALESCE(e.basic_salary, 0) AS basic_salary,
        COALESCE(e.honorarium, 0) AS honorarium,
        COALESCE(e.watch_reward, 0) AS watch_reward,
        COALESCE(e.overload_rate, 0) AS overload_rate,

        COALESCE(c.sss_ee, 0) AS sss_ee, 
        COALESCE(c.sss_er, 0) AS sss_er,
        COALESCE(c.philhealth_ee, 0) AS philhealth_ee, 
        COALESCE(c.philhealth_er, 0) AS philhealth_er,
        COALESCE(c.pag_ibig_ee, 0) AS pag_ibig_ee, 
        COALESCE(c.pag_ibig_er, 0) AS pag_ibig_er,
        COALESCE(c.medical_savings, 0) AS medical_savings, 
        COALESCE(c.mp2, 0) AS mp2,

        COALESCE(n.hdmf_pag, 0) AS hdmf_pag,
        COALESCE(n.sss, 0) AS sss,
        COALESCE(n.ret, 0) AS ret,

        COALESCE(ct.absent_late_hr, 0) AS absent_late_hr,
        COALESCE(e.absent_lateRate, 0) AS absent_lateRate,
        COALESCE(ct.absent_late_total, 0) AS absent_late_total,

        COALESCE(ct.canteen, 0) AS canteen,
        COALESCE(ct.others, 0) AS others,
        COALESCE(ct.wr_hr, 0) AS wr_hr,
        COALESCE(ct.wr_rate, 0) AS wr_rate,
        COALESCE(ct.adjust_hr, 0) AS adjust_hr,
        COALESCE(ct.adjust_rate, 0) AS adjust_rate,

        COALESCE(SUM(o.grand_total), 0) AS overload_hr
       
    FROM employees e
    JOIN computation ct ON ct.employee_id = e.employee_id
    JOIN contributions c ON c.employee_id = e.employee_id
    JOIN loans n ON n.employee_id = e.employee_id
    JOIN overload o ON o.employee_id = e.employee_id
    WHERE e.employee_id = ?"; // Use placeholder "?"


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id); // Bind $employee_id as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Calculation
        $gross_pay = $row['basic_salary'] + $row['honorarium'];

        $sss_total = $row['sss_ee'] + $row['sss_er'];
        $philhealth_total = $row['philhealth_ee'] + $row['philhealth_er'];
        $pagibig_total = $row['pag_ibig_ee'] + $row['pag_ibig_er'];

        $totalContributions = $sss_total + $philhealth_total + $pagibig_total + $row['medical_savings'] + $row['mp2'];
        $totalLoans = $row['hdmf_pag'] + $row['sss'] + $row['ret'];
        $absent_hr_rate_total = $row['absent_late_hr'] * $row['absent_lateRate'];
        $canteen_others = $row['canteen'] + $row['others'];

        $overload = $row['overload_hr'] * $row['overload_rate'];
        $club = $row['wr_hr'] * $row['wr_rate'];
        $adjustment = $row['adjust_hr'] * $row['adjust_rate'];

        $total_deduction = $totalContributions + $totalLoans + $absent_hr_rate_total + $canteen_others;
        $net_pay = $gross_pay - $total_deduction;
        $total = $overload + $club + $adjustment + $row['watch_reward'];

        echo "<style>
        .payslip-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
            background: #fff;
        }
        .fw-bold {
            font-weight: bold;
        }
        .text-primary { color: #007bff; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }
        .text-secondary { color:rgb(222, 133, 44); }
    </style>";

        echo "<div class='container'>";
        echo "<div class='row'>";

        // Column 1 - Employee Info & Deductions
        echo "<div class='col-md-6'>";
        echo "<div class='payslip-container'>";

        // Employee Info
        echo "<div class='employee-info mt-2'>";
        echo "<h6 class='fw-bold'>Name: {$row['full_name']}</h6>";

        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>Basic Salary:</span> 
        <span class='text-end'>₱" . number_format($row['basic_salary'], 2) . "</span>
      </p>";

        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>F&S Development:</span> 
        <span class='text-end'>₱" . number_format($row['honorarium'], 2) . "</span>
      </p>";

        echo "<h5 class='text-warning mt-2 d-flex justify-content-between'>
        <span>Gross Pay:</span> 
        <span class='text-end'>₱" . number_format($gross_pay, 2) . "</span>
      </h5>";

        echo "</div>";


        // Deductions
        echo "<div class='deductions mb-2'>";
        echo "<h6 class='fw-bold'>DEDUCTIONS</h6>";

        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>SSS Cont'n:</span> 
        <span class='text-end'>₱" . number_format($sss_total, 2) . "</span>
      </p>";

        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>HDMF (Manda) Cont'n:</span> 
        <span class='text-end'>₱" . number_format($pagibig_total, 2) . "</span>
      </p>";

        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>PHIC Cont'n:</span> 
        <span class='text-end'>₱" . number_format($philhealth_total, 2) . "</span>
      </p>";

        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>MP2 Cont'n:</span> 
        <span class='text-end'>₱" . number_format($row['mp2'], 2) . "</span>
      </p>";

        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>Medical Savings:</span> 
        <span class='text-end'>₱" . number_format($row['medical_savings'], 2) . "</span>
      </p>";

        echo "</div>";


        // Loans
        echo "<div class='loans mt-2'>";
        echo "<h6 class='fw-bold'>LOANS</h6>";

        echo "<p class='mb-0 d-flex justify-content-between'>
                <span>HDMF Loan:</span> 
                <span class='text-end'>₱" . number_format($row['hdmf_pag'], 2) . "</span>
              </p>";

        echo "<p class='mb-0 d-flex justify-content-between'>
                <span>SSS Loan:</span> 
                <span class='text-end'>₱" . number_format($row['sss'], 2) . "</span>
              </p>";

        echo "<p class='mb-0 d-flex justify-content-between'>
                <span>RET Loan:</span> 
                <span class='text-end'>₱" . number_format($row['ret'], 2) . "</span>
              </p>";

        echo "</div>";


        // Absences/Late/Canteen/Others
        echo "<div class='other-deductions mt-2'>";
        echo "<h6 class='fw-bold'>ABSENT/LATE etc.</h6>";

        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>Absences/Late:</span> 
        <span class='text-end'>₱" . number_format($absent_hr_rate_total, 2) . "</span>
      </p>";

        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>Canteen:</span> 
        <span class='text-end'>₱" . number_format($row['canteen'], 2) . "</span>
      </p>";

        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>Others:</span> 
        <span class='text-end'>₱" . number_format($row['others'], 2) . "</span>
      </p>";

        echo "</div>";


        // Total Deduction & Net Pay
        echo "<div class='net-pay'>";
        echo "<p class='fw-bold text-danger'>Total Deduction: ₱" . number_format($total_deduction, 2) . "</p>";
        echo "<h5 class='fw-bold text-success'>Net: ₱" . number_format($net_pay, 2) . "</h5>";
        echo "</div>";

        echo "</div>"; // End of first column container
        echo "</div>"; // End of first column

        // Column 2 - Additional Pay & Net Pay
        echo "<div class='col-md-6'>";
        echo "<div class='payslip-container'>";

        // OVERLOAD & WATCH
        echo "<div class='additional-pay'>";
        echo "<h6 class='fw-bold '>OVERLOAD & WATCH</h6>";
        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>Extra Load: " . number_format($row['overload_hr'], 2) . " * " . number_format($row['overload_rate'], 2) . "  </span><span class='fw-bold'>₱" . number_format($overload, 2) . "</span></p>";

        echo "<h6 class='fw-bold mt-2'>Faculty and Staff Development</h6>";
        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>Watch:</span> 
        <span class='text-end fw-bold'>₱" . number_format($row['watch_reward'], 2) . "</span></p>";

        echo "<h6 class='fw-bold mt-2'>ADDITIONAL PAY</h6>";
        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>Club: " . number_format($row['wr_hr'], 2) . " * " . number_format($row['wr_rate'], 2) . "  </span><span class='fw-bold'>₱" . number_format($club, 2) . "</span></p>";


        echo "<p class='mb-0 d-flex justify-content-between'>
        <span>Adjustment: " . number_format($row['adjust_hr'], 2) . " * " . number_format($row['adjust_rate'], 2) . "  </span><span class='fw-bold'>₱" . number_format($adjustment, 2) . "</span></p>";

        echo "<h5 class='fw-bold mt-2 text-success'>Total: ₱" . number_format($total, 2) . "</h5>";


        echo "</div>";



        echo "</div>"; // End of second column container
        echo "</div>"; // End of second column

        echo "</div>"; // End of row
        echo "</div>"; // End of container

    } else {
        echo "<p>No payslip found for this employee.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Invalid request.</p>";
}

$conn->close();
