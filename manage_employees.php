<?php
session_start();
require 'database_connection.php'; // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : 0;
    $employee_id_number = $_POST['employee_id_number']; // Employee ID Number
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $job_title_id = intval($_POST['job_title_id']);
    $department_id = intval($_POST['department_id']);
    $status = $_POST['status']; // Employee status (Active/Inactive)
    $employment_status = $_POST['employment_status']; // Full-time/Part-time
    $reason = isset($_POST['reason']) ? $_POST['reason'] : null; // Reason for inactive status

    if ($employee_id > 0) {
        // Update employee logic
        if ($status == 'inactive') {
            $date_inactive = date('Y-m-d'); // Current date for when employee becomes inactive
            $sql = "UPDATE employees SET first_name = ?, last_name = ?, job_title_id = ?, department_id = ?, status = ?, employment_status = ?, reason = ?, date_inactive = ?, employee_id_number = ? WHERE employee_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssiisssssi', $first_name, $last_name, $job_title_id, $department_id, $status, $employment_status, $reason, $date_inactive, $employee_id_number, $employee_id);
        } else {
            // When active, reset the `date_inactive` and `reason` fields to NULL
            $sql = "UPDATE employees SET first_name = ?, last_name = ?, job_title_id = ?, department_id = ?, status = ?, employment_status = ?, reason = NULL, date_inactive = NULL, employee_id_number = ? WHERE employee_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssiisssi', $first_name, $last_name, $job_title_id, $department_id, $status, $employment_status, $employee_id_number, $employee_id);
        }
    } else {
        // Add new employee logic (INSERT INTO employees)
        $sql = "INSERT INTO employees (employee_id_number, first_name, last_name, job_title_id, department_id, status, employment_status, date_of_joining) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssisss', $employee_id_number, $first_name, $last_name, $job_title_id, $department_id, $status, $employment_status);
    }

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit(); // Stop script execution after redirect
    } else {
        echo "Error updating or adding employee.";
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

// Fetch only active employees
$sql = "SELECT e.employee_id, e.employee_id_number, e.first_name, e.last_name, jt.job_title, d.department_name, e.status, e.employment_status, e.date_of_joining 
        FROM employees e
        JOIN job_titles jt ON e.job_title_id = jt.job_title_id
        JOIN departments d ON e.department_id = d.department_id
        WHERE e.status = 'active'"; // Fetch only active employees
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
                                    <a href="inactive_employees.php"><button type="button" class="btn btn-danger">Inactive Employees</button></a>
                                    
                                </div>
                                

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th style="display:none;"></th>
                                                <th>ID Number</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Job Title</th>
                                                <th>Department</th>
                                                <th>Date Hired</th>
                                                <th>Status</th>
                                                <th>Employment Status</th> <!-- Employment status column -->
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="employeeTableBody">
                                            <!-- This section will be updated dynamically using JavaScript -->
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td style="display:none;"><?php echo htmlspecialchars($row['employee_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['employee_id_number']); ?></td>
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
                            <input type="text" class="form-control" id="employee_id_number" name="employee_id_number"
                                placeholder="Employee ID Number" required>
                        </div>
                    </div>

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
                        <select class="form-control" id="status" name="status" required style="pointer-events: none;">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <p> * Active as Default </p>
                            
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
                        <input type="text" class="form-control" id="employee_id_number_update" name="employee_id_number"
                            placeholder="Employee ID Number" required>
                    </div>
                </div>


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

                    <!-- Employment Status Field -->
                    <div class="form-group">
                        <select class="form-control" id="employment_status_update" name="employment_status" required>
                            <option value="full-time">Full-time</option>
                            <option value="part-time">Part-time</option>
                        </select>
                    </div>

                    <!-- Status Field (Active/Inactive) -->
                    <div class="form-group">
                        <select class="form-control" id="status_update" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Reason Field (Disabled by default) -->
                    <div class="form-group">
                        <label for="reason">Reason for Inactive (if applicable)</label>
                        <input type="text" class="form-control" id="reason_update" name="reason" placeholder="Reason" disabled>
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
                        document.getElementById('employee_id_number_update').value = employee.employee_id_number;
                        document.getElementById('first_name_update').value = data.first_name;
                        document.getElementById('last_name_update').value = data.last_name;

                        // Preselect department
                        var departmentDropdown = document.getElementById('department_id_update');
                        for (var i = 0; i < departmentDropdown.options.length; i++) {
                            if (departmentDropdown.options[i].value == data.department_id) {
                                departmentDropdown.selectedIndex = i;
                                break;
                            }
                        }

                        // Preselect job title
                        populateJobTitles(data.department_id, data.job_title_id);

                        // Preselect employment status
                        document.getElementById('employment_status_update').value = data.employment_status;

                        // Preselect status and handle reason field
                        var statusDropdown = document.getElementById('status_update');
                        statusDropdown.value = data.status;
                        var reasonField = document.getElementById('reason_update');

                        if (data.status === 'inactive') {
                            reasonField.disabled = false;
                            reasonField.value = data.reason || ''; // Populate reason if it's already stored in the database
                        } else {
                            reasonField.disabled = true;
                            reasonField.value = ''; // Clear the reason field if active
                        }

                        // Add an event listener to dynamically enable/disable the reason field based on status change
                        statusDropdown.addEventListener('change', function() {
                            if (this.value === 'inactive') {
                                reasonField.disabled = false;
                            } else {
                                reasonField.disabled = true;
                                reasonField.value = ''; // Clear the reason field if status is set to active
                            }
                        });

                        // Show the modal
                        $('#updateEmployeeModal').modal('show');
                    }
                })
                .catch(error => {
                    console.error('Error fetching employee data:', error);
                });
        }




        document.getElementById('status_update').addEventListener('change', function() {
    var status = this.value;
    var reasonField = document.getElementById('reason_update');

    // Enable or disable the reason field based on the selected status
    if (status === 'inactive') {
        reasonField.disabled = false; // Enable the field if the status is inactive
        reasonField.setAttribute('required', 'required'); // Make it required
    } else {
        reasonField.disabled = true;  // Disable the field if the status is active
        reasonField.removeAttribute('required'); // Remove required attribute
        reasonField.value = ''; // Clear the field
    }
});

