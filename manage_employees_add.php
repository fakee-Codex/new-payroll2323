<?php
require 'database_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize response array
    $response = ['success' => false, 'message' => ''];

    try {
        // Loop through the form data (assuming you're adding multiple employees)
        foreach ($_POST['first_name'] as $i => $first_name) {
            // Sanitize and escape the form inputs
            $first_name = $conn->real_escape_string($first_name);
            $last_name = $conn->real_escape_string($_POST['last_name'][$i]);
            $suffix_title = $conn->real_escape_string($_POST['suffix_title'][$i]);
            $employee_type = $conn->real_escape_string($_POST['employee_type'][$i]);
            $classification = $conn->real_escape_string($_POST['classification'][$i]);
            $basic_salary = $conn->real_escape_string($_POST['basic_salary'][$i]);
            $honorarium = $conn->real_escape_string($_POST['honorarium'][$i]);
            $incentives = $conn->real_escape_string($_POST['incentives'][$i]);
            $overload_rate = $conn->real_escape_string($_POST['overload_rate'][$i]);
            $watch_reward = $conn->real_escape_string($_POST['watch_reward'][$i]);
            $absent_lateRate = $basic_salary / 13 / 8;

            // Insert the data into the database
            $stmt = $conn->prepare("INSERT INTO employees (first_name, last_name, suffix_title, employee_type, classification, basic_salary, honorarium, incentives, overload_rate, watch_reward, absent_lateRate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssdddddd", $first_name, $last_name, $suffix_title, $employee_type, $classification, $basic_salary, $honorarium, $incentives, $overload_rate, $watch_reward, $absent_lateRate);
            $stmt->execute();
        }

        // Success response
        $response['success'] = true;
        $response['message'] = 'Employee added successfully!';
    } catch (Exception $e) {
        // Error response
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    // Return response as JSON
    echo json_encode($response);
    exit(); // End the script
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">



</head>

<body class="bg-gray-100">
    <?php include 'aside.php'; ?>
    <main class="flex-grow-1 p-3  text-white">

        <div class="container bg-white mt-5">
            <h1 class="h2 fw-bold text-black text-center">Employee Form</h1>

            <!-- Back Button -->
            <div class="text-left mb-4">
                <a href="sidebarManageemployee.php" class="btn btn-primary">Back</a>
            </div>

            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-md-6 text-black">
                        <fieldset class="border border-3 p-4 rounded">
                            <legend class="h5 fw-semibold">Personal Information</legend>
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name[]" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name[]" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Suffix / Title</label>
                                <input type="text" name="suffix_title[]" placeholder="e.g. Sr, LPT" class="form-control">
                            </div>
                        </fieldset>
                    </div>

                    <!-- Job Information -->
                    <div class="col-md-6 text-black">
                        <fieldset class="border border-3 p-4 rounded">
                            <legend class="h5 fw-semibold">Job Information</legend>
                            <div class="mb-3">
                                <label class="form-label">Employee Type</label>
                                <select name="employee_type[]" class="form-select" required>
                                    <option value="full-time">Full-Time</option>
                                    <option value="part-time">Part-Time</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Job Position</label>
                                <input type="text" name="classification[]" placeholder="e.g. teacher, staff." class="form-control" required>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <!-- Salary Section -->
                <fieldset class="border border-3 p-4 rounded mt-4 text-black">
                    <legend class="h5 fw-semibold text-center">Salary Details</legend>
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Basic Salary</label>
                            <input type="number" name="basic_salary[]" class="form-control" required oninput="calculateAbsentLateRate(this)">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">F&S Development</label>
                            <input type="number" name="honorarium[]" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Incentives</label>
                            <input type="number" name="incentives[]" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Overload Rate</label>
                            <input type="number" name="overload_rate[]" class="form-control" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Watch Reward</label>
                            <input type="number" name="watch_reward[]" class="form-control" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Absent/Late Rate</label>
                            <input type="number" name="absent_lateRate[]" class="form-control" readonly>
                        </div>
                    </div>
                </fieldset>

                <!-- Submit Button -->
                <button type="submit" name="add" class="btn btn-primary w-100 mt-3">Submit</button>
            </form>
        </div>



    </main>

    <script>
        // Function to calculate the absent/late rate
        function calculateAbsentLateRate(input) {
            const basicSalary = parseFloat(input.value);
            if (!isNaN(basicSalary) && basicSalary > 0) {
                const rate = (basicSalary / 13) / 8;
                // Find the corresponding absentLateRate field
                const absentLateField = input.closest('form').querySelector('[name="absent_lateRate[]"]');
                absentLateField.value = rate.toFixed(2); // Update absentLateRate with calculated value
            }
        }

        // Function to handle the form submission via AJAX
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            let formData = new FormData(this); // Create FormData object from the form
            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) // Expect a JSON response
                .then(data => {
                    console.log(data); // Debugging line to inspect the response in the console
                    if (data.success) {
                        alert('Employee added successfully!');
                        // Redirect to sidebarManageemployee.php after success
                        window.location.href = "sidebarManageemployee.php"; // Redirect after successful submission
                    } else {
                        alert('Error: ' + data.message); // Show error message if something went wrong
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred, please try again.');
                });
        });
    </script>

</body>

</html>