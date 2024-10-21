<?php
session_start();
require 'database_connection.php'; // Include the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php'); // Redirect to login if not authenticated
    exit;
}

// Handle form submission to add F & S 15th pay
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employee_id'], $_POST['amount'], $_POST['date'])) {
    $employee_id = $_POST['employee_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    // Insert the F & S 15th pay into the fs_15th_pay table
    $stmt = $conn->prepare("INSERT INTO fs_15th_pay (employee_id, amount, date) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $employee_id, $amount, $date);

    if ($stmt->execute()) {
        // Redirect to the same page with success message
        header("Location: fs_15th_pay.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all F & S 15th pay entries from the database
$result = $conn->query("
    SELECT fs.fs_15th_id, fs.employee_id, fs.amount, fs.date, e.first_name, e.last_name 
    FROM fs_15th_pay fs 
    LEFT JOIN employees e ON fs.employee_id = e.employee_id
");

?>

<?php
include 'header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="alert" class="alert alert-success" role="alert">
    F & S 15th Pay added successfully!
    </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Manage F & S 15th Pay</h1>

    <!-- Add F & S 15th Pay Button -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="#" data-toggle="modal" data-target="#addFS15thPay" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add F & S 15th Pay</span>
            </a>
        </div>

        <!-- F & S 15th Pay Table -->
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
                                <td><?php echo htmlspecialchars($row['fs_15th_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo number_format($row['amount'], 2); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add F & S 15th Pay Modal -->
<div class="modal fade" id="addFS15thPay" tabindex="-1" role="dialog" aria-labelledby="addFS15thPayLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFS15thPayLabel">Add F & S 15th Pay</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="fs_15th_pay.php" method="POST">
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
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Add F & S 15th Pay">
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
