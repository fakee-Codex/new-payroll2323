<?php
session_start();
require 'database_connection.php'; // Include the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Handle form submission to add a contribution
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contribution_name'], $_POST['amount']) && !isset($_POST['edit_contribution_id'])) {
    $contribution_name = $_POST['contribution_name'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO contributions (contribution_name, amount) VALUES (?, ?)");
    $stmt->bind_param("sd", $contribution_name, $amount);

    if ($stmt->execute()) {
        // Redirect to the same page with success message
        header("Location: manage_contributions.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle form submission to edit a contribution
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_contribution_id'])) {
    $contribution_id = $_POST['edit_contribution_id'];
    $contribution_name = $_POST['edit_contribution_name'];
    $amount = $_POST['edit_amount'];

    $stmt = $conn->prepare("UPDATE contributions SET contribution_name = ?, amount = ? WHERE contribution_id = ?");
    $stmt->bind_param("sdi", $contribution_name, $amount, $contribution_id);

    if ($stmt->execute()) {
        // Redirect to the same page with success message
        header("Location: manage_contributions.php?edit_success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all contributions from the database
$result = $conn->query("SELECT * FROM contributions");

?>

<?php
include 'header.php';
?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div id="alert" class="alert alert-success" role="alert">
                    Contribution added successfully!
                    </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['edit_success']) && $_GET['edit_success'] == 1): ?>
                    <div id="alert" class="alert alert-success" role="alert">
                    Contribution updated successfully!
                    </div>
                    <?php endif; ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Manage Contributions</h1>

                    <!-- Add Contributions Button -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <a href="#" data-toggle="modal" data-target="#addContribution" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Add Contribution</span>
                            </a>
                        </div>

                        <!-- Contributions Table -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Contribution Name</th>
                                            <th>Monthly Contribution Amount</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['contribution_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['contribution_name']); ?></td>
                                                <td><?php echo number_format($row['amount'], 2); ?></td>
                                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm edit-btn"
                                                            data-id="<?php echo $row['contribution_id']; ?>"
                                                            data-name="<?php echo $row['contribution_name']; ?>"
                                                            data-amount="<?php echo $row['amount']; ?>"
                                                            data-toggle="modal" data-target="#editContributionModal">
                                                        Edit
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

            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Joshua and Friends</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Add Contribution Modal-->
    <div class="modal fade" id="addContribution" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Contribution</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="manage_contributions.php" method="POST">
                        <div class="form-group">
                            <label for="contribution_name">Contribution Name</label>
                            <input type="text" class="form-control" id="contribution_name" name="contribution_name" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Monthly Contribution Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <input class="btn btn-primary" type="submit" value="Add Contribution">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Contribution Modal-->
    <div class="modal fade" id="editContributionModal" tabindex="-1" role="dialog" aria-labelledby="editContributionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContributionModalLabel">Edit Contribution</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="manage_contributions.php" method="POST">
                        <input type="hidden" id="edit_contribution_id" name="edit_contribution_id">
                        <div class="form-group">
                            <label for="edit_contribution_name">Contribution Name</label>
                            <input type="text" class="form-control" id="edit_contribution_name" name="edit_contribution_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_amount">Monthly Contribution Amount</label>
                            <input type="number" class="form-control" id="edit_amount" name="edit_amount" step="0.01" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <input class="btn btn-primary" type="submit" value="Update Contribution">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
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

    // Handle setting the contribution data in the edit modal
    $(document).on('click', '.edit-btn', function () {
        var contribution_id = $(this).data('id');
        var contribution_name = $(this).data('name');
        var amount = $(this).data('amount');

        // Set the values in the edit modal
        $('#edit_contribution_id').val(contribution_id);
        $('#edit_contribution_name').val(contribution_name);
        $('#edit_amount').val(amount);
    });
    </script>

</body>

</html>

<?php
$conn->close(); // Close the database connection
?>
