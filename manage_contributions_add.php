<?php

require 'database_connection.php';

// Fetch existing data
$sql = "SELECT * FROM contribution_percentages ORDER BY percentage_id DESC LIMIT 1";
$result = $conn->query($sql);
$current_data = null;
if ($result->num_rows > 0) {
    $current_data = $result->fetch_assoc();
}


// Check if POST data exists before using it
if (isset($_POST['sss_ee_percentage']) && isset($_POST['sss_er_percentage']) && isset($_POST['philhealth_ee_percentage']) && isset($_POST['philhealth_er_percentage'])) {
    // Get POST data
    $sss_ee_percentage = $_POST['sss_ee_percentage'];
    $sss_er_percentage = $_POST['sss_er_percentage'];
    $philhealth_ee_percentage = $_POST['philhealth_ee_percentage'];
    $philhealth_er_percentage = $_POST['philhealth_er_percentage'];

    if ($current_data) {
        // If existing data found, update it
        $sql = "UPDATE contribution_percentages SET sss_ee_percentage = '$sss_ee_percentage', sss_er_percentage = '$sss_er_percentage', philhealth_ee_percentage = '$philhealth_ee_percentage', philhealth_er_percentage = '$philhealth_er_percentage' WHERE percentage_id = " . $current_data['percentage_id'];
    } else {
        // No existing data, insert new
        $sql = "INSERT INTO contribution_percentages (sss_ee_percentage, sss_er_percentage, philhealth_ee_percentage, philhealth_er_percentage)
        VALUES ('$sss_ee_percentage', '$sss_er_percentage', '$philhealth_ee_percentage', '$philhealth_er_percentage')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Percentage saved successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $sss_ee = $_POST['sss_ee'];
    $pagibig_ee = $_POST['pagibig_ee'];
    $philhealth_ee = $_POST['philhealth_ee'];
    $sss_er = $_POST['sss_er'];
    $pagibig_er = $_POST['pagibig_er'];
    $philhealth_er = $_POST['philhealth_er'];
    $medical_savings = $_POST['medical_savings'];
    $retirement = $_POST['retirement'];
    $mp2 = $_POST['mp2'];

    // Insert query
    $insert_sql = "INSERT INTO contributions (employee_id, sss_ee, pag_ibig_ee, philhealth_ee, sss_er, pag_ibig_er, philhealth_er, medical_savings, retirement, mp2)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("iddddddddd", $employee_id, $sss_ee, $pagibig_ee, $philhealth_ee, $sss_er, $pagibig_er, $philhealth_er, $medical_savings, $retirement, $mp2);

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
        <h3 class="text-center mb-4 text-primary fw-bold">Add Contribution</h3>

        <!-- Modal Button to Trigger -->
        <div class="col-md-12 mt-4 text-end">
            <button type="button" class="btn btn-warning px-4 py-2 fw-bold rounded-pill" data-bs-toggle="modal" data-bs-target="#modalId">
                Set Percentage
            </button>
        </div>

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

                <!-- Employer Share Column -->
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded shadow">
                        <h5 class="text-center text-success fw-bold">Employer Share</h5>

                        <!-- SSS Employer Share -->
                        <div class="mb-3">
                            <label for="sss_er" class="form-label fw-semibold">SSS (Employer Share)</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="sss_er" id="sss_er" class="form-control form-control-sm border-primary" placeholder="Enter SSS contribution" required>
                            </div>
                        </div>

                        <!-- Pag-ibig Employer Share -->
                        <div class="mb-3">
                            <label for="pagibig_er" class="form-label fw-semibold">Pag-ibig (Employer Share)</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="pagibig_er" id="pagibig_er" class="form-control form-control-sm border-primary" value="200" required>
                            </div>
                        </div>

                        <!-- PhilHealth Employer Share -->
                        <div class="mb-3">
                            <label for="philhealth_er" class="form-label fw-semibold">PhilHealth (Employer Share)</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="philhealth_er" id="philhealth_er" class="form-control form-control-sm border-primary" placeholder="Enter PhilHealth contribution" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employee Share Column -->
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded shadow">
                        <h5 class="text-center text-primary fw-bold">Employee Share</h5>

                        <!-- SSS Employee Share -->
                        <div class="mb-3">
                            <label for="sss_ee" class="form-label fw-semibold">SSS (Employee Share)</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="sss_ee" id="sss_ee" class="form-control form-control-sm border-primary" placeholder="Enter SSS contribution" required>
                            </div>
                        </div>

                        <!-- Pag-ibig Employee Share -->
                        <div class="mb-3">
                            <label for="pagibig_ee" class="form-label fw-semibold">Pag-ibig (Employee Share)</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="pagibig_ee" id="pagibig_ee" class="form-control form-control-sm border-primary" value="200" required>
                            </div>
                        </div>

                        <!-- PhilHealth Employee Share -->
                        <div class="mb-3">
                            <label for="philhealth_ee" class="form-label fw-semibold">PhilHealth (Employee Share)</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="philhealth_ee" id="philhealth_ee" class="form-control form-control-sm border-primary" placeholder="Enter PhilHealth contribution" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Savings Section -->
                <div class="mb-4">
                    <h4 class="text-center mb-4 text-primary fw-bold">Savings</h4>

                    <div class="d-flex justify-content-center gap-3">
                        <!-- Medical Savings Contribution -->
                        <div>
                            <label for="medical_savings" class="form-label fw-semibold">Medical</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="medical_savings" id="medical_savings"
                                    class="form-control form-control-sm border-primary text-center"
                                    placeholder="Medical" style="max-width: 150px;">
                            </div>
                        </div>

                        <!-- Retirement Contribution -->
                        <div>
                            <label for="retirement" class="form-label fw-semibold">Retirement</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="retirement" id="retirement"
                                    class="form-control form-control-sm border-primary text-center"
                                    placeholder="Retirement" style="max-width: 150px;">
                            </div>
                        </div>

                        <!-- mp2 Contribution -->
                        <div>
                            <label for="mp2" class="form-label fw-semibold">MP2</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" name="mp2" id="mp2"
                                    class="form-control form-control-sm border-primary text-center"
                                    placeholder="mp2" style="max-width: 150px;">
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Submit Button -->
                <div class="col-md-12 text-center mt-4">
                    <button type="submit" class="btn btn-success px-4 py-2 fw-bold">Submit</button>
                    <a href="manage_contributions.php" class="btn btn-secondary px-4 py-2 fw-bold">Back</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal structure -->
    <div class="modal fade" id="modalId" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Set Contribution Percentage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="percentageForm">
                        <div class="mb-3">
                            <label for="sss_ee_modal" class="form-label">SSS EE Rate (%)</label>
                            <input type="number" class="form-control" id="sss_ee_modal" required>
                        </div>
                        <div class="mb-3">
                            <label for="sss_er_modal" class="form-label">SSS ER Rate (%)</label>
                            <input type="number" class="form-control" id="sss_er_modal" required>
                        </div>
                        <div class="mb-3">
                            <label for="philhealth_ee_modal" class="form-label">PhilHealth EE Rate (%)</label>
                            <input type="number" class="form-control" id="philhealth_ee_modal" required>
                        </div>
                        <div class="mb-3">
                            <label for="philhealth_er_modal" class="form-label">PhilHealth ER Rate (%)</label>
                            <input type="number" class="form-control" id="philhealth_er_modal" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePercentageBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>
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
                            const percentages = data.percentages;

                            // Populate Basic Salary and Employee Type
                            $('#basic_salary').val(basicSalary.toFixed(2));
                            $('#employee_type').val(data.employee.employee_type);

                            // Calculate and populate contributions using dynamic percentage values
                            if (!isNaN(basicSalary)) {
                                const sssEr = basicSalary * (percentages.sss_er_percentage / 100);
                                $('#sss_er').val(sssEr.toFixed(2));
                                const sssEe = basicSalary * (percentages.sss_ee_percentage / 100);
                                $('#sss_ee').val(sssEe.toFixed(2));
                                const philhealthEr = basicSalary * (percentages.philhealth_er_percentage / 100);
                                const philhealthEe = basicSalary * (percentages.philhealth_ee_percentage / 100);
                                $('#philhealth_er').val(philhealthEr.toFixed(2));
                                $('#philhealth_ee').val(philhealthEe.toFixed(2));
                            }
                        },
                        error: function() {
                            alert('Unable to fetch employee details.');
                        }
                    });
                } else {
                    // Clear fields if no employee selected
                    $('#basic_salary').val('');
                    $('#employee_type').val('');
                    $('#sss_er').val('');
                    $('#sss_ee').val('');
                    $('#philhealth_er').val('');
                    $('#philhealth_ee').val('');
                }
            });
        });


        $(document).ready(function() {
            // Fetch existing data and pre-fill form
            $.ajax({
                url: 'get_percentage_data.php', // A script to fetch data
                method: 'GET',
                success: function(data) {
                    if (data) {
                        var percentageData = JSON.parse(data);
                        $('#sss_ee_modal').val(percentageData.sss_ee_percentage);
                        $('#sss_er_modal').val(percentageData.sss_er_percentage);
                        $('#philhealth_ee_modal').val(percentageData.philhealth_ee_percentage);
                        $('#philhealth_er_modal').val(percentageData.philhealth_er_percentage);
                    }
                }
            });

            $('#savePercentageBtn').click(function() {
                var sss_ee_percentage = $('#sss_ee_modal').val();
                var sss_er_percentage = $('#sss_er_modal').val();
                var philhealth_ee_percentage = $('#philhealth_ee_modal').val();
                var philhealth_er_percentage = $('#philhealth_er_modal').val();

                // AJAX request to save or update the data
                $.ajax({
                    url: window.location.href, // This sends the request to the current page 
                    type: 'POST',
                    data: {
                        sss_ee_percentage: sss_ee_percentage,
                        sss_er_percentage: sss_er_percentage,
                        philhealth_ee_percentage: philhealth_ee_percentage,
                        philhealth_er_percentage: philhealth_er_percentage
                    },
                    success: function(response) {
                        alert('Percentage has been set successfully!');
                        $('#modalId').modal('hide'); // Hide the modal after success

                        // Reload the page after 1 second (optional delay)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function() {
                        alert('Error saving data.');
                    }
                });

            });
        });
    </script>
</body>

</html>