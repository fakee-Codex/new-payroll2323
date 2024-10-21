<?php
session_start();

// Database connection
require 'database_connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];

    // Define regular hours threshold (e.g., 8 hours)
    $regular_hours_threshold = 8;

    if ($status == 'Absent') {
        // If Absent, set total hours to 0
        $total_hours = 0;
        $regular_hours = 0;
        $overtime_hours = 0;
    } else {
        // If Present, use the submitted total hours worked
        $total_hours = (float)$_POST['total_hours']; // Get total hours worked

        // Calculate regular hours and overtime hours
        if ($total_hours > $regular_hours_threshold) {
            $regular_hours = $regular_hours_threshold;
            $overtime_hours = $total_hours - $regular_hours_threshold;
        } else {
            $regular_hours = $total_hours;
            $overtime_hours = 0;
        }
    }

    // Insert into the attendance table
    $stmt = $conn->prepare("INSERT INTO attendance (employee_id, date, total_hours, regular_hours, overtime_hours, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isddds", $employee_id, $attendance_date, $total_hours, $regular_hours, $overtime_hours, $status);

    if ($stmt->execute()) {
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    } else {
        echo "Error saving attendance: " . $stmt->error;
    }
}

// Fetch attendance records
$attendance_records = $conn->query("SELECT a.*, e.first_name, e.last_name FROM attendance a JOIN employees e ON a.employee_id = e.employee_id");
?>

<?php
include 'header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div id="alert" class="alert alert-success" role="alert">
            Attendance saved successfully!
        </div>
    <?php endif; ?>
    
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daily Time Records</h1>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="#" data-toggle="modal" data-target="#addAttendance" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Attendance</span>
            </a>
        </div>
        <!-- Attendance Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Date</th>
                            <th>Total Hours Worked</th>
                            <th>Regular Hours</th>
                            <th>Overtime Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $attendance_records->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['attendance_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo date("F j, Y", strtotime($row['date'])); ?></td>
                                <td><?php echo htmlspecialchars($row['total_hours']); ?></td>
                                <td><?php echo htmlspecialchars($row['regular_hours']); ?></td>
                                <td><?php echo htmlspecialchars($row['overtime_hours']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Attendance Modal-->
<div class="modal fade" id="addAttendance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Attendance</h5>
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

                    <!-- Status -->
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                        </select>
                    </div>
                    
                    <!-- Attendance Date -->
                    <div class="form-group">
                        <label for="attendance_date">Date</label>
                        <input type="date" name="attendance_date" id="attendance_date" class="form-control" required>
                    </div>

                    <!-- Total Hours Worked -->
                    <div class="form-group">
                        <label for="total_hours">Total Hours Worked</label>
                        <input type="number" name="total_hours" id="total_hours" class="form-control" step="0.01" min="0" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Add Attendance">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to set today's date -->
<script>
    // Get today's date in the local timezone and format it as YYYY-MM-DD
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
    const day = String(today.getDate()).padStart(2, '0');

    // Set the value of the date input to the local date
    document.getElementById('attendance_date').value = `${year}-${month}-${day}`;
</script>

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

<!-- Alert Notification -->
<script>
    setTimeout(function() {
        var alertElement = document.getElementById('alert');
        if (alertElement) {
            alertElement.style.display = 'none';
        }
    }, 4000);
</script>

</body>
</html>
