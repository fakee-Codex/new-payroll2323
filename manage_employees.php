<?php
session_start();
require 'database_connection.php'; // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Handle form submission
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : 0;
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $job_title_id = intval($_POST['job_title_id']);
    $department_id = intval($_POST['department_id']);
    $status = $_POST['status']; // Employee status (Active/Inactive)
    $employment_status = $_POST['employment_status']; // Full-time/Part-time

    if ($employee_id > 0) {
        // Update existing employee
        $sql = "UPDATE employees SET first_name = ?, last_name = ?, job_title_id = ?, department_id = ?, status = ?, employment_status = ?
                WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssiissi', $first_name, $last_name, $job_title_id, $department_id, $status, $employment_status, $employee_id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit(); // Stop script execution after redirect
        } else {
            echo "Error updating employee.";
        }
    } else {
        // Add new employee
        $sql = "INSERT INTO employees (first_name, last_name, job_title_id, department_id, status, employment_status, date_of_joining) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssisss', $first_name, $last_name, $job_title_id, $department_id, $status, $employment_status);

        if ($stmt->execute()) {
            // Redirect to the same page with success message
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit(); // Stop script execution after redirect
        } else {
            echo "Error adding employee.";
        }
    }
}


// Fetch all job titles into an array
$job_titles_array = [];
$job_titles_result = $conn->query("SELECT job_title_id, job_title FROM job_titles");
while ($row = $job_titles_result->fetch_assoc()) {
    $job_titles_array[] = $row;
}

// Fetch all departments into an array
$departments_array = [];
$departments_result = $conn->query("SELECT department_id, department_name FROM departments");
while ($row = $departments_result->fetch_assoc()) {
    $departments_array[] = $row;
}

// Handle employee deletion
if (isset($_GET['delete'])) {
    $employee_id = intval($_GET['delete']);
    
    // Prepare the DELETE statement
    $sql = "DELETE FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $employee_id);
    
    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect with success flag to avoid duplicate deletion on refresh
        header("Location: " . $_SERVER['PHP_SELF'] . "?delete_success=1");
        exit();
    } else {
        // Redirect with error flag
        header("Location: " . $_SERVER['PHP_SELF'] . "?delete_error=1");
        exit();
    }
}

// Fetch all employees
// Fetch all employees with date of joining
$sql = "SELECT e.employee_id, e.first_name, e.last_name, jt.job_title, d.department_name, e.status, e.employment_status, e.date_of_joining 
        FROM employees e
        JOIN job_titles jt ON e.job_title_id = jt.job_title_id
        JOIN departments d ON e.department_id = d.department_id";
$result = $conn->query($sql);

?>

<?php
include 'header.php';
?>



                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Success alert for employee addition -->
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div id="alert" class="alert alert-success" role="alert">
                    Employee added successfully!
                    </div>
                    <?php endif; ?>

                    <!-- General error alert for any errors -->
                    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                    <div id="alert" class="alert alert-danger" role="alert">
                    Error adding employee. Please try again.
                    </div>
                    <?php endif; ?>

                    <!-- Success alert for employee deletion -->
                    <?php if (isset($_GET['delete_success']) && $_GET['delete_success'] == 1): ?>
                    <div id="alert" class="alert alert-danger" role="alert">
                    Employee deleted successfully!
                    </div>
                    <?php endif; ?>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Manage Employees</h1>
                        
                    </div>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <a href="#" data-toggle="modal" data-target="#addEmployee" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <span class="text">Add Employee</span>
                                    </a>
                                    <!-- Filter buttons -->
                        

                        </div>
                        <!-- DataTable to display employees -->
                            <div class="card-body">
                                <!-- Filter buttons -->
                                <div class="btn-group" role="group" aria-label="Employee Filters">
                                    <button type="button" class="btn btn-primary" onclick="filterEmployees('all')">All Employees</button>
                                    <button type="button" class="btn btn-success" onclick="filterEmployees('full-time')">Full-Time</button>
                                    <button type="button" class="btn btn-warning" onclick="filterEmployees('part-time')">Part-Time</button>
                                    
                                </div>
                                

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Job Title</th>
                                                <th>Department</th>
                                                <th>Date Of Joining</th>
                                                <th>Status</th>
                                                <th>Employment Status</th> <!-- Employment status column -->
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="employeeTableBody">
                                            <!-- This section will be updated dynamically using JavaScript -->
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                                                <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                                <td><?php echo date("F j, Y", strtotime($row['date_of_joining'])); ?></td>
                                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                                <td><?php echo htmlspecialchars($row['employment_status']); ?></td> <!-- Display employment status -->
                                                <td>
                                                    <a href="javascript:void(0);" onclick="openModalForEdit(<?php echo $row['employee_id']; ?>)">Edit</a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                    </div>

                </div>
                <!-- /.container-fluid -->

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

    <!-- Add Employee Modal-->
  
