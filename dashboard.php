<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'gfi_exel');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    SELECT 
        e.employee_id,
        CONCAT(e.first_name, ' ', e.last_name, ' ', e.suffix_title) AS full_name, 
        e.classification,
        e.overload_rate,
        COALESCE(SUM(o.grand_total), 0) AS overload_hr,
        e.basic_salary, 
        e.honorarium, 
        e.watch_reward,
        COALESCE(SUM(ct.adjust_total), 0) AS adjust_hr,
        CT.overload_total,
    CT.GROSS_PAY,
        
        
        COALESCE(SUM(ct.adjust_total), 0) AS adjust_total,
        COALESCE(SUM(ct.watch_total), 0) AS watch_total,
        COALESCE(SUM(ct.gross_pay), 0) AS gross_pay,
        
        COALESCE(SUM(ct.pagibig), 0) AS pagibig,
        COALESCE(SUM(ct.mp2), 0) AS mp2,
        COALESCE(SUM(ct.sss), 0) AS sss,
        COALESCE(SUM(ct.ret), 0) AS ret,
        
        COALESCE(SUM(ct.canteen), 0) AS canteen,
        COALESCE(SUM(ct.others), 0) AS others,
        COALESCE(SUM(ct.absent_late_total), 0) AS absent_late_total,
        COALESCE(SUM(ct.total_deduction), 0) AS total_deduction,
        COALESCE(SUM(ct.net_pay), 0) AS net_pay,
        
        COALESCE(c.medical_savings, 0) AS medical_savings, 
        COALESCE((c.sss_ee + c.sss_er), 0) AS sss_total, 
        COALESCE((c.pag_ibig_ee + c.pag_ibig_er), 0) AS pag_ibig_total, 
        COALESCE((c.philhealth_ee + c.philhealth_er), 0) AS philhealth_total
    
    FROM employees e
    LEFT JOIN overload o ON e.employee_id = o.employee_id
    LEFT JOIN contributions c ON e.employee_id = c.employee_id
    LEFT JOIN computation ct ON e.employee_id = ct.employee_id

    GROUP BY e.employee_id, e.first_name, e.last_name, e.suffix_title, 
             e.classification, e.overload_rate, e.basic_salary, e.honorarium, 
             e.watch_reward, c.medical_savings, c.sss_ee, c.sss_er, c.pag_ibig_ee, 
             c.pag_ibig_er, c.philhealth_ee, c.philhealth_er
";

$result = $conn->query($sql);


// Fetch total employees, active employees, and total payroll
$totalEmployeesQuery = "SELECT COUNT(*) AS total_employees FROM employees";
$activeEmployeesQuery = "SELECT COUNT(*) AS active_employees FROM employees";  // No status check
$totalPayrollQuery = "SELECT SUM(basic_salary) AS total_payroll FROM employees";

// Fetch the results for total employees, active employees, and total payroll
$totalEmployeesResult = $conn->query($totalEmployeesQuery);
$activeEmployeesResult = $conn->query($activeEmployeesQuery);
$totalPayrollResult = $conn->query($totalPayrollQuery);

// Fetch the results
$totalEmployees = $totalEmployeesResult->fetch_assoc()['total_employees'];
$activeEmployees = $activeEmployeesResult->fetch_assoc()['active_employees'];
$totalPayroll = $totalPayrollResult->fetch_assoc()['total_payroll'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <?php include 'aside.php'; ?> <!-- Sidebar -->

    <main class="flex-grow p-6">
        <!-- Dashboard Title -->
        <h1 class="text-4xl font-bold text-white text-center mb-6 p-6 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 rounded-lg shadow-lg">
            Dashboard
        </h1>

        <!-- Dashboard Summary Cards -->
       <div class="row g-4 mb-4">
    <!-- Total Employees Card -->
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-lg border-0 rounded-3 hover-shadow transform-scale">
            <div class="card-body text-center">
                <h2 class="fs-4 fw-semibold">Total Employees</h2>
                <p class="fs-3 fw-bold"><?php echo $totalEmployees; ?></p>
            </div>
        </div>
    </div>

    <!-- Active Employees Card -->
    <div class="col-md-4">
        <div class="card bg-success text-white shadow-lg border-0 rounded-3 hover-shadow transform-scale">
            <div class="card-body text-center">
                <h2 class="fs-4 fw-semibold">Active Employees</h2>
                <p class="fs-3 fw-bold"><?php echo $activeEmployees; ?></p>
            </div>
        </div>
    </div>

    <!-- Total Payroll Card -->
    <div class="col-md-4">
        <div class="card bg-warning text-white shadow-lg border-0 rounded-3 hover-shadow transform-scale">
            <div class="card-body text-center">
                <h2 class="fs-4 fw-semibold">Total Payroll</h2>
                <p class="fs-3 fw-bold">₱<?php echo number_format($totalPayroll, 2); ?></p>
            </div>
        </div>
    </div>
</div>


        <!-- Employee List Section -->
        <div class="bg-gray-100 p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">PAYROLL SUMMARY</h2>
            <div class="overflow-x-auto">
                <table class="table table-bordered w-full">
                    <thead class="bg-gray-200">


                        <tr>
                            <th rowspan="2">NAME</th>
                            <th rowspan="2">CLASSIFICATION</th>
                            <th colspan="1" class="p-2 bg-rose-400">RATE</th>
                            <th colspan="2"></th>
                            <th colspan="2">OTHER BENEFIT</th>
                            <th colspan="2">ADD'L PAY</th>
                            <th rowspan="2">GROSS SALARY</th>
                            <th colspan="4">LOANS</th>
                            <th colspan="3">CONTRIBUTIONS</th>
                            <th rowspan="2">MEDICAL SAVINGS</th>
                            <th rowspan="2">CANTEEN</th>
                            <th rowspan="2">OTHER DEDUCTIONS</th>
                            <th rowspan="2">ABSENT / CSL / LATE</th>
                            <th rowspan="2">TOTAL DEDUCTIONS</th>
                            <th rowspan="2">NET PAY</th>
                        </tr>
                        <tr>
                            <th>OL/OT</th>
                            <th>OL</th>
                            <th>BASIC</th>
                            <th>F&S15TH</th>
                            <th>WATCH</th>
                            <th>OT</th>
                            <th>OVERLOAD</th>
                            <th>HDMF</th>
                            <th>MP2</th>
                            <th>SSS</th>
                            <th>RETMNT</th>
                            <th>SSS</th>
                            <th>PAG-IBIG</th>
                            <th>PHIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    
             if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['full_name']}</td>
            <td>{$row['classification']}</td>
            <td>{$row['overload_rate']}</td>
            <td>{$row['overload_hr']}</td>
            <td>{$row['basic_salary']}</td>
            <td>{$row['honorarium']}</td>
            <td>{$row['watch_reward']}</td>
        <td>{$row['adjust_total']}</td>

            <td>{$row['overload_total']}</td>
           
            <td>{$row['gross_pay']}</td>
          
            <td>{$row['pagibig']}</td>
            <td>{$row['mp2']}</td>
            <td>{$row['sss']}</td>
            <td>{$row['ret']}</td>
            <td>{$row['sss_total']}</td>
            <td>{$row['pag_ibig_total']}</td>
            <td>{$row['philhealth_total']}</td>
            <td>{$row['medical_savings']}</td>
            <td>{$row['canteen']}</td>
            <td>{$row['others']}</td>
            <td>{$row['absent_late_total']}</td>
            <td>{$row['total_deduction']}</td>
            <td>{$row['net_pay']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='19' class='text-center text-gray-500'>No employees found.</td></tr>";
}

                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>

</html>