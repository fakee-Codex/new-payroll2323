<?php
session_start();

// Database connection
require 'database_connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Initialize variables for the payroll period label
$payroll_cutoff_label = '';

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Automatically set the start_date and end_date if not provided
if (empty($start_date) || empty($end_date)) {
    $current_date = date('Y-m-d');
    $day_of_month = date('j', strtotime($current_date)); // Day of the month

    // Determine the payroll cutoff period
    if ($day_of_month <= 15) {
        $start_date = date('Y-m-01'); // First day of the current month
        $end_date = date('Y-m-15'); // 15th day of the current month
    } else {
        $start_date = date('Y-m-16'); // 16th day of the current month
        $end_date = date('Y-m-t'); // Last day of the current month
    }

    // Redirect to update the URL parameters with start_date and end_date
    header("Location: ?start_date=$start_date&end_date=$end_date");
    exit();
}

// Use the user-provided date range or the default payroll cutoff label
$payroll_cutoff_label = "Payroll Cutoff: " . date("F j, Y", strtotime($start_date)) . " - " . date("F j, Y", strtotime($end_date));

// Query to fetch payroll data based on the date range
$query = "
    SELECT e.employee_id, CONCAT(e.first_name, ' ', e.last_name) AS name, 
           SUM(a.regular_hours) AS total_regular_hours, SUM(a.overtime_hours) AS total_overtime_hours, 
           dr.daily_rate 
    FROM employees e 
    LEFT JOIN attendance a ON e.employee_id = a.employee_id 
    LEFT JOIN daily_rate dr ON e.employee_id = dr.employee_id AND dr.end_date IS NULL
    WHERE a.date BETWEEN '$start_date' AND '$end_date'
    GROUP BY e.employee_id
";

$result = mysqli_query($conn, $query);

// Fetch global contributions (applies to all employees)
$contributions = [];
$contributions_query = "SELECT contribution_name, amount FROM contributions";
$contributions_result = mysqli_query($conn, $contributions_query);
while ($row = mysqli_fetch_assoc($contributions_result)) {
    $contributions[] = $row;
}

// JSON encode contributions safely
$contributions_json = json_encode($contributions, JSON_HEX_APOS | JSON_HEX_QUOT);

?>


