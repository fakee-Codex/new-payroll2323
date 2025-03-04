<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslips</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .header {

            text-align: center;
        }
    </style>
    <script>
        function toggleSelectAll(source) {
            checkboxes = document.getElementsByName('selected_employees[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>


</head>

<body>
    <?php include 'aside.php'; ?>

    <main>
    <div class="container mt-4">
        <form method="POST" id="payslipForm" action="generate_payslip.php" target="_blank">
            <div class="header">
                <h1 style="font-size: 36px;">Generate Payslips</h1>
                <h3 style="font-size: 24px;">Period 1-15</h3>
            </div>

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th><input type="checkbox" onclick="toggleSelectAll(this)"> Select All Employees</th>
                        <th>Employee Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "gfi_exel");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $query = "SELECT DISTINCT e.employee_id, CONCAT_WS(' ', e.last_name, e.first_name, e.suffix_title) AS name 
                              FROM employees e";

                    $result = $conn->query($query);

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td><input type='checkbox' name='selected_employees[]' value='{$row['employee_id']}'></td>
                            <td>{$row['name']}</td>
                          </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary" onclick="setFormAction('generate_payslip.php')">
                Generate Payslips 1-15
            </button>

            <button type="submit" class="btn btn-primary" onclick="setFormAction('generate_payslip2.php')">
                Overload & Watch
            </button>
        </form>
    </div>
</main>

<script>
    function toggleSelectAll(source) {
        let checkboxes = document.querySelectorAll('input[name="selected_employees[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = source.checked);
    }

    function setFormAction(action) {
        document.getElementById('payslipForm').action = action;
    }
</script>

</body>

</html>