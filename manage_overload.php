<?php
// Database connection
$host = 'localhost';
$dbname = 'gfi_exel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch overload data along with employee names
    $query = "
        SELECT 
            o.*, 
            CONCAT(e.last_name, ' ', e.first_name, ' ', e.suffix_title) AS employee_name
        FROM overload o
        JOIN employees e ON o.employee_id = e.employee_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $overloadData = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">



    <style>
        /* Custom Tailwind style for table and button */
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

        .editable-cell {
            text-align: center;
            width: 80px;
        }

        /* Styling for error input fields */
        .error-input {
            border-color: #ff0000;
        }

        .content {
            padding: 2rem;
            background-color: #f9fafb;
        }

        h1 {
            font-size: 1.75rem;
            color: #333;
        }
    </style>
</head>

<body>

    <?php include 'aside.php'; ?> <!-- This will import the sidebar -->
    <style>
        /* Sticky for the first column horizontally */
        #crudTable td:first-child {
            position: sticky;
            left: 0;
            background-color: #fff;
            /* Optional: Set background color to avoid overlap with other columns */
            z-index: 2;
            /* Ensures the first column is above other content */
        }

        .gg {
            position: sticky;
            left: 0;
            z-index: 2;
            /* Ensures the first column is above other content */

        }
    </style>
    <main>

        <div class="content">
            <h1 class="text-3xl font-semibold text-center mb-4">Overload Data</h1>
            <p class="mb-4 text-center">
                This table provides an overview of employee overload data. It includes details such as employee names, hours worked (highlighted in secondary color), and total amounts for specific days of the week, including custom columns for adjustments and grand totals. You can view and edit individual employee overloads below.
            </p>
            <div class="text-right mb-4">
                <a href="manage_overload_add.php" class="bg-green-500  py-2 px-4 rounded-md shadow-md hover:bg-green-600">ADD OVERLOAD</a>
            </div>
            <div class="text-right mt-4">
                <a href="manage_overload_ultra_edit.php" class="bg-green-500  py-2 px-4 rounded-md shadow-md hover:bg-green-600">
                    Edit all Days
                </a>

            </div>
            <div class="table-wrapper">
                <!-- Start Form -->
                <table id="crudTable" class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <thead>
                        <tr>
                            <th rowspan="2" class="sticky gg">Employee Name</th>
                            <th colspan="3">MWF</th>
                            <th colspan="3">TTH</th>
                            <th colspan="3">SS</th>
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
                            <th rowspan="2">ACTION</th>
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
                    <tbody class="text-center">
                        <?php if (!empty($overloadData)) : ?>
                            <?php foreach ($overloadData as $row) : ?>
                                <tr data-id="<?= htmlspecialchars($row['overload_id']) ?>">


                                    <td style="text-align: justify; padding: 0.5rem; white-space: nowrap;">
                                        <span style="display: inline-block;"><?= htmlspecialchars(trim(preg_replace('/\s+/', ' ', $row['employee_name']))) ?></span>
                                    </td>

                                    <td><?= htmlspecialchars(number_format($row['mwf_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['mwf_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['mwf_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['tth_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['tth_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['tth_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['ss_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['ss_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['ss_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['monday_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['monday_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['monday_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['tuesday_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['tuesday_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['tuesday_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['wednesday_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['wednesday_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['wednesday_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['thursday_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['thursday_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['thursday_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['friday_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['friday_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['friday_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['saturday_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['saturday_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['saturday_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['sunday_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['sunday_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['sunday_total'], 2)) ?></td>

                                    <td> <?= htmlspecialchars(number_format($row['mtth_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['mtth_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['mtth_total'], 2)) ?></td>

                                    <td> <?= htmlspecialchars(number_format($row['mtwf_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['mtwf_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['mtwf_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['twthf_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['twthf_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['twthf_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['mw_days'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['mw_hrs'], 2)) ?></td>
                                    <td><?= htmlspecialchars('' . number_format($row['mw_total'], 2)) ?></td>

                                    <td><?= htmlspecialchars(number_format($row['less_lateOL'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['additional'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['adjustment_less'], 2)) ?></td>
                                    <td><?= htmlspecialchars('₱' . number_format($row['grand_total'], 2)) ?></td>
                                    <td>
                                        <a href="manage_overload_edit.php?row_id=<?= htmlspecialchars($row['overload_id']) ?>"
                                            style="color: white; background-color: blue; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Edit</a>
                                    </td>




                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="27">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- Submit Button -->

                </form> <!-- End Form -->
            </div>

        </div>

        <script>
            function updateDayTotal(inputElement, day) {
                // Get all input fields for the specified day
                const dayInputs = document.querySelectorAll('[name$="_days[]"]');

                // Iterate through each input element and update its value
                dayInputs.forEach(function(field) {
                    if (field.name.includes(day)) {
                        field.value = inputElement.value; // Set the value of each field
                    }
                });
            }
        </script>



        <script src="/js/bootstrap.bundle.min.js"></script>
        </div>



    </main>



</body>

</html>