<?php
include 'header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Payroll Calculation</h1>

    <!-- Display the payroll cutoff label -->
    <div class="alert alert-info">
        <?php echo $payroll_cutoff_label; ?>
    </div>
    <!-- Date Range Form -->
    <form method="GET" action="">
        <div class="form-group row">
            <label for="start_date" class="col-sm-1 col-form-label">Start Date</label>
            <div class="col-sm-3">
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" required>
            </div>
            <label for="end_date" class="col-sm-1 col-form-label">End Date</label>
            <div class="col-sm-3">
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" required>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
            <div class="col-sm-2">
                <a href="print_payroll.php?start_date=<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>&end_date=<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" class="btn btn-info" target="_blank" id="print-payroll">Print Payroll</a>
            </div>
        </div>
    </form>
        


    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Total Regular Hours</th>
                            <th>Total Overtime Hours</th>
                            <th>Daily Rate</th>
                            <th>Total Pay</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Fetch employee's loan
                                $loan_query = "SELECT loan_amount, loan_terms, remaining_balance FROM loans WHERE employee_id = {$row['employee_id']} AND remaining_balance > 0 LIMIT 1";
                                $loan_result = mysqli_query($conn, $loan_query);
                                $loan = mysqli_fetch_assoc($loan_result);

                                // Fetch other deductions, make sure it fetches all unpaid deductions
                                $deductions_query = "SELECT * FROM other_deductions WHERE employee_id = {$row['employee_id']} AND status != 'paid'";
                                $deductions_result = mysqli_query($conn, $deductions_query);
                                
                                // Initialize deduction values
                                $medical_savings = 0;
                                $canteen = 0;
                                $absence_late = 0;
                                $other_deduction = 0;

                                // Loop through all unpaid deductions for this employee
                                while ($deduction_row = mysqli_fetch_assoc($deductions_result)) {
                                    $medical_savings += (float)$deduction_row['medical_savings'];
                                    $canteen += (float)$deduction_row['canteen'];
                                    $absence_late += (float)$deduction_row['absence_late'];
                                }

                                $other_deduction = $medical_savings + $canteen + $absence_late;

                                // Fetch overload pay based on the start and end date of the payroll period
                                $overload_query = "
                                    SELECT SUM(amount) AS total_overload_pay 
                                    FROM overload_pay 
                                    WHERE employee_id = {$row['employee_id']} 
                                    AND start_date <= '$end_date' AND end_date >= '$start_date'";
                                $overload_result = mysqli_query($conn, $overload_query);
                                $overload_row = mysqli_fetch_assoc($overload_result);
                                $total_overload_pay = $overload_row['total_overload_pay'] ?? 0;

                                // Fetch watch pay
                                $watch_query = "SELECT SUM(amount) AS total_watch_pay FROM watch_pay WHERE employee_id = {$row['employee_id']} AND date_added BETWEEN '$start_date' AND '$end_date'";
                                $watch_result = mysqli_query($conn, $watch_query);
                                $watch_row = mysqli_fetch_assoc($watch_result);
                                $total_watch_pay = $watch_row['total_watch_pay'] ?? 0;

                                // Fetch F & S 15th pay
                                $fs_15th_query = "SELECT SUM(amount) AS total_fs_15th_pay FROM fs_15th_pay WHERE employee_id = {$row['employee_id']} AND date BETWEEN '$start_date' AND '$end_date'";
                                $fs_15th_result = mysqli_query($conn, $fs_15th_query);
                                $fs_15th_row = mysqli_fetch_assoc($fs_15th_result);
                                $total_fs_15th_pay = $fs_15th_row['total_fs_15th_pay'] ?? 0;

                                // Calculate total pay
                                $total_pay = ($row['total_regular_hours'] * ($row['daily_rate'] / 8)) + ($row['total_overtime_hours'] * ($row['daily_rate'] / 8 * 1.5));

                                // Calculate total contributions
                                $total_contributions = 0;
                                foreach ($contributions as $contribution) {
                                    $total_contributions += $contribution['amount'];
                                }

                                // Calculate loan deduction if a loan exists
                                $loan_deduction = 0;
                                if ($loan) {
                                    $loan_deduction = $loan['loan_amount'] / $loan['loan_terms'];
                                }

                                // Deduct contributions, loan, and other deductions from total pay to get net pay
                                $gross_pay = $total_pay + $total_overload_pay + $total_watch_pay + $total_fs_15th_pay; // Add overload, watch, and F & S 15th pay to total gross pay
                                $net_pay = $gross_pay - $total_contributions - $loan_deduction - $other_deduction;

                                echo "<tr>";
                                echo "<td>{$row['employee_id']}</td>";
                                echo "<td>{$row['name']}</td>";
                                echo "<td>{$row['total_regular_hours']}</td>";
                                echo "<td>{$row['total_overtime_hours']}</td>";
                                echo "<td>{$row['daily_rate']}</td>";
                              
                                echo "<td>" . number_format($gross_pay, 2) . "</td>";
                                echo "<td>
                                        <button class='btn btn-primary view-payroll-btn' data-toggle='modal' data-target='#payrollModal' 
                                            data-id='{$row['employee_id']}' 
                                            data-name='{$row['name']}' 
                                            data-regular-hours='{$row['total_regular_hours']}' 
                                            data-overtime-hours='{$row['total_overtime_hours']}' 
                                            data-daily-rate='{$row['daily_rate']}' 
                                            data-total-overload-pay='" . number_format($total_overload_pay, 2) . "' 
                                            data-total-watch-pay='" . number_format($total_watch_pay, 2) . "' 
                                            data-total-fs-15th-pay='" . number_format($total_fs_15th_pay, 2) . "' 
                                            data-total-pay='" . number_format($gross_pay, 2) . "' 
                                            data-loan-deduction='" . number_format($loan_deduction, 2) . "' 
                                            data-medical-savings='" . number_format($medical_savings, 2) . "' 
                                            data-canteen='" . number_format($canteen, 2) . "' 
                                            data-absence-late='" . number_format($absence_late, 2) . "' 
                                            data-other-deduction='" . number_format($other_deduction, 2) . "' 
                                            data-net-pay='" . number_format($net_pay, 2) . "'>
                                            View Payroll
                                        </button>
                                        <input type='hidden' class='contributions-data' value='" . htmlspecialchars(json_encode($contributions), ENT_QUOTES, 'UTF-8') . "' />
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Payroll Modal -->
<div class="modal fade" id="payrollModal" tabindex="-1" role="dialog" aria-labelledby="payrollModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="payrollModalLabel"><span id="modal-employee-name"></span></h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Payroll Period: </strong> 
                    <?php echo date('F j, Y', strtotime($start_date)); ?> - 
                    <?php echo date('F j, Y', strtotime($end_date)); ?>
                </p>

                <p><strong>Daily Rate: </strong> 
                <strong>₱ <span id="modal-daily-rate"></span></strong>
                </p>
                
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Hours Rendered</td>
                            <td><span id="modal-regular-hours"></span></td>
                        </tr>
                        <tr>
                            <td>Overtime</td>
                            <td><span id="modal-overtime-hours"></span></td>
                        </tr>
                        <tr>
                            <td>Overload Pay</td>
                            <td><span id="modal-total-overload-pay"></span></td> <!-- Overload Pay field -->
                        </tr>
                        <tr>
                            <td>Watch Pay</td>
                            <td><span id="modal-total-watch-pay"></span></td> <!-- Watch Pay field -->
                        </tr>
                        <tr>
                            <td>F & S 15th Pay</td>
                            <td><span id="modal-total-fs-15th-pay"></span></td> <!-- F & S 15th Pay field -->
                        </tr>
                        <tr>
                            <td><strong>GROSS</strong></td>
                            <td><strong>₱ <span id="modal-total-pay"></span></strong></td>
                        </tr>
                        <tr>
                            <td><strong>DEDUCTIONS</strong></td>
                        </tr>
                        <tr>
                            <td>Loan Deduction</td>
                            <td><span id="modal-loan-deduction"></span></td>
                        </tr>
                        <tr>
                            <td>Medical Savings</td>
                            <td><span id="modal-medical-savings"></span></td>
                        </tr>
                        <tr>
                            <td>Canteen</td>
                            <td><span id="modal-canteen"></span></td>
                        </tr>
                        <tr>
                            <td>Absence/Late</td>
                            <td><span id="modal-absence-late"></span></td>
                        </tr>
                        <tr>
                            <td>Other Deductions Total</td>
                            <td><span id="modal-other-deduction"></span></td>
                        </tr>
                        <tr>
                            <td>Contributions</td>
                            <td><span id="modal-contributions"></span></td>
                        </tr>
                        <tr>
                            <td><strong>NET PAY</strong></td>
                            <td><strong>₱ <span id="modal-net-pay"></span></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary finalize-payroll-btn">Finalize Payroll</button>
                <button type="button" class="btn btn-danger" id="print-payslip">Print Payslip</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Joshua and Friends</span>
        </div>
    </div>
