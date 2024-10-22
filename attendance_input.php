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

// Fetch unique employees with attendance records
$employees_with_attendance = $conn->query("
    SELECT e.employee_id, e.first_name, e.last_name 
    FROM attendance a 
    JOIN employees e ON a.employee_id = e.employee_id 
    GROUP BY e.employee_id
");

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
                            <th>Show Logs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $employees_with_attendance->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></td>
                                <td>
                                <button class="btn btn-primary show-logs-btn" 
                                        data-employee-id="<?php echo $row['employee_id']; ?>" 
                                        data-employee-name="<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>" 
                                        data-toggle="modal" data-target="#logsModal">
                                    Show Logs
                                </button>

                                </td>
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

<!-- Logs Modal -->
<div class="modal fade" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="logsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logsModalLabel">{Employee Name} Attendance Logs</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Date Filter -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <input type="date" id="startDate" class="form-control" placeholder="Start Date">
                    </div>
                    <div class="col-md-4">
                        <input type="date" id="endDate" class="form-control" placeholder="End Date">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" id="filterBtn">Filter</button>
                    </div>
                </div>

                <!-- Logs Table -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="logsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total Hours Worked</th>
                                <th>Regular Hours</th>
                                <th>Overtime Hours</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="log-table-body">
                            <!-- Logs will be dynamically loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to handle logs display -->
<script>
    document.querySelectorAll('.show-logs-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const employeeId = this.dataset.employeeId;
            const employeeName = this.dataset.employeeName;
            document.getElementById('logsModalLabel').textContent = `${employeeName} Attendance Logs`;

            loadLogs(employeeId); // Initial load without filters

            // Set up filter button
            document.getElementById('filterBtn').addEventListener('click', function() {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                loadLogs(employeeId, startDate, endDate); // Load with date filters
            });
        });
    });

    function loadLogs(employeeId, startDate = '', endDate = '') {
        // Load logs via AJAX with optional date filters
        let query = `fetch_attendance_logs.php?employee_id=${employeeId}`;
        if (startDate && endDate) {
            query += `&start_date=${startDate}&end_date=${endDate}`;
        }

        fetch(query)
            .then(response => response.json())
            .then(data => {
                const logTableBody = document.querySelector('.log-table-body');
                logTableBody.innerHTML = ''; // Clear previous logs

                // Append logs as table rows
                data.logs.forEach(log => {
                    const logRow = document.createElement('tr');
                    logRow.innerHTML = `
                        <td>${log.date}</td>
                        <td>${log.total_hours}</td>
                        <td>${log.regular_hours}</td>
                        <td>${log.overtime_hours}</td>
                        <td>${log.status}</td>
                    `;
                    logTableBody.appendChild(logRow);
                });
            });
    }
</script>


<!-- Alert Notification -->
<script>
    setTimeout(function() {
        var alertElement = document.getElementById('alert');
        if (alertElement) {
            alertElement.style.display = 'none';
        }
    }, 4000);
</script>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="js/demo/datatables-demo.js"></script>

</body>
</html>
