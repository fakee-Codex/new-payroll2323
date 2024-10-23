<?php
session_start();
// Database connection
require 'database_connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Process form submission for setting daily rate and hourly rate
if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['daily_rate']) || isset($_POST['hourly_rate']))) {
    $employee_id = $_POST['employee_id'];
    $start_date = $_POST['start_date'];

    // Check if hourly rate is set, and calculate daily rate from it
    if (!empty($_POST['hourly_rate'])) {
        $hourly_rate = $_POST['hourly_rate'];
        $daily_rate = $hourly_rate * 8; // Calculate the daily rate based on hourly rate
    } else {
        $daily_rate = $_POST['daily_rate'];
        $hourly_rate = $daily_rate / 8; // Calculate the hourly rate based on daily rate
    }

    // Ensure new start date is after the last end date
    $sql_check_last_end_date = "SELECT end_date FROM daily_rate WHERE employee_id = ? ORDER BY end_date DESC LIMIT 1";
    $stmt_check = $conn->prepare($sql_check_last_end_date);
    $stmt_check->bind_param("i", $employee_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $last_end_date = $result_check->fetch_assoc()['end_date'];
        if ($last_end_date !== null && $start_date <= $last_end_date) {
            echo "Error: Start date must be after the last end date.";
            exit();
        }
    }

    // Set the end date of the current active rate (if exists) to the day before the new start date
    $sql_update_end_date = "UPDATE daily_rate 
                            SET end_date = DATE_SUB(?, INTERVAL 1 DAY)
                            WHERE employee_id = ? AND end_date IS NULL";
    $stmt = $conn->prepare($sql_update_end_date);
    $stmt->bind_param("si", $start_date, $employee_id);
    $stmt->execute();

    // Insert the new daily rate and hourly rate with the provided start date
    $sql_insert_rate = "INSERT INTO daily_rate (employee_id, daily_rate, hourly_rate, start_date) 
                        VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert_rate);
    $stmt->bind_param("idds", $employee_id, $daily_rate, $hourly_rate, $start_date);

    if ($stmt->execute()) {
        // After successfully saving, show success notification
        header("Location: " . $_SERVER['PHP_SELF'] . "?rate_success=1");
        exit();
    } else {
        echo "Error saving daily rate: " . $stmt->error;
    }
}

// Fetch employees and their daily rates, including end_date
$employee_rates = $conn->query("
    SELECT e.employee_id, e.first_name, e.last_name, dr.daily_rate, dr.hourly_rate, dr.start_date, dr.end_date
    FROM employees e
    LEFT JOIN daily_rate dr ON e.employee_id = dr.employee_id
    ORDER BY e.first_name
");

include 'header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if (isset($_GET['rate_success']) && $_GET['rate_success'] == 1): ?>
        <div id="alert" class="alert alert-success" role="alert">
            Daily rate set successfully!
        </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Employee Daily Rate Input</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="#" data-toggle="modal" data-target="#addDailyRate" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Set Employee Daily Rate</span>
            </a>
        </div>

        <!-- Daily Rates Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Hourly Rate</th>
                            <th>Daily Rate</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $employee_rates->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></td>

                            <!-- Display Hourly Rate -->
                            <td><?php echo isset($row['hourly_rate']) ? number_format($row['hourly_rate'], 2) : 'Not set'; ?></td>

                            <!-- Display Daily Rate -->
                            <td><?php echo isset($row['daily_rate']) ? number_format($row['daily_rate'], 2) : 'Not set'; ?></td>

                            <!-- Display Start Date -->
                            <td><?php echo isset($row['start_date']) ? date("F j, Y", strtotime($row['start_date'])) : '-'; ?></td>

                            <!-- Display End Date or "Active" if end_date is NULL -->
                            <td>
                                <?php 
                                if (isset($row['end_date']) && $row['end_date'] !== NULL) {
                                    echo date("F j, Y", strtotime($row['end_date']));
                                } else {
                                    echo "Active";
                                }
                                ?>
                            </td>

                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Daily Rate Modal-->
<div class="modal fade" id="addDailyRate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Set Daily Rate and Hourly Rate</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <!-- Employee Selection -->
                    <div class="form-group">
                        <label for="employee_id">Select Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control" required>
                            <?php
                            // Fetch all employees
                            $employees = $conn->query("SELECT employee_id, first_name, last_name FROM employees");
                            while ($row = $employees->fetch_assoc()) {
                                echo "<option value='{$row['employee_id']}'>{$row['first_name']} {$row['last_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Hourly Rate Input -->
                    <div class="form-group">
                        <label for="hourly_rate">Hourly Rate</label>
                        <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" class="form-control" oninput="updateDailyRate()" required>
                    </div>

                    <!-- Daily Rate Input -->
                    <div class="form-group">
                        <label for="daily_rate">Daily Rate</label>
                        <input type="number" step="0.01" name="daily_rate" id="daily_rate" class="form-control" oninput="updateHourlyRate()" required>
                    </div>

                    <!-- Start Date Input -->
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Set Daily Rate">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


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

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>


<!-- JavaScript for updating hourly and daily rates dynamically -->
<script>
    // Function to update the daily rate based on the hourly rate
    function updateDailyRate() {
        var hourlyRateInput = document.getElementById('hourly_rate').value;
        if (hourlyRateInput) {
            var dailyRate = hourlyRateInput * 8;
            document.getElementById('daily_rate').value = dailyRate.toFixed(2); // Reflect daily rate
        }
    }

    // Function to update the hourly rate based on the daily rate
    function updateHourlyRate() {
        var dailyRateInput = document.getElementById('daily_rate').value;
        if (dailyRateInput) {
            var hourlyRate = dailyRateInput / 8;
            document.getElementById('hourly_rate').value = hourlyRate.toFixed(2); // Reflect hourly rate
        }
    }
</script>

<!-- Alert Notification -->
<script>
    // Set a timeout to hide the alert after 4 seconds (4000 ms)
    setTimeout(function() {
        var alertElement = document.getElementById('alert');
        if (alertElement) {
            alertElement.style.display = 'none';
        }
    }, 4000);
</script>

<?php
$conn->close(); // Close the database connection
?>


