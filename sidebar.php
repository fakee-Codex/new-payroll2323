<?php


require 'database_connection.php';




$sql = "
    SELECT 
        e.employee_id, 
        CONCAT(e.last_name, ', ', e.first_name, ' ', e.suffix_title) AS full_name,
        e.basic_salary, 
        e.honorarium, 
        
                e.overload_rate,
       
                ct.overload_total,

        ct.wr_hr,
        ct.wr_rate,
        ct.wr_total,
        ct.adjust_hr,
        ct.adjust_rate,
        ct.adjust_total,
        ct.watch_hr,
        e.watch_reward,
        ct.watch_total,
        ct.gross_pay,
        ct.absent_late_hr,
         e.absent_lateRate,
        ct.absent_late_total,

        n.ret,
        n.sss,
        n.hdmf_pag,

      
 
        
        ct.canteen,
        ct.others,
        ct.total_deduction,
        ct.net_pay,
        ct.reg_date,
        c.mp2,
        c.medical_savings,
      
                
               




        COALESCE(SUM(o.grand_total), 0) AS overload_hr, 
    
       
        COALESCE((c.sss_ee + c.sss_er), 0) AS sss_total, 
        COALESCE((c.pag_ibig_ee + c.pag_ibig_er), 0) AS pag_ibig_total, 
        COALESCE((c.philhealth_ee + c.philhealth_er), 0) AS philhealth_total
    FROM employees e
    LEFT JOIN overload o ON e.employee_id = o.employee_id
    LEFT JOIN loans n ON e.employee_id = n.employee_id
    LEFT JOIN contributions c ON e.employee_id = c.employee_id
    LEFT JOIN computation ct ON e.employee_id = ct.employee_id

    GROUP BY e.employee_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>

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


        td {
            padding: 0.5rem;
            text-align: justify;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f3f4f6;
            text-align: center;
            font-weight: bold;
        }

        td input {
            width: 100%;
            padding: 0.25rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            text-align: justify;
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
            text-align: justify;
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

        #crudTable {

            display: block;
            max-height: 600px;
            /* Adjust height as needed */
            overflow-y: auto;
            width: 100%;
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
            z-index: 1;
            /* Ensures the first column is above other content */
        }

        .nggh {
            position: sticky;
            top: 0;
            z-index: 3;
            /* Ensures the first column is above other content */
        }

        .bgg {
            position: sticky;
            left: 0;
            z-index: 4;
            /* Ensures the first column is above other content */
        }
    </style>
    <main>
        <div class="content">
            <h1 class="text-xl font-bold mb-4">
                GFI Payroll (<?php echo date('F'); ?> 1-15, <?php echo date('Y'); ?>)
            </h1>

            <div class="table-wrapper">
                <table id="crudTable" class="table table-bordered w-full border">

                    <thead class="nggh">
                        <tr>
                            <th rowspan="2" class="p-2 position-sticky top-0 text-center align-middle" style="background-color: #FEF08A;">Name</th>


                            <th colspan="2" class="p-2" style="background-color: #E3F2FD; text-align: center; vertical-align: middle;">Full-Time</th>
                            <th colspan="2" class="p-2" style="background-color: #E3F2FD; text-align: center; vertical-align: middle;">Overloads</th>
                            <th rowspan="2" class="p-2" style="background-color: #E3F2FD; text-align: center; vertical-align: middle;">Total</th>

                            <th colspan="3" class="p-2 text-center align-middle" style="background-color: #D4EDDA;">CLUBS</th>
                            <th colspan="3" class="p-2 text-center align-middle" style="background-color: #D4EDDA;">Adjustment</th>
                            <th colspan="3" class="p-2 text-center align-middle" style="background-color: #D4EDDA;">Watch Reward</th>

                            <th rowspan="2" class="p-2" style="background-color: #FFCBA4; text-align: center; vertical-align: middle;">Gross Pay</th>

                            <th colspan="3" class="p-2 text-center align-middle" style="background-color: #FFD6D6;">Absences/Late</th>
                            <th colspan="3" class="p-2 text-center align-middle" style="background-color: #F0F0F0;">Loans</th>

                            <th colspan="5" class="p-2 text-center align-middle" style="background-color: #E6E6FA;">Contributions</th>

                            <th rowspan="2" class="p-2" style="background-color: #FFD6D6; text-align: center; vertical-align: middle;">Canteen</th>
                            <th rowspan="2" class="p-2" style="background-color: #FFD6D6; text-align: center; vertical-align: middle;">Others</th>
                            <th rowspan="2" class="p-2" style="background-color: #FFD6D6; text-align: center; vertical-align: middle;">Total Deductions</th>
                            <th rowspan="2" class="p-2" style="background-color: #FFCBA4; text-align: center; vertical-align: middle;">Net Pay</th>
                            <th rowspan="2" class="p-2 bg-secondary text-center align-middle">Action</th>

                        </tr>

                        <tr>
                            <th class="p-2 align-middle" style="background-color: #E3F2FD;">Basic</th>
                            <th class="p-2 align-middle" style="background-color: #E3F2FD;">Honorarium</th>
                            <th class="p-2 align-middle" style="background-color: #E3F2FD;">HR</th>
                            <th class="p-2 align-middle" style="background-color: #E3F2FD;">Rate</th>

                            <th class="p-2 align-middle" style="background-color: #D4EDDA;">WR</th>
                            <th class="p-2 align-middle" style="background-color: #D4EDDA;">Rate</th>
                            <th class="p-2 align-middle" style="background-color: #D4EDDA;">Total</th>
                            <th class="p-2 align-middle" style="background-color: #D4EDDA;">HR</th>
                            <th class="p-2 align-middle" style="background-color: #D4EDDA;">Rate</th>
                            <th class="p-2 align-middle" style="background-color: #D4EDDA;">Total</th>
                            <th class="p-2 align-middle" style="background-color: #D4EDDA;">HR</th>
                            <th class="p-2 align-middle" style="background-color: #D4EDDA;">Rate</th>
                            <th class="p-2 align-middle" style="background-color: #D4EDDA;">Total</th>

                            <th class="p-2 align-middle" style="background-color: #FFD6D6;">HR</th>
                            <th class="p-2 align-middle" style="background-color: #FFD6D6;">Rate</th>
                            <th class="p-2 align-middle" style="background-color: #FFD6D6;">Total</th>

                            <th class="p-2 align-middle" style="background-color: #F0F0F0;">RET</th>
                            <th class="p-2 align-middle" style="background-color: #F0F0F0;">SSS</th>
                            <th class="p-2 align-middle" style="background-color: #F0F0F0;">HDMF (pag-ibig)</th>

                            <th class="p-2 align-middle" style="background-color: #E6E6FA;">MP2</th>
                            <th class="p-2 align-middle" style="background-color: #E6E6FA;">MED. S</th>
                            <th class="p-2 align-middle" style="background-color: #E6E6FA;">SSS</th>
                            <th class="p-2 align-middle" style="background-color: #E6E6FA;">HDMF (mandatory)</th>
                            <th class="p-2 align-middle" style="background-color: #E6E6FA;">PHIC</th>
                        </tr>

                    </thead>
                    <tbody class="ngg">

                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td data-employee='{$row['employee_id']}' class='p-2 sticky gg' style='padding: 0.5rem; text-align: justify; border: 1px solid #ddd; white-space: nowrap; overflow: hidden; background-color: #FEF08A;'>{$row['full_name']}</td>";



                                echo "<td class='p-2' style='background-color: #E3F2FD;'>" . number_format($row['basic_salary'], 2) . "</td>"; // Basic Salary
                                echo "<td class='p-2' style='background-color: #E3F2FD;'>" . number_format($row['honorarium'], 2) . "</td>"; // Honorarium
                                echo "<td class='p-2' style='background-color: #E3F2FD;'>" . number_format($row['overload_hr'], 2) . "</td>"; // Overload HR
                                echo "<td class='p-2' style='background-color: #E3F2FD;'>" . number_format($row['overload_rate'], 2, '.', ',') . "</td>"; // Overload Rate
                                echo "<td name='overload_total' class='p-2' style='background-color: #E3F2FD;'>" . number_format($row['overload_total'], 2) . "</td>"; // Overload Total



                                echo "<td style='background-color: #D4EDDA;'><input type='number' name='wr_hr' class='editable-cell' value='{$row['wr_hr']}'></td>"; // WR HR
                                echo "<td style='background-color: #D4EDDA;'><input type='number' name='wr_rate' class='editable-cell' value='{$row['wr_rate']}'></td>"; // WR Rate
                                echo "<td name='wr_total' class='p-2' style='background-color: #D4EDDA;'>" . number_format($row['wr_total'], 2) . "</td>"; // WR Total
                                echo "<td style='background-color: #D4EDDA;'><input type='number' name='adjust_rate' class='editable-cell' value='{$row['adjust_rate']}'></td>"; // Adjust Rate
                                echo "<td style='background-color: #D4EDDA;'><input type='number' name='adjust_hr' class='editable-cell' value='{$row['adjust_hr']}'></td>"; // Adjust HR
                                echo "<td name='adjust_total' class='p-2' style='background-color: #D4EDDA;'>" . number_format($row['adjust_total'], 2) . "</td>"; // Adjust Total
                                echo "<td style='background-color: #D4EDDA;'><input type='number' name='watch_hr' class='editable-cell' value='{$row['watch_hr']}'></td>"; // Watch HR
                                echo "<td class='p-2' name='watch_reward' style='background-color: #D4EDDA;'>" . number_format($row['watch_reward'], 2) . "</td>"; // Watch Reward
                                echo "<td name='watch_total' class='p-2' style='background-color: #D4EDDA;'>" . number_format($row['watch_total'], 2) . "</td>"; // Watch Total



                                echo "<td name='gross_pay' class='p-2' style='background-color: #FFCBA4;'>" . number_format($row['gross_pay'], 2) . "</td>"; // Gross Pay


                                echo "<td style='background-color: #FFD6D6;'><input type='number' name='absent_late_hr' class='editable-cell' value='{$row['absent_late_hr']}'></td>"; // Absent Late HR
                                echo "<td class='p-2' name='absent_lateRate' style='background-color: #FFD6D6;'>" . number_format($row['absent_lateRate'], 2) . "</td>";
                                echo "<td name='absent_late_total' class='p-2' style='background-color: #FFD6D6;'>" . number_format(0.00, 2) . "</td>"; // Absent Late Total

                                echo "<td class='p-2' style='background-color: #F0F0F0;'>" . number_format($row['ret'], 2) . "</td>"; // RET
                                echo "<td class='p-2' style='background-color: #F0F0F0;'>" . number_format($row['sss'], 2) . "</td>"; // SSS
                                echo "<td class='p-2' style='background-color: #F0F0F0;'>" . number_format($row['hdmf_pag'], 2) . "</td>"; // Pag-ibig




                                echo "<td class='p-2' style='background-color: #E6E6FA;'>" . number_format($row['mp2'], 2) . "</td>"; // MP2
                                echo "<td class='p-2' style='background-color: #E6E6FA;'>" . number_format($row['medical_savings'], 2) . "</td>"; // Medical Savings

                                echo "<td class='p-2' style='background-color: #E6E6FA;'>" . number_format($row['sss_total'], 2) . "</td>"; // SSS

                                echo "<td class='p-2' style='background-color: #E6E6FA;'>" . number_format($row['pag_ibig_total'], 2) . "</td>"; // Pag-ibig Total
                                echo "<td class='p-2' style='background-color: #E6E6FA;'>" . number_format($row['philhealth_total'], 2) . "</td>"; // PhilHealth Total



                                echo "<td style='background-color: #FFD6D6;'><input type='number' name='canteen' class='editable-cell' value='{$row['canteen']}'></td>"; // Canteen
                                echo "<td style='background-color: #FFD6D6;'><input type='number' name='others' class='editable-cell' value='{$row['others']}'></td>"; // Others

                                echo "<td name='total_deduction' class='p-2' style='background-color: #FFD6D6;'>" . number_format($row['total_deduction'], 2) . "</td>"; // Total Deduction

                                echo "<td name='net_pay' class='p-2' style='background-color: #FFCBA4;'>" . number_format($row['net_pay'], 2) . "</td>"; // Net Pay

                                echo "<td>
                                <button class='btn btn-primary p-2 viewPayslip' data-employee='{$row['employee_id']}'>View Payslip</button>
                            </td>";





                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='12' class='p-2 text-center'>No data found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

            </div>


            <!-- Modal Structure -->
            <div class="modal fade" id="payslipModal" tabindex="-1" aria-labelledby="payslipModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="payslipModalLabel">Employee Payslip</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="payslipContent">
                            <!-- Payslip details will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.querySelectorAll(".viewPayslip").forEach(button => {
                        button.addEventListener("click", function() {
                            let employeeId = this.getAttribute("data-employee");

                            // AJAX request to fetch payslip details
                            fetch("payslips/fetch_payslip.php?id=" + employeeId)
                                .then(response => response.text())
                                .then(data => {
                                    document.getElementById("payslipContent").innerHTML = data;
                                    new bootstrap.Modal(document.getElementById("payslipModal")).show();
                                })
                                .catch(error => console.error("Error fetching payslip:", error));
                        });
                    });
                });
            </script>



            <div class="flex justify-end mt-5 gap-x-4">


                <button id="saveTableBtn" class="save-btn mb-4">Save Table</button>
            </div>


        </div>
        <script>

        </script>


    </main>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const saveButton = document.getElementById("saveTableBtn");

            // Function to calculate values for a row
            function calculateRow(row) {
                const basicSalary = parseFloat(row.querySelector("td:nth-child(2)").textContent.replace(/,/g, '')) || 0;
                const honorarium = parseFloat(row.querySelector("td:nth-child(3)").textContent.replace(/,/g, '')) || 0;
                const overloadHR = parseFloat(row.querySelector("td:nth-child(4)").textContent.replace(/,/g, '')) || 0;
                const overloadRate = parseFloat(row.querySelector("td:nth-child(5)").textContent.replace(/,/g, '')) || 0;

                const overloadTotal = overloadHR * overloadRate;
                row.querySelector("td[name='overload_total']").textContent = overloadTotal.toFixed(2);

                const adjustmentHR = parseFloat(row.querySelector("input[name='adjust_hr']").value) || 0;
                const adjustmentRate = parseFloat(row.querySelector("input[name='adjust_rate']").value) || 0;
                const wrHR = parseFloat(row.querySelector("input[name='wr_hr']").value) || 0;
                const wrRate = parseFloat(row.querySelector("input[name='wr_rate']").value) || 0;
                const watchHR = parseFloat(row.querySelector("input[name='watch_hr']").value) || 0;
                const watchReward = parseFloat(row.querySelector("td:nth-child(14)").textContent.replace(/,/g, '')) || 0;

                const absentLateHR = parseFloat(row.querySelector("input[name='absent_late_hr']").value) || 0;
                const absentLateRate = parseFloat(row.querySelector("td:nth-child(18)").textContent.replace(/,/g, '')) || 0;
                const absentLateTotal = absentLateHR * absentLateRate;
                row.querySelector("td[name='absent_late_total']").textContent = absentLateTotal.toFixed(2);

                // Fix Loan calculations
                const ret = parseFloat(row.querySelector("td:nth-child(20)").textContent.replace(/,/g, '')) || 0;
                const sss = parseFloat(row.querySelector("td:nth-child(21)").textContent.replace(/,/g, '')) || 0;
                const hdmfPag = parseFloat(row.querySelector("td:nth-child(22)").textContent.replace(/,/g, '')) || 0;

                const mp2 = parseFloat(row.querySelector("td:nth-child(23)").textContent.replace(/,/g, '')) || 0;
                const medicalSavings = parseFloat(row.querySelector("td:nth-child(24)").textContent.replace(/,/g, '')) || 0;
                const sssTotal = parseFloat(row.querySelector("td:nth-child(25)").textContent.replace(/,/g, '')) || 0;
                const pagIbigTotal = parseFloat(row.querySelector("td:nth-child(26)").textContent.replace(/,/g, '')) || 0;
                const philhealthTotal = parseFloat(row.querySelector("td:nth-child(27)").textContent.replace(/,/g, '')) || 0;

                const canteen = parseFloat(row.querySelector("input[name='canteen']").value) || 0;

                const others = parseFloat(row.querySelector("input[name='others']").value) || 0;


                const wrTotal = wrHR * wrRate;
                const adjustmentTotal = adjustmentHR * adjustmentRate;
                const watchRewardTotal = watchHR * watchReward;

                const totalLoans = hdmfPag + sss + ret;
                const totalContributions = mp2 + medicalSavings + sssTotal + pagIbigTotal + philhealthTotal;
                const totalDeduction = absentLateTotal + totalLoans + totalContributions + canteen + others;

                const grossPay = basicSalary + honorarium + overloadTotal + wrTotal + adjustmentTotal + watchRewardTotal;
                const netPay = grossPay - totalDeduction;

                row.querySelector("td[name='wr_total']").textContent = wrTotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                row.querySelector("td[name='adjust_total']").textContent = adjustmentTotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                row.querySelector("td[name='watch_total']").textContent = watchRewardTotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                row.querySelector("td[name='gross_pay']").textContent = grossPay.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                row.querySelector("td[name='total_deduction']").textContent = totalDeduction.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                row.querySelector("td[name='net_pay']").textContent = netPay.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }


            // Trigger calculation when the page loads
            function calculateAllRows() {
                const rows = document.querySelectorAll("#crudTable tbody tr");
                rows.forEach(row => {
                    calculateRow(row);
                });
            }

            // Calculate all rows once the page is loaded
            calculateAllRows();

            // Attach input event listener to editable cells
            document.querySelectorAll(".editable-cell").forEach(cell => {
                cell.addEventListener("input", function() {
                    const row = this.closest("tr");
                    calculateRow(row);
                });
            });


            // Save table data
            document.getElementById("saveTableBtn").addEventListener("click", function() {
                const rows = document.querySelectorAll("#crudTable tbody tr");
                const data = [];



                rows.forEach(row => {
                    const rowData = {
                        employee_id: row.querySelector("td[data-employee]").getAttribute("data-employee") || 0, // Default employee_id to 0
                        full_name: row.querySelector("td[data-employee]").textContent || "", // Ensure full_name is set to an empty string if empty
                        basic_salary: parseFloat(row.querySelector("td:nth-child(2)").textContent.replace(/,/g, '')) || 0,
                        honorarium: parseFloat(row.querySelector("td:nth-child(3)").textContent.replace(/,/g, '')) || 0,
                        overload_hr: parseFloat(row.querySelector("td:nth-child(4)").textContent.replace(/,/g, '')) || 0,
                        overload_rate: parseFloat(row.querySelector("td:nth-child(5)").textContent.replace(/,/g, '')) || 0,
                        overload_total: parseFloat(row.querySelector("td[name='overload_total']").textContent.replace(/,/g, '')) || 0,
                        wr_hr: parseFloat(row.querySelector("input[name='wr_hr']").value) || 0,
                        wr_rate: parseFloat(row.querySelector("input[name='wr_rate']").value) || 0,
                        wr_total: parseFloat(row.querySelector("td[name='wr_total']").textContent.replace(/,/g, '')) || 0,
                        adjust_hr: parseFloat(row.querySelector("input[name='adjust_hr']").value) || 0,
                        adjust_rate: parseFloat(row.querySelector("input[name='adjust_rate']").value) || 0,
                        adjust_total: parseFloat(row.querySelector("td[name='adjust_total']").textContent.replace(/,/g, '')) || 0,
                        watch_hr: parseFloat(row.querySelector("input[name='watch_hr']").value) || 0,
                        watch_reward: parseFloat(row.querySelector("td:nth-child(14)").textContent.replace(/,/g, '')) || 0,





                        watch_total: parseFloat(row.querySelector("td[name='watch_total']").textContent.replace(/,/g, '')) || 0,
                        gross_pay: parseFloat(row.querySelector("td[name='gross_pay']").textContent.replace(/,/g, '')) || 0,
                        absent_late_hr: parseFloat(row.querySelector("input[name='absent_late_hr']").value) || 0,



                        absent_lateRate: parseFloat(row.querySelector("td:nth-child(18)").textContent.replace(/,/g, '')) || 0,

                        absent_late_total: parseFloat(row.querySelector("td[name='absent_late_total']").textContent.replace(/,/g, '')) || 0,


                        ret: parseFloat(row.querySelector("td:nth-child(20)").textContent.replace(/,/g, '')) || 0,
                        sss: parseFloat(row.querySelector("td:nth-child(21)").textContent.replace(/,/g, '')) || 0,
                        hdmf_pag: parseFloat(row.querySelector("td:nth-child(22)").textContent.replace(/,/g, '')) || 0,




                        mp2: parseFloat(row.querySelector("td:nth-child(23)").textContent.replace(/,/g, '')) || 0,
                        medical_savings: parseFloat(row.querySelector("td:nth-child(24)").textContent.replace(/,/g, '')) || 0,
                        sss_total: parseFloat(row.querySelector("td:nth-child(25)").textContent.replace(/,/g, '')) || 0,
                        pag_ibig_total: parseFloat(row.querySelector("td:nth-child(26)").textContent.replace(/,/g, '')) || 0,
                        philhealth_total: parseFloat(row.querySelector("td:nth-child(27)").textContent.replace(/,/g, '')) || 0,







                        canteen: parseFloat(row.querySelector("input[name='canteen']").value) || 0,
                        others: parseFloat(row.querySelector("input[name='others']").value) || 0,
                        total_deduction: parseFloat(row.querySelector("td[name='total_deduction']").textContent.replace(/,/g, '')) || 0,
                        net_pay: parseFloat(row.querySelector("td[name='net_pay']").textContent.replace(/,/g, '')) || 0,


                    };

                    console.log("Pushed Data:", rowData);
                    data.push(rowData);
                });




                if (confirm("Are you sure you want to save this data?")) {
                    fetch("cc-save1.php", {
                            method: "POST",
                            body: JSON.stringify(data), // Ensure 'data' is properly structured
                            headers: {
                                "Content-Type": "application/json",
                            },
                        })
                        .then(response => response.json()) // Convert response to JSON
                        .then(jsonData => {
                            console.log("Response Data:", jsonData);
                            if (jsonData.success) {
                                alert(jsonData.message); // Get message from PHP response
                            } else {
                                console.error("Error Response:", jsonData);
                                alert("Error: " + jsonData.message);
                            }
                        })
                        .catch(error => {
                            console.error("Fetch Error:", error);
                            alert("An error occurred while saving the data.");
                        });
                }


            });

        });
    </script>


</body>

</html>