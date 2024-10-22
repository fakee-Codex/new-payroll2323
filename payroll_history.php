<?php
session_start();
require 'database_connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Fetch all payroll history
$query = "SELECT * FROM payroll_history ORDER BY date_generated DESC";
$result = mysqli_query($conn, $query);

include 'header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Payroll History</h1>

    <!-- Payroll History Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Date Generated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . date("F j, Y", strtotime($row['start_date'])) . "</td>";
                                echo "<td>" . date("F j, Y", strtotime($row['end_date'])) . "</td>";
                                echo "<td>" . date("F j, Y h:i A", strtotime($row['date_generated'])) . "</td>";
                                echo "<td><a href='print_payroll.php?start_date=" . $row['start_date'] . "&end_date=" . $row['end_date'] . "' target='_blank' class='btn btn-info'>View Payroll</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No payroll history found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
