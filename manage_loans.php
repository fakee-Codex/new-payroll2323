<?php
// Database connection
require 'database_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the contributions table
$sql = "SELECT 
            n.loan_id,
            e.employee_id,
            CONCAT(e.last_name, ', ', e.first_name, ' ', e.suffix_title) AS Name,
            e.employee_type AS Type,
            e.basic_salary AS Basic,
           n.ret, n.sss, n.hdmf_pag
        FROM loans n
        JOIN employees e ON n.employee_id = e.employee_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Loans</title>

 
  
</head>

<body>

    <?php include 'aside.php'; ?> <!-- This will import the sidebar -->
    <style>

    </style>
    <main>
        <div class="container mt-5 mb-3 text-center text-white">
            <h1>Manage Loans</h1>
        </div>

        <a href="manage_loans_add.php" class="btn btn-primary mb-3">Add Loans</a>

        <!-- Table Section -->
        <div id="contributions-table" style="background-color: white;">
        <table class="table table-bordered" style="background-color: white;">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Employee Type</th>
                        <th>Basic Salary</th>
              
                        <th>RET</th>
                        <th>SSS</th>
                        <th>HDMF/PAG</th>
                        <th>Total Loan Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody style="background-color: white;">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                      

                            $ret = $row['ret'];
                            $sss = $row['sss'];
                            $hdmf_pag = $row['hdmf_pag'];
                            $total_loan = $ret + $sss + $hdmf_pag;

                            echo "<tr>
                    <td>{$row['Name']}</td>
                    <td>{$row['Type']}</td>
                    <td>" . number_format($row['Basic'], 2) . "</td>
                    <td>" . number_format($ret, 2) . "</td>
                    <td>" . number_format($sss, 2) . "</td>
                    <td>" . number_format($hdmf_pag, 2) . "</td>
                    <td>" . number_format($total_loan, 2) . "</td>
                 
                    <td>
                        <a href='manage_loans_edit.php?id={$row['loan_id']}' class='btn btn-warning btn-sm'>Edit</a>
                       
                    </td>
                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11'>No data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

      
  

        <script src="js/bootstrap.bundle.min.js"></script>
    </main>

</body>

</html>