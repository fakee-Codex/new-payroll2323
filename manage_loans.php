<?php
session_start();
require 'database_connection.php'; // Include the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Handle form submission to add a loan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employee_id'], $_POST['loan_amount'], $_POST['loan_terms'])) {
    $employee_id = $_POST['employee_id'];
    $loan_amount = $_POST['loan_amount'];
    $loan_description = $_POST['loan_description']; // Optional description
    $loan_terms = $_POST['loan_terms']; // Number of terms to repay the loan
    $remaining_balance = $loan_amount; // Initially, the remaining balance is the full loan amount

    // Insert the loan into the loans table
    $stmt = $conn->prepare("INSERT INTO loans (employee_id, loan_amount, loan_description, loan_terms, remaining_balance) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idsid", $employee_id, $loan_amount, $loan_description, $loan_terms, $remaining_balance);

    if ($stmt->execute()) {
        // Redirect to the same page with success message
        header("Location: manage_loans.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle form submission to edit a loan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_loan_id'])) {
    $loan_id = $_POST['edit_loan_id'];
    $employee_id = $_POST['edit_employee_id'];
    $loan_amount = $_POST['edit_loan_amount'];
    $loan_description = $_POST['edit_loan_description'];
    $loan_terms = $_POST['edit_loan_terms']; // Update loan terms if edited
    $remaining_balance = $_POST['edit_remaining_balance']; // Keep track of the remaining balance

    // Update the loan in the loans table
    $stmt = $conn->prepare("UPDATE loans SET employee_id = ?, loan_amount = ?, loan_description = ?, loan_terms = ?, remaining_balance = ? WHERE loan_id = ?");
    $stmt->bind_param("idsidi", $employee_id, $loan_amount, $loan_description, $loan_terms, $remaining_balance, $loan_id);

    if ($stmt->execute()) {
        // Redirect to the same page with success message
        header("Location: manage_loans.php?edit_success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all loans from the database
$result = $conn->query("
    SELECT l.loan_id, l.employee_id, l.loan_amount, l.loan_description, l.loan_terms, l.remaining_balance, l.created_at, e.first_name, e.last_name 
    FROM loans l 
    LEFT JOIN employees e ON l.employee_id = e.employee_id
");


?>

<?php
include 'header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="alert" class="alert alert-success" role="alert">
    Loan added successfully!
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['edit_success']) && $_GET['edit_success'] == 1): ?>
    <div id="alert" class="alert alert-success" role="alert">
    Loan updated successfully!
    </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Manage Loans</h1>

    <!-- Add Loan Button -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="#" data-toggle="modal" data-target="#addLoan" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Loan</span>
            </a>
        </div>

        <!-- Loans Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Loan Amount</th>
                            <th>Description</th>
                            <th>Loan Terms</th>
                            <th>Remaining Balance</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['loan_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo number_format($row['loan_amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($row['loan_description']); ?></td>
                                <td><?php echo htmlspecialchars($row['loan_terms']); ?></td>
                                <td><?php echo number_format($row['remaining_balance'], 2); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <?php echo "<td>
                                    <button class='btn btn-primary btn-sm edit-btn'
                                            data-id='{$row['loan_id']}'
                                            data-employee-id='{$row['employee_id']}'
                                            data-amount='" . htmlspecialchars($row['loan_amount'], ENT_QUOTES, 'UTF-8') . "'
                                            data-description='" . htmlspecialchars($row['loan_description'], ENT_QUOTES, 'UTF-8') . "'
                                            data-terms='" . htmlspecialchars($row['loan_terms'], ENT_QUOTES, 'UTF-8') . "'
                                            data-balance='" . htmlspecialchars($row['remaining_balance'], ENT_QUOTES, 'UTF-8') . "'
                                            data-toggle='modal' data-target='#editLoanModal'>
                                            Edit
                                    </button>
                                </td>";
                            ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Loan Modal -->
<div class="modal fade" id="addLoan" tabindex="-1" role="dialog" aria-labelledby="addLoanLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLoanLabel">Add Loan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="manage_loans.php" method="POST">
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
                        <label for="loan_amount">Loan Amount</label>
                        <input type="number" class="form-control" id="loan_amount" name="loan_amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="loan_terms">Number of Loan Terms</label>
                        <input type="number" class="form-control" id="loan_terms" name="loan_terms" required>
                    </div>
                    <div class="form-group">
                        <label for="loan_description">Loan Description (Optional)</label>
                        <input type="text" class="form-control" id="loan_description" name="loan_description">
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Add Loan">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Loan Modal -->
<div class="modal fade" id="editLoanModal" tabindex="-1" role="dialog" aria-labelledby="editLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLoanModalLabel">Edit Loan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="manage_loans.php" method="POST">
                    <input type="hidden" id="edit_loan_id" name="edit_loan_id">
                    <div class="form-group">
                        <label for="edit_employee_id">Select Employee</label>
                        <select class="form-control" id="edit_employee_id" name="edit_employee_id" required>
                            <?php
                            // Fetch all employees for the edit form
                            $employees = $conn->query("SELECT employee_id, first_name, last_name FROM employees");
                            while ($emp = $employees->fetch_assoc()) {
                                echo "<option value='{$emp['employee_id']}'>{$emp['first_name']} {$emp['last_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_loan_amount">Loan Amount</label>
                        <input type="number" class="form-control" id="edit_loan_amount" name="edit_loan_amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_loan_terms">Loan Terms</label>
                        <input type="number" class="form-control" id="edit_loan_terms" name="edit_loan_terms" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_remaining_balance">Remaining Balance</label>
                        <input type="number" class="form-control" id="edit_remaining_balance" name="edit_remaining_balance" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_loan_description">Loan Description (Optional)</label>
                        <input type="text" class="form-control" id="edit_loan_description" name="edit_loan_description">
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Update Loan">
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

<!-- Alert Notification -->
<script>
// Set a timeout to hide the alert after 3 seconds (3000 ms)
setTimeout(function() {
    var alertElement = document.getElementById('alert');
    if (alertElement) {
    alertElement.style.display = 'none';
    }
}, 4000);

// Handle setting the loan data in the edit modal
$(document).on('click', '.edit-btn', function () {
    var loan_id = $(this).data('id');
    var employee_id = $(this).data('employee-id');
    var loan_amount = $(this).data('amount');
    var loan_description = $(this).data('description');
    var loan_terms = $(this).data('terms');
    var remaining_balance = $(this).data('balance');

    // Set the values in the edit modal
    $('#edit_loan_id').val(loan_id);
    $('#edit_employee_id').val(employee_id);
    $('#edit_loan_amount').val(loan_amount);
    $('#edit_loan_description').val(loan_description);
    $('#edit_loan_terms').val(loan_terms);
    $('#edit_remaining_balance').val(remaining_balance);
});
</script>

</body>
</html>
