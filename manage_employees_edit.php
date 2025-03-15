<?php
$conn = new mysqli('localhost', 'root', '', 'gfi_exel');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee = null; // Declare a variable to hold the fetched employee data
if (isset($_GET['id'])) {
    // Capture the employee_id from the URL
    $employee_id = $conn->real_escape_string($_GET['id']);

    // Fetch the employee data based on the employee_id passed in the URL
    $result = $conn->query("SELECT * FROM employees WHERE employee_id = '$employee_id'");
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    } else {
        echo "Employee not found.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form data and update the employee record
    $employee_id = $_POST['employee_id'];
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $suffix_title = $conn->real_escape_string($_POST['suffix_title']);
    $employee_type = $conn->real_escape_string($_POST['employee_type']);
    $classification = $conn->real_escape_string($_POST['classification']);
    $basic_salary = $conn->real_escape_string($_POST['basic_salary']);
    $honorarium = $conn->real_escape_string($_POST['honorarium']);
    $incentives = $conn->real_escape_string($_POST['incentives']);
    $overload_rate = $conn->real_escape_string($_POST['overload_rate']);
    $watch_reward = $conn->real_escape_string($_POST['watch_reward']);
    $absent_lateRate = $basic_salary / 13 / 8;

    // Update the employee record in the database
    $sql = "UPDATE employees SET 
            first_name = '$first_name', 
            last_name = '$last_name',
            suffix_title = '$suffix_title',  
            employee_type = '$employee_type', 
            classification = '$classification', 
            basic_salary = '$basic_salary', 
            honorarium = '$honorarium', 
            incentives = '$incentives', 
            overload_rate = '$overload_rate', 
            watch_reward = '$watch_reward', 
            absent_lateRate = '$absent_lateRate' 
            WHERE employee_id = '$employee_id'";

    $response = array(); // Response array for JSON output

    if ($conn->query($sql) === TRUE) {
        // Success response
        $response['success'] = true;
        $response['message'] = 'Employee updated successfully!';
    } else {
        // Error response
        $response['success'] = false;
        $response['message'] = 'Error: ' . $conn->error;
    }

    // Return response as JSON
    echo json_encode($response);
    exit(); // End the script after outputting the response
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">

   

</head>

<body class="bg-gray-100">
    <?php include 'aside.php'; ?>
    <main class="flex-grow-1 p-3 text-white">
        <div class="container bg-white text-black mt-5">
            <h1 class="h3 text-center fw-bold mb-4">Edit Employee</h1>

            <!-- Back Button -->
            <div class="mb-3">
                <a href="sidebarManageemployee.php" class="btn btn-primary">Back</a>
            </div>

            <!-- Form -->
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">

                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-md-6">
                        <fieldset class="border border-2 p-3 rounded">
                            <legend class="fw-semibold">Personal Information</legend>
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" value="<?php echo isset($employee['first_name']) ? $employee['first_name'] : ''; ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" value="<?php echo isset($employee['last_name']) ? $employee['last_name'] : ''; ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Suffix / Title</label>
                                <input type="text" name="suffix_title" value="<?php echo isset($employee['suffix_title']) ? $employee['suffix_title'] : ''; ?>" class="form-control">
                            </div>
                        </fieldset>
                    </div>

                    <!-- Job Information -->
                    <div class="col-md-6">
                        <fieldset class="border border-2 p-3 rounded">
                            <legend class="fw-semibold">Job Information</legend>
                            <div class="mb-3">
                                <label class="form-label">Employee Type</label>
                                <select name="employee_type" class="form-select">
                                    <option value="full-time" <?php echo (isset($employee['employee_type']) && $employee['employee_type'] == 'full-time') ? 'selected' : ''; ?>>Full-Time</option>
                                    <option value="part-time" <?php echo (isset($employee['employee_type']) && $employee['employee_type'] == 'part-time') ? 'selected' : ''; ?>>Part-Time</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Job Position</label>
                                <input type="text" name="classification" value="<?php echo isset($employee['classification']) ? $employee['classification'] : ''; ?>" class="form-control">
                            </div>
                        </fieldset>
                    </div>
                </div>

                <!-- Salary Details -->
                <fieldset class="border border-2 p-3 rounded mt-3">
                    <legend class="fw-semibold text-center">Salary Details</legend>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Basic Salary</label>
                            <input type="number" name="basic_salary" id="edit_basic_salary" value="<?php echo isset($employee['basic_salary']) ? $employee['basic_salary'] : ''; ?>" class="form-control" oninput="calculateAbsentLateRate(this)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">F&S Development</label>
                            <input type="number" name="honorarium" value="<?php echo isset($employee['honorarium']) ? $employee['honorarium'] : ''; ?>" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Incentives</label>
                            <input type="number" name="incentives" value="<?php echo isset($employee['incentives']) ? $employee['incentives'] : ''; ?>" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Overload Rate</label>
                            <input type="number" name="overload_rate" value="<?php echo isset($employee['overload_rate']) ? $employee['overload_rate'] : ''; ?>" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Watch Reward</label>
                            <input type="number" name="watch_reward" value="<?php echo isset($employee['watch_reward']) ? $employee['watch_reward'] : ''; ?>" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Absent/Late Rate</label>
                            <input type="number" name="absent_lateRate" id="edit_absent_lateRate" value="<?php echo isset($employee['absent_lateRate']) ? $employee['absent_lateRate'] : ''; ?>" class="form-control" readonly step="0.01">
                        </div>
                    </div>
                </fieldset>

                <!-- Submit Button -->
                <button type="submit" name="update" class="btn btn-primary w-100 mt-3">Save Changes</button>
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
                const absentLateField = document.querySelector('[name="absent_lateRate"]');

                // Update absentLateRate with the calculated value
                absentLateField.value = rate.toFixed(2);
            } else {
                // If basic salary is invalid, clear the absentLateRate field
                const absentLateField = document.querySelector('[name="absent_lateRate"]');
                absentLateField.value = '';
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
                    if (data.success) {
                        alert(data.message); // Show success message
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