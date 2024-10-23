<?php
session_start();
require 'database_connection.php'; // Include the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php'); // Redirect to login if not authenticated
    exit;
}

// Handle bulk addition or update of overload pay with exclusions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_bulk'])) {
    $amount = $_POST['amount'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $excluded_employees = isset($_POST['excluded_employees']) ? $_POST['excluded_employees'] : [];

    // Fetch all employees except the excluded ones
    $placeholders = implode(',', array_fill(0, count($excluded_employees), '?'));
    $query = "SELECT employee_id FROM employees WHERE employment_status = 'full-time'";

    if (!empty($excluded_employees)) {
        $query .= " WHERE employee_id NOT IN ($placeholders)";
    }

    $stmt = $conn->prepare($query);
    if (!empty($excluded_employees)) {
        $types = str_repeat('i', count($excluded_employees));
        $stmt->bind_param($types, ...$excluded_employees);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Insert or update overload pay for all non-excluded employees
    $insert_stmt = $conn->prepare("INSERT INTO overload_pay (employee_id, amount, start_date, end_date) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE amount = ?, start_date = ?, end_date = ?");
    while ($row = $result->fetch_assoc()) {
        $employee_id = $row['employee_id'];
        $insert_stmt->bind_param("idssiss", $employee_id, $amount, $start_date, $end_date, $amount, $start_date, $end_date);
        $insert_stmt->execute();
    }

    header("Location: overload_pay.php?success=1");
    exit();
}

// Handle form submission to update multiple overload pay entries
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_multiple'])) {
    $selected_ids = explode(',', $_POST['selected_ids']); // Get selected IDs (comma-separated string) and convert to array
    $amount = $_POST['amount']; // Get the updated amount
    $start_date = $_POST['start_date']; // Get the updated start date
    $end_date = $_POST['end_date']; // Get the updated end date

    if (!empty($selected_ids)) {
        foreach ($selected_ids as $overload_id) {
            // Prepare the query to update each selected row
            $update_query = "UPDATE overload_pay SET amount = ?, start_date = ?, end_date = ? WHERE overload_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("dssi", $amount, $start_date, $end_date, $overload_id);
            $stmt->execute();
        }
        header("Location: overload_pay.php?success=1");
        exit();
    } else {
        echo "No IDs selected for update.";
    }
}

// Handle deletion of selected rows
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_selected'])) {
    $selected_ids = $_POST['selected_ids'];
    if (!empty($selected_ids)) {
        $ids = implode(',', array_map('intval', $selected_ids)); // Sanitize IDs
        $delete_query = "DELETE FROM overload_pay WHERE overload_id IN ($ids)";
        $conn->query($delete_query);
        header("Location: overload_pay.php?deleted=1");
        exit();
    }
}

// Fetch overload pay entries for a specified date range
$filter_start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$filter_end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

