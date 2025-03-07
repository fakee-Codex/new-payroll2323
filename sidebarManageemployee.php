<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'gfi_exel');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>

</head>
<body class="bg-light">

    <?php include 'aside.php'; ?>
  
    <main class="flex-1 p-6">
    <div class="container-fluid">
    <div class="mb-4">
        <h2 class="fs-4 fw-semibold text-dark mb-3">Employee List</h2>
        <a href="manage_employees_add.php" class="btn btn-primary mb-3">
            Add Employee
        </a>
    </div>

    <!-- Search Bar -->
    <div class="d-flex justify-content-start mb-3">
        <input type="text" id="searchInput" class="form-control w-25 me-2" placeholder="Search employee..." onkeyup="searchEmployee()">
        <button class="btn btn-primary" onclick="searchEmployee()">Search</button>
    </div>

    <div class="table-responsive bg-white shadow-sm rounded">
        <table id="employeeTable" class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Employee Type</th>
                    <th>Classification</th>
                    <th>Basic Salary</th>
                    <th>F&S Development</th>
                    <th>Incentives</th>
                    <th>Overload Rate</th>
                    <th>Watch Reward</th>
                    <th>Absent / Late Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_fetch = "SELECT * FROM employees";
                $result = $conn->query($sql_fetch);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                              <td>" . $row['last_name'] . ", " . $row['first_name'] . " " . $row['suffix_title'] . "</td>
                              <td>" . ucfirst($row['employee_type']) . "</td>
                              <td>" . $row['classification'] . "</td>
                              <td>₱" . number_format($row['basic_salary'], 2) . "</td>
                              <td>₱" . number_format($row['honorarium'], 2) . "</td>
                              <td>₱" . number_format($row['incentives'], 2) . "</td>
                              <td>₱" . number_format($row['overload_rate'], 2) . "</td>
                              <td>₱" . number_format($row['watch_reward'], 2) . "</td>
                              <td>₱" . number_format($row['absent_lateRate'], 2) . "</td>
                              <td>
                                  <a href='manage_employees_edit.php?id=" . $row['employee_id'] . "' class='btn btn-success btn-sm'>Edit</a>
                              </td>
                          </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10' class='text-center text-muted'>No employees found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchEmployee() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const employeeTable = document.getElementById('employeeTable');
            const rows = employeeTable.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let matchFound = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(searchInput)) {
                        matchFound = true;
                        break;
                    }
                }

                rows[i].style.display = matchFound ? '' : 'none';
            }
        }
    </script>
</div>

    </main>

</body>

</html>