</footer>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- JavaScript for Handling Modal Data -->
<script>
$(document).ready(function(){
    // Initialize DataTable
    $('#dataTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true
    });

    $(document).ready(function() {
    // Handle the "Print Payroll" button click
    $('#print-payroll').click(function() {
        // Ensure startDate and endDate are defined properly
        var startDate = "<?php echo $start_date; ?>";
        var endDate = "<?php echo $end_date; ?>";

        if (!startDate || !endDate) {
            alert("Start date or End date is missing!");
            return;
        }

        // Add record to payroll history via AJAX
        $.post('add_to_payroll_history.php', {
            start_date: startDate,
            end_date: endDate
        }, function(response) {
            console.log(response); // Log success or error message
        });

        // Open the payroll report in a new tab with the correct start and end date
        var form = $('<form action="print_payroll.php" method="GET" target="_blank"></form>');
        form.append('<input type="hidden" name="start_date" value="' + startDate + '">');
        form.append('<input type="hidden" name="end_date" value="' + endDate + '">');
        
        // Append and submit the form
        $('body').append(form);
        form.submit();
    });
});




    // Handle modal data population
    $('.view-payroll-btn').click(function(){
        var name = $(this).data('name');
        var regularHours = $(this).data('regular-hours');
        var overtimeHours = $(this).data('overtime-hours');
        var dailyRate = $(this).data('daily-rate');
        var totalPay = $(this).data('total-pay');
        var loanDeduction = $(this).data('loan-deduction');
        var medicalSavings = $(this).data('medical-savings');
        var canteen = $(this).data('canteen');
        var absenceLate = $(this).data('absence-late');
        var otherDeduction = $(this).data('other-deduction');
        var overloadPay = $(this).data('total-overload-pay');
        var watchPay = $(this).data('total-watch-pay');
        var fs15thPay = $(this).data('total-fs-15th-pay');
        var netPay = $(this).data('net-pay');

        // Get the payroll period start and end dates
        var startDate = "<?php echo $start_date; ?>";
        var endDate = "<?php echo $end_date; ?>";

        // Fetch the corresponding hidden contributions data
        var contributionsInput = $(this).closest('td').find('.contributions-data').val();

        // Parse contributions if it's a valid JSON string
        var contributionsList = '';
        try {
            var contributions = JSON.parse(contributionsInput); 
            contributions.forEach(function(contribution) {
                contributionsList += contribution.contribution_name + ' - ₱' + parseFloat(contribution.amount).toFixed(2) + '<br>';
            });
        } catch (e) {
            contributionsList = 'Error displaying contributions';
        }

        // Set modal data
        $('#modal-employee-name').text(name);
        $('#modal-regular-hours').text(regularHours);
        $('#modal-overtime-hours').text(overtimeHours);
        $('#modal-daily-rate').text(dailyRate);
        $('#modal-total-pay').text(totalPay);
        $('#modal-loan-deduction').text(loanDeduction);
        $('#modal-medical-savings').text(medicalSavings);
        $('#modal-canteen').text(canteen);
        $('#modal-absence-late').text(absenceLate);
        $('#modal-other-deduction').text(otherDeduction);
        $('#modal-total-overload-pay').text(overloadPay);
        $('#modal-total-watch-pay').text(watchPay);
        $('#modal-total-fs-15th-pay').text(fs15thPay);
        $('#modal-contributions').html(contributionsList);  
        $('#modal-net-pay').text(netPay);

        // When the "Print Payslip" button is clicked
        $('#print-payslip').click(function(){
            var form = $('<form action="payslip.php" method="POST" target="_blank"></form>'); // Open in a new tab
            form.append('<input type="hidden" name="employeeName" value="' + name + '">');
            form.append('<input type="hidden" name="regularHours" value="' + regularHours + '">');
            form.append('<input type="hidden" name="overtimeHours" value="' + overtimeHours + '">');
            form.append('<input type="hidden" name="dailyRate" value="' + dailyRate + '">');
            form.append('<input type="hidden" name="totalPay" value="' + totalPay + '">');
            form.append('<input type="hidden" name="loanDeduction" value="' + loanDeduction + '">');
            form.append('<input type="hidden" name="medicalSavings" value="' + medicalSavings + '">');
            form.append('<input type="hidden" name="canteen" value="' + canteen + '">');
            form.append('<input type="hidden" name="absenceLate" value="' + absenceLate + '">');
            form.append('<input type="hidden" name="otherDeduction" value="' + otherDeduction + '">');
            form.append('<input type="hidden" name="overloadPay" value="' + overloadPay + '">');
            form.append('<input type="hidden" name="watchPay" value="' + watchPay + '">');
            form.append('<input type="hidden" name="fs15thPay" value="' + fs15thPay + '">');
            form.append('<input type="hidden" name="netPay" value="' + netPay + '">');
            form.append('<input type="hidden" name="contributionsList" value="' + contributionsList + '">'); // Pass contributions
            form.append('<input type="hidden" name="startDate" value="' + startDate + '">'); // Pass start date
            form.append('<input type="hidden" name="endDate" value="' + endDate + '">');   // Pass end date

            // Append the form and submit it
            $('body').append(form);
            form.submit();
        });
    });

    // Finalize payroll and update loan balance
    $('.finalize-payroll-btn').click(function(){
        var employeeId = $('.view-payroll-btn').data('id');
        var loanDeduction = $('.view-payroll-btn').data('loan-deduction');

        // Mark deductions as paid and update loan balance
        $.post('update_loan_balance.php', {employee_id: employeeId, loan_deduction: loanDeduction}, function(response) {
            alert(response.message);
        }, 'json');
        
        $.post('update_other_deduction_status.php', {employee_id: employeeId}, function(response) {
            alert(response.message);
        }, 'json');
    });
});
</script>

<!-- Page level custom scripts -->
<script src="js/demo/datatables-demo.js"></script>

</body>
</html>
