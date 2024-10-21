<?php
session_start();
require 'database_connection.php'; // Include the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php'); // Redirect to login if not authenticated
    exit;
}

// Handle form submission to add other deductions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employee_id'], $_POST['medical_savings'], $_POST['canteen'], $_POST['absence_late'])) {
    $employee_id = $_POST['employee_id'];
    $medical_savings = $_POST['medical_savings'];
    $canteen = $_POST['canteen'];
    $absence_late = $_POST['absence_late'];

    // Calculate the total deduction
    $total_deduction = $medical_savings + $canteen + $absence_late;

    // Insert the deductions into the deductions table
    $stmt = $conn->prepare("INSERT INTO other_deductions (employee_id, medical_savings, canteen, absence_late, total_deduction) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idddd", $employee_id, $medical_savings, $canteen, $absence_late, $total_deduction);

    if ($stmt->execute()) {
        // Redirect to the same page with success message
        header("Location: other_deduction.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all deductions from the database
$result = $conn->query("
    SELECT od.deduction_id, od.employee_id, od.medical_savings, od.canteen, od.absence_late, od.total_deduction, od.status, od.created_at, e.first_name, e.last_name 
    FROM other_deductions od 
    LEFT JOIN employees e ON od.employee_id = e.employee_id
");

?>

<?php
include 'header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="alert" class="alert alert-success" role="alert">
    Deductions added successfully!
    </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Manage Other Deductions</h1>

    <!-- Add Deduction Button -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="#" data-toggle="modal" data-target="#addDeduction" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Deduction</span>
            </a>
        </div>

        <!-- Deductions Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Medical Savings</th>
                            <th>Canteen</th>
                            <th>Absence/Late</th>
                            <th>Total Deduction</th>
                            <th>Date</th> <!-- Add created_at column -->
                            <th>Status</th>
                            
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['deduction_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo number_format($row['medical_savings'], 2); ?></td>
                                <td><?php echo number_format($row['canteen'], 2); ?></td>
                                <td><?php echo number_format($row['absence_late'], 2); ?></td>
                                <td><?php echo number_format($row['total_deduction'], 2); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td> <!-- Display formatted created_at -->
                                <td><?php echo htmlspecialchars($row['status']); ?></td> <!-- Display status -->
                                

                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Deduction Modal -->
<div class="modal fade" id="addDeduction" tabindex="-1" role="dialog" aria-labelledby="addDeductionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDeductionLabel">Add Deduction</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="other_deduction.php" method="POST">
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
                        <label for="medical_savings">Medical Savings</label>
                        <input type="number" class="form-control" id="medical_savings" name="medical_savings" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="canteen">Canteen</label>
                        <input type="number" class="form-control" id="canteen" name="canteen" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="absence_late">Absence/Late</label>
                        <input type="number" class="form-control" id="absence_late" name="absence_late" step="0.01" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Add Deduction">
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
