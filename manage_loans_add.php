<?php

require 'database_connection.php';



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $ret = $_POST['ret'];
    $sss = $_POST['sss'];
    $hdmf_pag = $_POST['hdmf_pag'];


    // Insert query
    $insert_sql = "INSERT INTO loans (employee_id, ret, sss, hdmf_pag) 
                    VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("iddd", $employee_id, $ret, $sss, $hdmf_pag);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success' id='success-alert'>Contribution added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}


// Fetch employees for dropdown
$employees_sql = "SELECT employee_id, CONCAT(last_name, ', ', first_name, ', ',suffix_title) AS Name FROM employees";
$employees_result = $conn->query($employees_sql);

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contribution</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</head>




<body>

<?php include 'aside.php'; ?> <!-- This will import the sidebar -->

    <div class="container mt-5 p-4 bg-white shadow-lg rounded">
        <h3 class="text-center mb-4 text-primary fw-bold">Add Loans</h3>


        <form method="POST" class="p-3">
            <div class="row g-4">
                <!-- Employee Selection -->
                <div class="col-md-6">
                    <label for="employee_id" class="form-label fw-semibold">Employee Name</label>
                    <select name="employee_id" id="employee_id" class="form-select border-primary" required>
                        <option value="">Select Employee</option>
                        <?php
                        if ($employees_result->num_rows > 0) {
                            while ($row = $employees_result->fetch_assoc()) {
                                echo "<option value='{$row['employee_id']}'>{$row['Name']}</option>";
                            }
                        } else {
                            echo "<option value=''>No employees found</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Basic Salary -->
                <div class="col-md-3">
                    <label for="basic_salary" class="form-label fw-semibold">Basic Salary</label>
                    <input type="text" id="basic_salary" class="form-control-sm border-primary form-control" readonly>
                </div>

                <!-- Employee Type -->
                <div class="col-md-3">
                    <label for="employee_type" class="form-label fw-semibold">Employee Type</label>
                    <input type="text" id="employee_type" class="form-control-sm border-primary form-control" readonly>
                </div>



                <!-- loans Section -->
                <div class="mb-4">
                    <h4 class="text-center mb-4 text-primary fw-bold">Credits</h4>

                    <div class="d-flex justify-content-center gap-3">
                        <!-- retirement loan -->
                        <div>
                            <label for="ret" class="form-label fw-semibold">Retirement Loan</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="ret" id="medical_savings"
                                    class="form-control form-control-sm border-primary text-center"
                                    placeholder="retirement" style="max-width: 150px;">
                            </div>
                        </div>

                        <!-- sss loan -->
                        <div>
                            <label for="retirement" class="form-label fw-semibold">SSS Loan</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="sss" id="sss"
                                    class="form-control form-control-sm border-primary text-center"
                                    placeholder="sss" style="max-width: 150px;">
                            </div>
                        </div>

                        <!-- hdmf loan -->
                        <div>
                            <label for="mp2" class="form-label fw-semibold">HDMF Loans</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="hdmf_pag" id="hdmf_pag"
                                    class="form-control form-control-sm border-primary text-center"
                                    placeholder="pag-ibig" style="max-width: 150px;">
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Submit Button -->
                <div class="col-md-12 text-center mt-4">
                    <button type="submit" class="btn btn-success px-4 py-2 fw-bold">Add Contribution</button>
                    <a href="manage_loans.php" class="btn btn-secondary px-4 py-2 fw-bold">Back</a>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        $(document).ready(function() {
            $('#employee_id').change(function() {
                const employeeId = $(this).val();

                if (employeeId) {
                    // Fetch employee and percentage data using AJAX
                    $.ajax({
                        url: 'fetch_employee_details.php', // New PHP file to fetch both employee and percentage data
                        type: 'POST',
                        data: {
                            employee_id: employeeId
                        },
                        success: function(response) {
                            const data = JSON.parse(response);
                            const basicSalary = parseFloat(data.employee.basic_salary);
                         

                            // Populate Basic Salary and Employee Type
                            $('#basic_salary').val(basicSalary.toFixed(2));
                            $('#employee_type').val(data.employee.employee_type);

                        
                        },
                        error: function() {
                            alert('Unable to fetch employee details.');
                        }
                    });
                } else {
                    // Clear fields if no employee selected
                    $('#basic_salary').val('');
                    $('#employee_type').val('');
                 
                }
            });
        });

    </script>
</body>

</html>