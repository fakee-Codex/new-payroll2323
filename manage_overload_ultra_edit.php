<?php

include('database_connection.php'); // Assuming you have a database connection setup

// Execute the query to fetch overload data
$query = "
    SELECT 
        o.overload_id,  -- Ensure overload_id is selected here
        o.*, 
        CONCAT(e.first_name, ' ', e.last_name) AS employee_name
    FROM overload o
    JOIN employees e ON o.employee_id = e.employee_id
";  // Customize the query if needed
$result = mysqli_query($conn, $query);  // Execute the query

if (!$result) {
    die("Query failed: " . mysqli_error($conn));  // Handle query failure
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>


</head>

<body>

    <?php include 'aside.php'; ?> <!-- This will import the sidebar -->

    <main>


        <div class="bg-gray-200 p-2 rounded-lg shadow-md">



            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Edit Overload Data</h2>
            <!-- Back Button -->
            <div class="text-left mb-4">
                <button
                    onclick="window.location.href='manage_overload.php';"
                    class="bg-gray-500 text-white py-2 px-4 rounded-md shadow-md hover:bg-pink-600">
                    Back to Manage Overload
                </button>
            </div>


            <form method="POST" action="">
                <div class="text-right mb-4">
                    <button id="updateButton" class="bg-green-500 text-white py-2 px-4 rounded-md shadow-md hover:bg-green-600">
                        Save Changes
                    </button>
                </div>

                <div class="overflow-x-auto">

                    <table class="w-full bg-white shadow-md rounded-lg border border-gray-200">
                        <thead class="bg-blue-600 text-white text-center">
                            <tr>
                                <th rowspan="2" class="p-3 border border-gray-300 sticky left-0 bg-blue-700 z-10">Employee Name</th>
                                <th colspan="3" class="p-3 border border-gray-300">MWF</th>
                                <th colspan="3" class="p-3 border border-gray-300">TTH</th>
                                <th colspan="3" class="p-3 border border-gray-300">SS</th>
                                <th colspan="3" class="p-3 border border-gray-300">MONDAY</th>
                                <th colspan="3" class="p-3 border border-gray-300">TUESDAY</th>
                                <th colspan="3" class="p-3 border border-gray-300">Wednesday</th>
                                <th colspan="3" class="p-3 border border-gray-300">Thursday</th>
                                <th colspan="3" class="p-3 border border-gray-300">Friday</th>
                                <th colspan="3" class="p-3 border border-gray-300">Saturday</th>
                                <th colspan="3" class="p-3 border border-gray-300">Sunday</th>
                                <th colspan="3" class="p-3 border border-gray-300">MTTH</th>
                                <th colspan="3" class="p-3 border border-gray-300">MTWF</th>
                                <th colspan="3" class="p-3 border border-gray-300">TWTHF</th>
                                <th colspan="3" class="p-3 border border-gray-300">MW</th>
                                <th rowspan="2" class="p-3 border border-gray-300">Less</th>
                                <th rowspan="2" class="p-3 border border-gray-300">Add</th>
                                <th rowspan="2" class="p-3 border border-gray-300">Adjustments</th>
                                <th rowspan="2" class="p-3 border border-gray-300 sticky left-0 bg-blue-700 z-10">Grand Total</th>

                            </tr>
                            <tr class="bg-blue-500">
                                <?php for ($i = 0; $i < 14; $i++) : ?>
                                    <th class="p-2 border border-gray-300">DAYS</th>
                                    <th class="p-2 border border-gray-300">HRS</th>
                                    <th class="p-2 border border-gray-300">TOTAL</th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>


                                <tr class="border-b hover:bg-gray-100"
                                    data-overload-id="<?= $row['overload_id'] ?>"
                                    data-employee-id="<?= $row['employee_id'] ?>">




                                    <!-- Employee Name -->
                                    <td class="p-3 font-semibold sticky left-0 bg-white border-r border-gray-300 z-10">
                                        <?= htmlspecialchars($row['employee_name']) ?>
                                    </td>

                                    <!-- Editable "Days" inputs -->
                                    <td class="p-3"><input type="number" name="mwf_days[]" value="<?= $row['mwf_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-mwf-hrs><?= htmlspecialchars($row['mwf_hrs']) ?></td>
                                    <td class="p-3 text-center" data-mwf-total><?= htmlspecialchars($row['mwf_total']) ?></td>

                                    <td class="p-3"><input type="number" name="tth_days[]" value="<?= $row['tth_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-tth-hrs><?= htmlspecialchars($row['tth_hrs']) ?></td>
                                    <td class="p-3 text-center" data-tth-total><?= htmlspecialchars($row['tth_total']) ?></td>

                                    <td class="p-3"><input type="number" name="ss_days[]" value="<?= $row['ss_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-ss-hrs><?= htmlspecialchars($row['ss_hrs']) ?></td>
                                    <td class="p-3 text-center" data-ss-total><?= htmlspecialchars($row['ss_total']) ?></td>

                                    <td class="p-3"><input type="number" name="monday_days[]" value="<?= $row['monday_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-monday-hrs><?= htmlspecialchars($row['monday_hrs']) ?></td>
                                    <td class="p-3 text-center" data-monday-total><?= htmlspecialchars($row['monday_total']) ?></td>

                                    <td class="p-3"><input type="number" name="tuesday_days[]" value="<?= $row['tuesday_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-tuesday-hrs><?= htmlspecialchars($row['tuesday_hrs']) ?></td>
                                    <td class="p-3 text-center" data-tuesday-total><?= htmlspecialchars($row['tuesday_total']) ?></td>

                                    <td class="p-3"><input type="number" name="wednesday_days[]" value="<?= $row['wednesday_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-wednesday-hrs><?= htmlspecialchars($row['wednesday_hrs']) ?></td>
                                    <td class="p-3 text-center" data-wednesday-total><?= htmlspecialchars($row['wednesday_total']) ?></td>

                                    <td class="p-3"><input type="number" name="thursday_days[]" value="<?= $row['thursday_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-thursday-hrs><?= htmlspecialchars($row['thursday_hrs']) ?></td>
                                    <td class="p-3 text-center" data-thursday-total><?= htmlspecialchars($row['thursday_total']) ?></td>

                                    <td class="p-3"><input type="number" name="friday_days[]" value="<?= $row['friday_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-friday-hrs><?= htmlspecialchars($row['friday_hrs']) ?></td>
                                    <td class="p-3 text-center" data-friday-total><?= htmlspecialchars($row['friday_total']) ?></td>

                                    <td class="p-3"><input type="number" name="saturday_days[]" value="<?= $row['saturday_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-saturday-hrs><?= htmlspecialchars($row['saturday_hrs']) ?></td>
                                    <td class="p-3 text-center" data-saturday-total><?= htmlspecialchars($row['saturday_total']) ?></td>

                                    <td class="p-3"><input type="number" name="sunday_days[]" value="<?= $row['sunday_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-sunday-hrs><?= htmlspecialchars($row['sunday_hrs']) ?></td>
                                    <td class="p-3 text-center" data-sunday-total><?= htmlspecialchars($row['sunday_total']) ?></td>

                                    <td class="p-3"><input type="number" name="mtth_days[]" value="<?= $row['mtth_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-mtth-hrs><?= htmlspecialchars($row['mtth_hrs']) ?></td>
                                    <td class="p-3 text-center" data-mtth-total><?= htmlspecialchars($row['mtth_total']) ?></td>

                                    <td class="p-3"><input type="number" name="mtwf_days[]" value="<?= $row['mtwf_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-mtwf-hrs><?= htmlspecialchars($row['mtwf_hrs']) ?></td>
                                    <td class="p-3 text-center" data-mtwf-total><?= htmlspecialchars($row['mtwf_total']) ?></td>

                                    <td class="p-3"><input type="number" name="twthf_days[]" value="<?= $row['twthf_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-twthf-hrs><?= htmlspecialchars($row['twthf_hrs']) ?></td>
                                    <td class="p-3 text-center" data-twthf-total><?= htmlspecialchars($row['twthf_total']) ?></td>

                                    <td class="p-3"><input type="number" name="mw_days[]" value="<?= $row['mw_days'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center" data-mw-hrs><?= htmlspecialchars($row['mw_hrs']) ?></td>
                                    <td class="p-3 text-center" data-mw-total><?= htmlspecialchars($row['mw_total']) ?></td>

                                   

                                    <!-- Less, Additional, Adjustments, Grand Total -->
                                    <td class="p-3"><input type="number" name="less_lateOL[]" value="<?= $row['less_lateOL'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3"><input type="number" name="additional[]" value="<?= $row['additional'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3"><input type="number" name="adjustment_less[]" value="<?= $row['adjustment_less'] ?>" class="w-16 border rounded px-2 py-1 text-center"></td>
                                    <td class="p-3 text-center font-bold text-blue-600 sticky right-0 bg-white z-20" data-grand-total>
                                        <?= htmlspecialchars($row['grand_total']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>



                </div>

            </form>
            <script>
                document.getElementById("updateButton").addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent default form submission

                    let rows = document.querySelectorAll("tbody tr");
                    let data = [];

                    rows.forEach(row => {
                        let rowData = {
                            overload_id: row.getAttribute("data-overload-id"),
                            employee_id: row.getAttribute("data-employee-id"),

                            // Inputs
                            wednesday_days: row.querySelector("input[name='wednesday_days[]']").value,
                            thursday_days: row.querySelector("input[name='thursday_days[]']").value,
                            friday_days: row.querySelector("input[name='friday_days[]']").value,
                            mtth_days: row.querySelector("input[name='mtth_days[]']").value,
                            mtwf_days: row.querySelector("input[name='mtwf_days[]']").value,
                            twthf_days: row.querySelector("input[name='twthf_days[]']").value,
                            mw_days: row.querySelector("input[name='mw_days[]']").value,
                            less_lateOL: row.querySelector("input[name='less_lateOL[]']").value,
                            additional: row.querySelector("input[name='additional[]']").value,
                            adjustment_less: row.querySelector("input[name='adjustment_less[]']").value,

                            // Text values from table cells
                            wednesday_hrs: row.querySelector("[data-wednesday-hrs]").textContent.trim(),
                            wednesday_total: row.querySelector("[data-wednesday-total]").textContent.trim(),
                            thursday_hrs: row.querySelector("[data-thursday-hrs]").textContent.trim(),
                            thursday_total: row.querySelector("[data-thursday-total]").textContent.trim(),
                            friday_hrs: row.querySelector("[data-friday-hrs]").textContent.trim(),
                            friday_total: row.querySelector("[data-friday-total]").textContent.trim(),
                            mtth_hrs: row.querySelector("[data-mtth-hrs]").textContent.trim(),
                            mtth_total: row.querySelector("[data-mtth-total]").textContent.trim(),
                            mtwf_hrs: row.querySelector("[data-mtwf-hrs]").textContent.trim(),
                            mtwf_total: row.querySelector("[data-mtwf-total]").textContent.trim(),
                            twthf_hrs: row.querySelector("[data-twthf-hrs]").textContent.trim(),
                            twthf_total: row.querySelector("[data-twthf-total]").textContent.trim(),
                            mw_hrs: row.querySelector("[data-mw-hrs]").textContent.trim(),
                            mw_total: row.querySelector("[data-mw-total]").textContent.trim(),
                            grand_total: row.querySelector("[data-grand-total]").textContent.trim(),
                        };

                        data.push(rowData);
                    });

                    // Send data to the server using fetch
                    fetch('manage_overload_ultra_edit_update.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(result => {
                            console.log('Success:', result);
                            window.location.reload();

                            // Handle success response here
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Handle error here
                        });
                });
            </script>


        </div>
        <script>

        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll("tr.border-b").forEach(row => {
                    addCalculationListeners(row);
                    calculateRowTotals(row);
                });
            });

            function addCalculationListeners(row) {
                const inputs = row.querySelectorAll("input[name$='_days[]'], input[name='less_lateOL[]'], input[name='additional[]'], input[name='adjustment_less[]']");

                inputs.forEach(input => {
                    input.addEventListener("input", function() {
                        const dayMatch = input.name.match(/(mwf|tth|wednesday|ss|monday|tuesday|wednesday|thursday|friday|saturday|sunday|mtth|mtwf|twthf|mw)_days\[\]/);
                        if (dayMatch) {
                            const dayColumn = dayMatch[1] + "_days[]";
                            syncColumnInputs(dayColumn, input.value); // Sync all inputs in the same column
                        }
                        calculateRowTotals(row);
                    });

                    input.addEventListener("focus", function() {
                        input.setSelectionRange(input.value.length, input.value.length);
                    });
                });
            }

            function calculateRowTotals(row) {
                let grandTotal = 0;
                const dayColumns = ["mwf", "tth", "ss", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday", "mtth", "mtwf", "twthf", "mw"];

                dayColumns.forEach(day => {
                    const daysInput = row.querySelector(`input[name="${day}_days[]"]`);
                    const hoursCell = row.querySelector(`td[data-${day}-hrs]`);
                    const totalCell = row.querySelector(`td[data-${day}-total]`);

                    if (daysInput && hoursCell && totalCell) {
                        const days = parseFloat(daysInput.value) || 0;
                        const hours = parseFloat(hoursCell.textContent) || 0;
                        const total = days * hours;

                        totalCell.textContent = total.toFixed(2);
                        grandTotal += total;
                    }
                });

                // Handle Less, Additional, and Adjustments
                const less = parseFloat(row.querySelector(`input[name="less_lateOL[]"]`)?.value) || 0;
                const add = parseFloat(row.querySelector(`input[name="additional[]"]`)?.value) || 0;
                const adjustments = parseFloat(row.querySelector(`input[name="adjustment_less[]"]`)?.value) || 0;

                // Final Grand Total Calculation
                grandTotal = grandTotal - less + add - adjustments;

                // Update Grand Total cell
                const grandTotalCell = row.querySelector(`td[data-grand-total]`);
                if (grandTotalCell) {
                    grandTotalCell.textContent = grandTotal.toFixed(2);
                }
            }

            // ✅ Sync all rows' inputs for the given column while allowing user input in any row
            function syncColumnInputs(inputName, value) {
                document.querySelectorAll(`input[name="${inputName}"]`).forEach(input => {
                    input.value = value;
                });

                // Recalculate all row totals after updating values
                document.querySelectorAll("tr.border-b").forEach(row => {
                    calculateRowTotals(row);
                });
            }
        </script>


    </main>



</body>

</html>