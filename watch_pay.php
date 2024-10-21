<?php
session_start();
require 'database_connection.php'; // Include the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php'); // Redirect to login if not authenticated
    exit;
}

// Handle form submission to add watch pay
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employee_id'], $_POST['amount'], $_POST['date_added'])) {
    $employee_id = $_POST['employee_id'];
    $amount = $_POST['amount'];
    $date_added = $_POST['date_added'];

    // Insert the watch pay into the watch_pay table
    $stmt = $conn->prepare("INSERT INTO watch_pay (employee_id, amount, date_added) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $employee_id, $amount, $date_added);

    if ($stmt->execute()) {
        // Redirect to the same page with success message
        header("Location: watch_pay.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all watch payments from the database
$result = $conn->query("
    SELECT wp.id, wp.employee_id, wp.amount, wp.date_added, e.first_name, e.last_name 
    FROM watch_pay wp 
    LEFT JOIN employees e ON wp.employee_id = e.employee_id
");
?>

<?php
include 'header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="alert" class="alert alert-success" role="alert">
    Watch Pay added successfully!
    </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Manage Watch Pay</h1>

    <!-- Add Watch Pay Button -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="#" data-toggle="modal" data-target="#addWatchPay" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Watch Pay</span>
            </a>
        </div>

        <!-- Watch Pay Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo number_format($row['amount'], 2); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['date_added'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Watch Pay Modal -->
<div class="modal fade" id="addWatchPay" tabindex="-1" role="dialog" aria-labelledby="addWatchPayLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWatchPayLabel">Add Watch Pay</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="watch_pay.php" method="POST">
                    <div class="form-group">
                        <label for="employee_id">Select Employee</label>
                        <select class="form-control" id="employee_id" name="employee_id" required>
                            <?php
                            // Fetch all employees
                            $employees = $conn->query("SELECT employee_id, first_name, last_name FROM employees");
                            while ($emp = $employees->fetch_assoc()) {
                                echo "<option value='{$emp['employee_id']}'>{$emp['first_name']} {$emp['last_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="date_added">Date</label>
                        <input type="date" class="form-control" id="date_added" name="date_added" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Add Watch Pay">
                </form>
            </div>
        </div>
    </div>
</div>

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

</body>
</html>
