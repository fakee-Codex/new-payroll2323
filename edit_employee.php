<?php
session_start();
require 'database_connection.php'; // Include the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Get the employee ID from the URL
if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']);
} else {
    echo "No employee ID provided!";
    exit;
}

// Fetch employee details from the database
$sql = "SELECT * FROM employees WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $employee = $result->fetch_assoc();
} else {
    echo "Employee not found!";
    exit;
}

// Fetch job titles and departments for the dropdowns
$job_titles = $conn->query("SELECT * FROM job_titles");
$departments = $conn->query("SELECT * FROM departments");

// Handle the form submission for updating employee details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $job_title_id = intval($_POST['job_title_id']);
    $department_id = intval($_POST['department_id']);
    $salary = floatval($_POST['salary']);

    // Update employee details in the database
    $sql = "UPDATE employees SET first_name = ?, last_name = ?, job_title_id = ?, department_id = ?, salary = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssiiii', $first_name, $last_name, $job_title_id, $department_id, $salary, $employee_id);

    if ($stmt->execute()) {
        echo "Employee updated successfully!";
    } else {
        echo "Error updating employee.";
    }

    // Fetch the updated employee details
    $sql = "SELECT * FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc(); // Update the employee array with the new values
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
</head>
<body>

<h1>Edit Employee</h1>

<form action="edit_employee.php?id=<?php echo $employee['employee_id']; ?>" method="POST">
    <label for="first_name">First Name:</label><br>
    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required><br>

    <label for="last_name">Last Name:</label><br>
    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required><br>

    <label for="job_title_id">Job Title:</label><br>
    <select id="job_title_id" name="job_title_id" required>
        <?php while ($job = $job_titles->fetch_assoc()): ?>
            <option value="<?php echo $job['job_title_id']; ?>" <?php if ($employee['job_title_id'] == $job['job_title_id']) echo 'selected'; ?>>
                <?php echo $job['job_title']; ?>
            </option>
        <?php endwhile; ?>
    </select><br>

    <label for="department_id">Department:</label><br>
    <select id="department_id" name="department_id" required>
        <?php while ($department = $departments->fetch_assoc()): ?>
            <option value="<?php echo $department['department_id']; ?>" <?php if ($employee['department_id'] == $department['department_id']) echo 'selected'; ?>>
                <?php echo $department['department_name']; ?>
            </option>
        <?php endwhile; ?>
    </select><br>

    <label for="salary">Salary:</label><br>
    <input type="number" id="salary" name="salary" step="0.01" value="<?php echo htmlspecialchars($employee['salary']); ?>" required><br>

    <input type="submit" value="Update Employee">
</form>

<a href="manage_employees.php">Back to Employee Management</a>

</body>
</html>

<?php
$conn->close();
?>
