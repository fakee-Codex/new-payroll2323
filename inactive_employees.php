<?php
session_start();
require 'database_connection.php'; // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}


// Fetch inactive employees
$sql = "SELECT e.employee_id, e.employee_id_number, e.first_name, e.last_name, jt.job_title, d.department_name, e.reason 
        FROM employees e
        JOIN job_titles jt ON e.job_title_id = jt.job_title_id
        JOIN departments d ON e.department_id = d.department_id
        WHERE e.status = 'inactive'";
$result = $conn->query($sql);

?>

<?php include 'header.php'; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Inactive Employees</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Job Title</th>
                            <th>Department</th>
                            <th>Reason for Inactivity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td><a href="javascript:void(0);" onclick="viewEmployeeSummary(<?php echo $row['employee_id']; ?>)">View Summary</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Summary Modal -->
<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="summaryModalLabel"><span id="modal-employee-name"></span> - Summary</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>ID Number:</strong> <span id="modal-employee-id-number"></span></p>
        <p><strong>Date of Hired:</strong> <span id="modal-date-joining"></span></p>
        <p><strong>Date of Inactive:</strong> <span id="modal-date-inactive"></span></p>
        <p><strong>Reason:</strong> <span id="modal-reason"></span></p>
        <hr/>
        <h5>Payment Summary</h5>
        <p><strong>Total Hours Rendered:</strong> <span id="modal-total-hours"></span></p>
        <p><strong>Total Pay:</strong> <span id="modal-total-pay"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Joshua and Friends</span>
        </div>
    </div>
</footer>

<script>
function viewEmployeeSummary(employeeId) {
    $.getJSON('get_employee_summary.php?employee_id=' + employeeId, function(data) {
        if (data.error) {
            console.error(data.error);
            alert('Error fetching employee data.');
        } else {
            // Populate the modal with the fetched data
            $('#modal-employee-name').text(data.first_name + ' ' + data.last_name);
            $('#modal-employee-id-number').text(data.employee_id_number);

            // Format and display dates
            $('#modal-date-joining').text(new Date(data.date_of_joining).toLocaleDateString());
            $('#modal-date-inactive').text(data.date_inactive ? new Date(data.date_inactive).toLocaleDateString() : 'N/A');
            $('#modal-status').text(data.status);
            $('#modal-reason').text(data.reason ? data.reason : 'N/A');

            // Display total hours and total pay
            $('#modal-total-hours').text(data.total_hours ? data.total_hours + ' hours' : '0 hours');
            $('#modal-total-pay').text(data.total_pay ? '₱ ' + data.total_pay.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '₱ 0.00');


            // Show the modal
            $('#summaryModal').modal('show');
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error fetching employee summary: ' + textStatus);
    });
}






</script>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
