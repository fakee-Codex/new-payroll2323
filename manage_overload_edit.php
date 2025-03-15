<?php
// Database connection
$host = 'localhost';
$dbname = 'gfi_exel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the row_id from the query string
    $row_id = isset($_GET['row_id']) ? intval($_GET['row_id']) : 0;

    if ($row_id <= 0) {
        die("Invalid Row ID");
    }

    // Fetch the data for the specified row
    $query = "
        SELECT o.*, CONCAT(e.first_name, ' ', e.last_name) AS employee_name
        FROM overload o
        JOIN employees e ON o.employee_id = e.employee_id
        WHERE o.overload_id = :row_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':row_id' => $row_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        die("Row not found.");
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Adjust width of select elements inside table cells */
        table td select.form-select {
            width: 200px;
            /* Adjust this width as needed */
        }


        /* Add custom width for input elements */
        table td input.form-control {
            width: 90px;
            /* Adjust width as needed */
        }

        button:hover {
            background-color: darkgreen;
            /* Or any color you prefer */
            color: white;
            /* Ensure text remains white when hovered */
            border: none;
            /* Removes any default borders if they appear */
        }

        button.bg-green-500:hover {
            background-color: #32a852;
            /* Custom dark green for green button */
        }

        button.bg-blue-500:hover {
            background-color: #1e3a8a;
            /* Custom dark blue for blue button */
        }


        .table-wrapper {
            max-width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0.5rem;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        td input {
            width: 100%;
            padding: 0.25rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            text-align: center;
        }

        td input:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .save-btn {
            padding: 0.5rem 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .save-btn:hover {
            background-color: #45a049;
        }

        .content {
            padding: 2rem;
            background-color: #f9fafb;
        }
    </style>
</head>

<body>

    <?php include 'aside.php'; ?> <!-- This will import the sidebar -->

    <main>

        <div class="content">
            <h1 class="text-center mb-5">Employee Name: <?= htmlspecialchars($row['employee_name']) ?></h1>

            <form id="editForm">
                <input type="hidden" name="row_id" value="<?= htmlspecialchars($row['overload_id']) ?>">
                <div class="table-wrapper">

                    <table class="table table-bordered text-center">
                        <thead class="table-light">
                            <tr>

                                <th colspan="3" class="table-warning">MWF</th>
                                <th colspan="3" class="table-warning">TTH</th>
                                <th colspan="3" class="table-warning">SS</th>
                                <th colspan="3">MONDAY</th>
                                <th colspan="3">TUESDAY</th>
                                <th colspan="3">Wednesday</th>
                                <th colspan="3">Thursday</th>
                                <th colspan="3">Friday</th>
                                <th colspan="3">SATURDAY</th>
                                <th colspan="3">SUNDAY</th>
                                <th colspan="3">MTTH</th>
                                <th colspan="3">MTWF</th>
                                <th colspan="3">TWTHF</th>
                                <th colspan="3">MW</th>
                                <th rowspan="2">Less</th>
                                <th rowspan="2">Add</th>
                                <th rowspan="2">Adjustments</th>
                                <th rowspan="2">Grand Total</th>
                            </tr>
                            <tr>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                                <th>DAYS</th>
                                <th>HRS</th>
                                <th>TOTAL</th>
                           
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                                <td><input type="number" step="0.01" class="form-control" name="mwf_days" value="<?= htmlspecialchars($row['mwf_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="mwf_hrs" value="<?= htmlspecialchars($row['mwf_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="mwf_total" value="<?= htmlspecialchars($row['mwf_total'] ?? 0) ?>" readonly></td>

                                <td><input type="number" step="0.01" class="form-control" name="tth_days" value="<?= htmlspecialchars($row['tth_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="tth_hrs" value="<?= htmlspecialchars($row['tth_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="tth_total" value="<?= htmlspecialchars($row['tth_total'] ?? 0) ?>" readonly></td>

                                <td><input type="number" step="0.01" class="form-control" name="ss_days" value="<?= htmlspecialchars($row['ss_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="ss_hrs" value="<?= htmlspecialchars($row['ss_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="ss_total" value="<?= htmlspecialchars($row['ss_total'] ?? 0) ?>" readonly></td>

                                <td><input type="number" step="0.01" class="form-control" name="monday_days" value="<?= htmlspecialchars($row['monday_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="monday_hrs" value="<?= htmlspecialchars($row['monday_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="monday_total" value="<?= htmlspecialchars($row['monday_total'] ?? 0) ?>" readonly></td>

                                <td><input type="number" step="0.01" class="form-control" name="tuesday_days" value="<?= htmlspecialchars($row['tuesday_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="tuesday_hrs" value="<?= htmlspecialchars($row['tuesday_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="tuesday_total" value="<?= htmlspecialchars($row['tuesday_total'] ?? 0) ?>" readonly></td>

                                <td><input type="number" step="0.01" class="form-control" name="wednesday_days" value="<?= htmlspecialchars($row['wednesday_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="wednesday_hrs" value="<?= htmlspecialchars($row['wednesday_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="wednesday_total" value="<?= htmlspecialchars($row['wednesday_total'] ?? 0) ?>" readonly></td>


                                <td><input type="number" step="0.01" class="form-control" name="thursday_days" value="<?= htmlspecialchars($row['thursday_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="thursday_hrs" value="<?= htmlspecialchars($row['thursday_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="thursday_total" value="<?= htmlspecialchars($row['thursday_total'] ?? 0) ?>" readonly></td>

                                <td><input type="number" step="0.01" class="form-control" name="friday_days" value="<?= htmlspecialchars($row['friday_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="friday_hrs" value="<?= htmlspecialchars($row['friday_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="friday_total" value="<?= htmlspecialchars($row['friday_total'] ?? 0) ?>" readonly></td>


                                <td><input type="number" step="0.01" class="form-control" name="saturday_days" value="<?= htmlspecialchars($row['saturday_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="saturday_hrs" value="<?= htmlspecialchars($row['saturday_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="saturday_total" value="<?= htmlspecialchars($row['saturday_total'] ?? 0) ?>" readonly></td>


                                <td><input type="number" step="0.01" class="form-control" name="sunday_days" value="<?= htmlspecialchars($row['sunday_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="sunday_hrs" value="<?= htmlspecialchars($row['sunday_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="sunday_total" value="<?= htmlspecialchars($row['sunday_total'] ?? 0) ?>" readonly></td>


                                <td><input type="number" step="0.01" class="form-control" name="mtth_days" value="<?= htmlspecialchars($row['mtth_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="mtth_hrs" value="<?= htmlspecialchars($row['mtth_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="mtth_total" value="<?= htmlspecialchars($row['mtth_total'] ?? 0) ?>" readonly></td>

                                <td><input type="number" step="0.01" class="form-control" name="mtwf_days" value="<?= htmlspecialchars($row['mtwf_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="mtwf_hrs" value="<?= htmlspecialchars($row['mtwf_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="mtwf_total" value="<?= htmlspecialchars($row['mtwf_total'] ?? 0) ?>" readonly></td>

                                <td><input type="number" step="0.01" class="form-control" name="twthf_days" value="<?= htmlspecialchars($row['twthf_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="twthf_hrs" value="<?= htmlspecialchars($row['twthf_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="twthf_total" value="<?= htmlspecialchars($row['twthf_total'] ?? 0) ?>" readonly></td>

                                <td><input type="number" step="0.01" class="form-control" name="mw_days" value="<?= htmlspecialchars($row['mw_days'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="mw_hrs" value="<?= htmlspecialchars($row['mw_hrs'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="mw_total" value="<?= htmlspecialchars($row['mw_total'] ?? 0) ?>" readonly></td>

                                

                                <td><input type="number" step="0.01" class="form-control" name="less_lateOL" value="<?= htmlspecialchars($row['less_lateOL'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="additional" value="<?= htmlspecialchars($row['additional'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control" name="adjustment_less" value="<?= htmlspecialchars($row['adjustment_less'] ?? 0) ?>"></td>
                                <td><input type="number" step="0.01" class="form-control readonly-input" name="grand_total" value="<?= htmlspecialchars($row['grand_total'] ?? 0) ?>"
                                        readonly>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    <button type="button" class="btn" style="background-color: green; color: white; padding: 10px 20px; border-radius: 5px; border: none;" onclick="saveEdits()">Save Changes</button>
                    <a href="manage_overload.php" class="btn" style="background-color: gray; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Cancel</a>
                </div>

        </div>
        </form>
        </div>


    </main>

    <script>
        function updateTotal(input) {
            const row = input.closest("tr");
            const dayColumn = input.name.split("_")[0]; // Extract day (e.g., "wednesday")
            const daysInput = row.querySelector(`input[name="${dayColumn}_days"]`);
            const hrsInput = row.querySelector(`input[name="${dayColumn}_hrs"]`);
            const totalInput = row.querySelector(`input[name="${dayColumn}_total"]`);

            const days = parseFloat(daysInput?.value) || 0;
            const hrs = parseFloat(hrsInput?.value) || 0;
            const total = days * hrs;

            if (totalInput) {
                totalInput.value = total.toFixed(2); // Update the total field (readonly)
            }

            // Update the Grand Total after recalculating individual totals
            updateGrandTotal(row);
        }

        function updateGrandTotal(row) {
            let grandTotal = 0;

            // Sum all the daily totals
            const totals = [
                "mwf_total",
                "tth_total",
                "ss_total",
                "monday_total",
                "tuesday_total",
                "wednesday_total",
                "thursday_total",
                "friday_total",
                "saturday_total",
                "sunday_total",
                "mtth_total",
                "mtwf_total",
                "twthf_total",
                "mw_total"
            ];
            totals.forEach(totalColumn => {
                const totalInput = row.querySelector(`input[name="${totalColumn}"]`);
                grandTotal += parseFloat(totalInput?.value) || 0;
            });

            // Subtract Less, Add Additional, and Subtract Adjustments
            const less = parseFloat(row.querySelector('input[name="less_lateOL"]')?.value) || 0;
            const add = parseFloat(row.querySelector('input[name="additional"]')?.value) || 0;
            const adjustments = parseFloat(row.querySelector('input[name="adjustment_less"]')?.value) || 0;

            grandTotal = grandTotal - less + add - adjustments;

            // Update the Grand Total field (readonly)
            const grandTotalInput = row.querySelector('input[name="grand_total"]');
            if (grandTotalInput) {
                grandTotalInput.value = grandTotal.toFixed(2);
            }
        }

        // Attach event listeners to all relevant inputs
        document.querySelectorAll(`
    input[name$="_days"],
    input[name$="_hrs"],
    input[name="less_lateOL"],
    input[name="additional"],
    input[name="adjustment_less"]
`).forEach(input => {
            input.addEventListener("input", () => {
                updateTotal(input); // Update totals and grand total dynamically
            });
        });



        function saveEdits() {
            const form = document.getElementById("editForm");
            const formData = new FormData(form);

            fetch("manage_overload_update.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert("Changes saved successfully!");
                        window.location.href = "manage_overload.php";
                    } else {
                        alert("Failed to save changes: " + result.error);
                    }
                })
                .catch(error => {
                    console.error("Error saving changes:", error);
                });
        }
    </script>


</body>

</html>