$result = $conn->query("
    SELECT op.overload_id, op.employee_id, op.amount, op.start_date, op.end_date, e.first_name, e.last_name 
    FROM overload_pay op 
    LEFT JOIN employees e ON op.employee_id = e.employee_id
    WHERE e.employment_status = 'full-time'
    AND op.start_date <= '$filter_end_date' 
    AND op.end_date >= '$filter_start_date'
");


?>

<?php
include 'header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="alert" class="alert alert-success" role="alert">
    Overload pay updated successfully!
    </div>
    <?php elseif (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
    <div id="alert" class="alert alert-success" role="alert">
    Selected entries deleted successfully!
    </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Manage Overload Pay</h1>

    <!-- Add Bulk Overload Pay Button -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="#" data-toggle="modal" data-target="#addBulkOverloadPay" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Bulk Overload Pay</span>
            </a>
        </div>

        <!-- Overload Pay Table -->
        <div class="card-body">
            <form id="manageOverloadForm" method="POST" action="overload_pay.php">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select_all"></th>
                                <th>ID</th>
                                <th>Employee Name</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><input type="checkbox" class="checkbox" name="selected_ids[]" value="<?php echo htmlspecialchars($row['overload_id']); ?>"></td>
                                    <td><?php echo htmlspecialchars($row['overload_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></td>
                                    <td><?php echo number_format($row['amount'], 2); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['start_date'])); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['end_date'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Edit and Delete Buttons -->
                <button type="button" class="btn btn-primary" id="edit_selected" disabled>Edit</button>
                <button type="submit" class="btn btn-danger" name="delete_selected" onclick="return confirm('Are you sure you want to delete the selected entries?')">Delete Selected</button>
            </form>
        </div>
    </div>
</div>

<!-- Add Bulk Overload Pay Modal -->
<div class="modal fade" id="addBulkOverloadPay" tabindex="-1" role="dialog" aria-labelledby="addBulkOverloadPayLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBulkOverloadPayLabel">Add Bulk Overload Pay<br/> * ONLY FOR FULL-TIME EMPLOYEE</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="overload_pay.php" method="POST">
                    <input type="hidden" name="add_bulk" value="1">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                    <div class="form-group">
                        <label for="excluded_employees">Exclude Employees</label>
                        <select class="form-control" id="excluded_employees" name="excluded_employees[]" multiple>
                            <?php
                            // Fetch all employees
                            $employees = $conn->query("SELECT employee_id, first_name, last_name FROM employees WHERE employment_status = 'full-time'");

                            while ($emp = $employees->fetch_assoc()) {
                                echo "<option value='{$emp['employee_id']}'>{$emp['first_name']} {$emp['last_name']}</option>";
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) button to select multiple employees.</small>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Add Bulk Overload Pay">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Multiple Overload Pay Modal -->
<div class="modal fade" id="editMultipleOverloadPayModal" tabindex="-1" role="dialog" aria-labelledby="editMultipleOverloadPayModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMultipleOverloadPayModalLabel">Edit Selected Overload Pay</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editMultipleOverloadPayForm" method="POST" action="overload_pay.php">
                    <input type="hidden" name="edit_multiple" value="1">
                    <!-- Add a hidden input for selected ids -->
                    <input type="hidden" name="selected_ids" id="selected_ids_input">
                    <div class="form-group">
                        <label for="edit_amount">Amount</label>
                        <input type="number" class="form-control" id="edit_amount" name="amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_start_date">Start Date</label>
                        <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_end_date">End Date</label>
                        <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
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

<!-- DataTables plugin -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/datatables-demo.js"></script>

<!-- JavaScript to handle select all, edit, and delete -->
<script>
// Before reinitializing the DataTable, destroy the previous instance if it exists
if ($.fn.DataTable.isDataTable('#dataTable')) {
    $('#dataTable').DataTable().clear().destroy(); // Destroy existing DataTable
}

// Reinitialize the DataTable
$('#dataTable').DataTable({
    paging: true,
    searching: true,
    ordering: true,
    info: true,
    // Add any other DataTable options you need
});

document.getElementById('select_all').addEventListener('change', function() {
    var checkboxes = document.querySelectorAll('.checkbox');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
    toggleEditButton();
});

document.querySelectorAll('.checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        toggleEditButton();
    });
});

function toggleEditButton() {
    var selectedCount = document.querySelectorAll('.checkbox:checked').length;
    var editButton = document.getElementById('edit_selected');
    editButton.disabled = selectedCount === 0; // Enable only when 1 or more rows are selected
}

// Handle edit button click (for multiple rows)
document.getElementById('edit_selected').addEventListener('click', function() {
    var selectedCheckboxes = document.querySelectorAll('.checkbox:checked');
    if (selectedCheckboxes.length > 0) {
        // Collect all selected IDs
        var selected_ids = [];
        selectedCheckboxes.forEach(function(checkbox) {
            selected_ids.push(checkbox.value);
        });
        // Set the selected IDs in the hidden input of the modal
        document.getElementById('selected_ids_input').value = selected_ids.join(',');

        // Open the modal for editing multiple rows
        $('#editMultipleOverloadPayModal').modal('show');
    }
});


</script>

</body>
</html>
