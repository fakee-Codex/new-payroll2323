<?php
session_start();
require 'database_connection.php'; // Include the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php'); // Redirect to login if not authenticated
    exit;
}

// Handle adding a new department
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_name = $_POST['department_name'];
    
    // Insert new department into the database
    if (!empty($department_name)) {
        $sql = "INSERT INTO departments (department_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $department_name);
        
        if ($stmt->execute()) {
            // Redirect with success flag to prevent duplicate form submission
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit(); // Stop script execution after the redirect
        } else {
            echo "Error adding department.";
        }
    } else {
        echo "Please enter a department name.";
    }
}



// Handle deleting a department
if (isset($_GET['delete'])) {
    $department_id = intval($_GET['delete']);
    
    try {
        // Delete the department from the database
        $sql = "DELETE FROM departments WHERE department_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $department_id);
        
        if ($stmt->execute()) {
            // Redirect with delete success flag
            header("Location: " . $_SERVER['PHP_SELF'] . "?delete_success=1");
            exit();
        } else {
            throw new Exception("Error deleting department.");
        }
    } catch (mysqli_sql_exception $e) {
        // Check if the error is due to a foreign key constraint violation
        if ($e->getCode() == 1451) {
            // Redirect with a custom error message in the URL
            header("Location: " . $_SERVER['PHP_SELF'] . "?delete_error=foreign_key");
            exit();
        } else {
            // Handle other errors (optional)
            echo "An unexpected error occurred: " . $e->getMessage();
        }
    }
}



// Fetch all departments from the database
$sql = "SELECT * FROM departments";
$result = $conn->query($sql);



?>

<?php
include 'header.php';
?>




                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div id="alert" class="alert alert-success" role="alert">
                    Department added successfully!
                    </div>
                    <?php endif; ?>

                    <!-- Success alert for department deletion -->
                    <?php if (isset($_GET['delete_success']) && $_GET['delete_success'] == 1): ?>
                    <div id="alert" class="alert alert-danger" role="alert">
                    Department deleted successfully!
                    </div>
                    <?php endif; ?>

                    <!-- Display error message for foreign key constraint -->
                    <?php if (isset($_GET['delete_error']) && $_GET['delete_error'] == 'foreign_key'): ?>
                    <div id="alert" class="alert alert-danger" role="alert">
                    Cannot delete this department because it is being referenced by other records (e.g., employees).
                    </div>
                    <?php endif; ?>
                    
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Manage Departments</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <a href="#" data-toggle="modal" data-target="#addDepartment" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <span class="text">Add Department</span>
                                    </a>
                                    
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                        <th>ID</th>
                                            <th>Department Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                        <th>ID</th>
                                            <th>Department Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['department_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                                <td>
                                                    <a href="manage_departments.php?delete=<?php echo $row['department_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this department?');">Delete</a>
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

    <!-- Add Department Modal-->
    <div class="modal fade" id="addDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Department</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="manage_departments.php" method="POST">
                        <input class="form-control form-control-user" type="text" id="department_name" name="department_name" required>
                        
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <input class="btn btn-primary" type="submit" value="Add Department">
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
    </script>

</body>

</html>


<?php
$conn->close(); // Close the database connection
?>