// Populate modal data when editing an employee
function openModalForEdit(employeeId) {
    fetch('get_employee_data.php?id=' + employeeId)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
            } else {
                // Populate the modal with employee data
                document.getElementById('employee_id_update').value = data.employee_id;
                document.getElementById('employee_id_number_update').value = data.employee_id_number;
                document.getElementById('first_name_update').value = data.first_name;
                document.getElementById('last_name_update').value = data.last_name;
                document.getElementById('status_update').value = data.status;
                document.getElementById('employment_status_update').value = data.employment_status;

                // Check if the employee is inactive, if so, enable the reason field and fill in the reason
                var reasonField = document.getElementById('reason_update');
                if (data.status === 'inactive') {
                    reasonField.disabled = false; // Enable the reason field
                    reasonField.value = data.reason || ''; // Set the reason if it exists
                } else {
                    reasonField.disabled = true; // Disable the reason field for active status
                    reasonField.value = ''; // Clear the reason field
                }

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
                document.getElementById('employee_id_number_update').value = employee.employee_id_number;
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
        xhr.open('POST', 'filter_employees.php', true); // Send the request to 'filter_employees.php'
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Destroy the existing DataTable instance before updating the table content
                $('#dataTable').DataTable().destroy();

                // Update the table content
                document.getElementById('employeeTableBody').innerHTML = xhr.responseText;

                // Re-initialize DataTable to apply sorting, search, and pagination to the new data
                $('#dataTable').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true
                });
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


