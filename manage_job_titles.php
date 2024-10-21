<?php
session_start();
require 'database_connection.php'; // Include the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php'); // Redirect to login if not authenticated
    exit;
}

// Fetch all departments for the dropdown selection in the form
$departments_sql = "SELECT department_id, department_name FROM departments";
$departments_result = $conn->query($departments_sql);

// Handle adding a new job title
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = $_POST['job_title'];
    $department_id = intval($_POST['department_id']); // Capture the department_id from the form

    // Insert new job title into the database with a reference to the department
    if (!empty($job_title) && !empty($department_id)) {
        $sql = "INSERT INTO job_titles (job_title, department_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $job_title, $department_id);
        
        if ($stmt->execute()) {
            // Redirect to prevent duplicate form submission
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit(); // Stop the script after redirect
        } else {
            echo "Error adding job title.";
        }
    } else {
        echo "Please enter a job title and select a department.";
    }
}

// Handle deleting a job title
if (isset($_GET['delete'])) {
    $job_title_id = intval($_GET['delete']);

    try {
        // Delete the job title from the database
        $sql = "DELETE FROM job_titles WHERE job_title_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $job_title_id);

        if ($stmt->execute()) {
            // Redirect to prevent re-execution on refresh
            header("Location: " . $_SERVER['PHP_SELF'] . "?delete_success=1");
            exit();
        } else {
            throw new Exception("Error deleting job title.");
        }
    } catch (mysqli_sql_exception $e) {
        // Handle foreign key constraint violation
        if ($e->getCode() == 1451) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?delete_error=foreign_key");
            exit();
        } else {
            echo "An unexpected error occurred: " . $e->getMessage();
        }
    }
}

// Fetch all job titles and their associated departments
$sql = "
    SELECT jt.job_title_id, jt.job_title, d.department_name 
    FROM job_titles jt
    LEFT JOIN departments d ON jt.department_id = d.department_id
";
$result = $conn->query($sql);
?>


<?php
include 'header.php';
?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div id="alert" class="alert alert-success" role="alert">
                    Job title added successfully!
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
                    <h1 class="h3 mb-2 text-gray-800">Manage Job Title</h1>
                    <p class="mb-4">The Manage Employees section enables administrators to view, update, and manage employee records efficiently. Administrators can easily search, edit, or remove employee details as needed..</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <a href="#" data-toggle="modal" data-target="#addJobTitle" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <span class="text">Add Job Title</span>
                                    </a>
                                    
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Job Title</th>
                                            <th>Department</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Job Title</th>
                                            <th>Department</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['job_title_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                                                <td><?php echo htmlspecialchars($row['department_name']); ?></td> <!-- Display department name -->
                                                <td>
                                                    <a href="manage_job_titles.php?delete=<?php echo $row['job_title_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this job title?');">Delete</a>
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
    <div class="modal fade" id="addJobTitle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Job Title</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="manage_job_titles.php" method="POST">
                    <!-- Job Title Input -->
                    <div class="form-group">
                        <label for="job_title">Job Title:</label>
                        <input class="form-control form-control-user" type="text" id="job_title" name="job_title" required>
                    </div>
                    
                    <!-- Department Selection Dropdown -->
                    <div class="form-group">
                        <label for="department_id">Department:</label>
                        <select class="form-control" id="department_id" name="department_id" required>
                            <option value="">Select a Department</option>
                            <?php
                            // Fetch departments from the database
                            $departments_sql = "SELECT department_id, department_name FROM departments";
                            $departments_result = $conn->query($departments_sql);

                            // Loop through the departments and create options
                            while ($row = $departments_result->fetch_assoc()): ?>
                                <option value="<?= $row['department_id']; ?>"><?= $row['department_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Add Job Title">
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