<div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="user" action="manage_employees.php" method="POST">
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                placeholder="First Name" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                placeholder="Last Name" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <select class="form-control" id="department_id" name="department_id" required>
                                <option value="" disabled selected>Select Department</option>
                                <!-- Options will be populated dynamically with PHP -->
                                <?php foreach ($departments_array as $department): ?>
                                    <option value="<?php echo $department['department_id']; ?>">
                                        <?php echo $department['department_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control" id="job_title_id" name="job_title_id" required>
                                <option value="" disabled selected>Select Job Title</option>
                                <!-- Job titles will be populated dynamically via JavaScript -->
                            </select>
                        </div>
                    </div>

                    <!-- Employment Status Field -->
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <select class="form-control" id="employment_status" name="employment_status" required>
                                <option value="" disabled selected>Select Employment Status</option>
                                <option value="full-time">Full-time</option>
                                <option value="part-time">Part-time</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <select class="form-control" id="status" name="status" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <input class="btn btn-primary" type="submit" value="Add Employee">
                </form>
            </div>
        </div>
    </div>
</div>






  <!-- Update Employee Modal -->
    <!-- Update Employee Modal -->
        <div class="modal fade" id="updateEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="updateEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateEmployeeModalLabel">Update Employee</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="updateEmployeeForm" method="POST" action="manage_employees.php">
                            <input type="hidden" id="employee_id_update" name="employee_id">

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control" id="first_name_update" name="first_name" placeholder="First Name" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="last_name_update" name="last_name" placeholder="Last Name" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <select class="form-control" id="department_id_update" name="department_id" required>
                                        <option value="">Select Department</option>
                                        <?php foreach ($departments_array as $department): ?>
                                            <option value="<?php echo $department['department_id']; ?>">
                                                <?php echo $department['department_name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <select class="form-control" id="job_title_id_update" name="job_title_id" required>
                                        <option value="">Select Job Title</option>
                                        <!-- Job titles will be populated dynamically via JavaScript -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <select class="form-control" id="employment_status_update" name="employment_status" required>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <select class="form-control" id="status_update" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Employee</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>







    <script>
        // Open the Update Employee Modal and load employee data
        function openModalForEdit(employeeId) {
            fetch('get_employee_data.php?id=' + employeeId)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                    } else {
                        // Populate the modal with employee data
                        document.getElementById('employee_id_update').value = data.employee_id;
                        document.getElementById('first_name_update').value = data.first_name;
                        document.getElementById('last_name_update').value = data.last_name;
                        document.getElementById('salary_update').value = data.salary;

                        // Set the selected option in the job title dropdown
                        let jobTitleDropdown = document.getElementById('job_title_id_update');
                        for (let i = 0; i < jobTitleDropdown.options.length; i++) {
                            if (jobTitleDropdown.options[i].value == data.job_title_id) {
                                jobTitleDropdown.selectedIndex = i;
                                break;
                            }
                        }

                        // Set the selected option in the department dropdown
                        let departmentDropdown = document.getElementById('department_id_update');
                        for (let i = 0; i < departmentDropdown.options.length; i++) {
                            if (departmentDropdown.options[i].value == data.department_id) {
                                departmentDropdown.selectedIndex = i;
                                break;
                            }
                        }

                        // Show the modal
                        $('#updateEmployeeModal').modal('show');
                    }
                })
                .catch(error => {
                    console.error('Error fetching employee data:', error);
                });
        }






    </script>

    <script>
        document.getElementById('department_id').addEventListener('change', function() {
        var departmentId = this.value; // Get the selected department ID
        var jobTitleSelect = document.getElementById('job_title_id'); // Job title dropdown

        // Clear previous job titles
        jobTitleSelect.innerHTML = '<option value="" disabled selected>Select Job Title</option>';

        if (departmentId) {
            // Create an AJAX request to fetch job titles
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'fetch_job_titles.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Handle the response
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var jobTitles = JSON.parse(xhr.responseText);

                        // If there's an error in the response
                        if (jobTitles.error) {
                            console.error(jobTitles.error);
                            return;
                        }

                        // Populate the job title dropdown with the received job titles
                        jobTitles.forEach(function(job) {
                            var option = document.createElement('option');
                            option.value = job.job_title_id;
                            option.textContent = job.job_title;
                            jobTitleSelect.appendChild(option);
                        });
                    } catch (error) {
                        console.error("Error parsing JSON response: ", error);
                    }
                } else {
                    console.error("AJAX error: ", xhr.status);
                }
            };

            // Send the request with the selected department ID
            xhr.send('department_id=' + departmentId);
        }
    });

    document.getElementById('department_id_update').addEventListener('change', function() {
    var departmentId = this.value; // Get the selected department ID
    var jobTitleSelect = document.getElementById('job_title_id_update'); // Job title dropdown

    // Clear previous job titles
    jobTitleSelect.innerHTML = '<option value="" disabled selected>Select Job Title</option>';

    if (departmentId) {
        // Create an AJAX request to fetch job titles
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_job_titles.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Handle the response
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var jobTitles = JSON.parse(xhr.responseText);

                    // If there's an error in the response
                    if (jobTitles.error) {
                        console.error(jobTitles.error);
                        return;
                    }

                    // Populate the job title dropdown with the received job titles
                    jobTitles.forEach(function(job) {
                        var option = document.createElement('option');
                        option.value = job.job_title_id;
                        option.textContent = job.job_title;
                        jobTitleSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error("Error parsing JSON response: ", error);
                }
            } else {
                console.error("AJAX error: ", xhr.status);
            }
        };

        // Send the request with the selected department ID
        xhr.send('department_id=' + departmentId);
    }
});



    </script>

    <script>
        function openModalForEdit(employeeId) {
        // Send AJAX request to fetch employee data
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_employee.php?employee_id=' + employeeId, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                var employee = JSON.parse(xhr.responseText);

                // Populate the modal fields with employee data
                document.getElementById('employee_id_update').value = employee.employee_id;
                document.getElementById('first_name_update').value = employee.first_name;
                document.getElementById('last_name_update').value = employee.last_name;
                document.getElementById('department_id_update').value = employee.department_id;
                document.getElementById('status_update').value = employee.status;

                // Trigger the change event to populate the job titles based on the selected department
                var departmentId = employee.department_id;
                var jobTitleId = employee.job_title_id;

                // Fetch job titles based on the department and preselect the job title
                populateJobTitlesForEdit(departmentId, jobTitleId);

                // Show the modal
                $('#updateEmployeeModal').modal('show');
            }
        };
        xhr.send();
        }

        // Function to populate job titles in the Edit modal
        function populateJobTitlesForEdit(departmentId, selectedJobTitleId) {
            var jobTitleSelect = document.getElementById('job_title_id_update'); // Job title dropdown

            // Clear previous job titles
            jobTitleSelect.innerHTML = '<option value="" disabled selected>Select Job Title</option>';

            if (departmentId) {
                // Create an AJAX request to fetch job titles based on department
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'fetch_job_titles.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                // Handle the response
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var jobTitles = JSON.parse(xhr.responseText);

                            // Populate the job title dropdown with the received job titles
                            jobTitles.forEach(function(job) {
                                var option = document.createElement('option');
                                option.value = job.job_title_id;
                                option.textContent = job.job_title;
                                jobTitleSelect.appendChild(option);

                                // Preselect the correct job title
                                if (job.job_title_id == selectedJobTitleId) {
                                    option.selected = true;
                                }
                            });
                        } catch (error) {
                            console.error("Error parsing JSON response: ", error);
                        }
                    } else {
                        console.error("AJAX error: ", xhr.status);
                    }
                };

                // Send the request with the selected department ID
                xhr.send('department_id=' + departmentId);
            }
        }

        function filterEmployees(filter) {
            // Create an AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'filter_employees.php', true); // Send the request to a new PHP file 'filter_employees.php'
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Update the table with the response
                    document.getElementById('employeeTableBody').innerHTML = xhr.responseText;
                } else {
                    console.error('Error fetching employee data:', xhr.status);
                }
            };

            // Send the selected filter to the server
            xhr.send('filter=' + filter);
        }


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

    <script>
    // Hide the alert after 3 seconds
    setTimeout(function() {
        var alertElement = document.getElementById('alert');
        if (alertElement) {
            alertElement.style.display = 'none';
        }
    }, 3000);

    
    </script>

</body>

</html>


<?php
$conn->close(); // Close the database connection
?>


