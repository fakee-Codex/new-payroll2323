<?php
session_start();
require 'database_connection.php'; // Include the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php'); // Redirect to login if not authenticated
    exit;
}

// Handle form submission for adding or updating a system user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_user'])) {
        // Update user details
        $admin_id = $_POST['admin_id'];
        $username = $_POST['username'];
        $role_id = $_POST['role_id'];
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the new password
            $sql = "UPDATE admins SET username = ?, password = ?, role_id = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssii', $username, $password, $role_id, $admin_id);
        } else {
            $sql = "UPDATE admins SET username = ?, role_id = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sii', $username, $role_id, $admin_id);
        }
        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?update_success=1');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } elseif (isset($_POST['add_user'])) {
        // Add a new user
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
        $role_id = $_POST['role_id'];

        // Insert the new user into the admins table
        $sql = "INSERT INTO admins (username, password, role_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $username, $password, $role_id);

        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Fetch all admins and roles from the database
$sql = "SELECT a.admin_id, a.username, r.role_name, r.role_id FROM admins a JOIN roles r ON a.role_id = r.role_id";
$admins_result = $conn->query($sql);

// Fetch all roles for dropdown
$roles_result = $conn->query("SELECT * FROM roles");

?>

<?php include 'header.php'; ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="alert" class="alert alert-success" role="alert">
        System User added successfully!
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['update_success']) && $_GET['update_success'] == 1): ?>
    <div id="alert" class="alert alert-success" role="alert">
        System User updated successfully!
    </div>
    <?php endif; ?>
    
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">System Users</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="#" data-toggle="modal" data-target="#addUserModal" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add System User</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    <?php while ($row = $admins_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['admin_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['role_name']); ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary"
                                   onclick="openEditModal(<?php echo $row['admin_id']; ?>, '<?php echo htmlspecialchars($row['username']); ?>', <?php echo $row['role_id']; ?>)">
                                   Edit
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add System User</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <input class="form-control" type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" name="role_id" required>
                            <option value="" disabled selected>Select Role</option>
                            <?php while ($role = $roles_result->fetch_assoc()): ?>
                            <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <input type="hidden" name="add_user" value="1">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Add User">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit System User</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <input class="form-control" type="text" id="edit_username" name="username" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="New Password (Leave blank if unchanged)">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" name="role_id" id="edit_role_id" required>
                            <!-- Roles will be dynamically populated via JavaScript -->
                        </select>
                    </div>
                    <input type="hidden" name="admin_id" id="edit_admin_id">
                    <input type="hidden" name="update_user" value="1">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" value="Update User">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to handle Edit Modal -->
<script>
// Open Edit Modal and populate fields
function openEditModal(adminId, username, roleId) {
    document.getElementById('edit_admin_id').value = adminId;
    document.getElementById('edit_username').value = username;

    // Fetch roles for the role dropdown
    var roleSelect = document.getElementById('edit_role_id');
    roleSelect.innerHTML = ''; // Clear existing options

    fetch('fetch_roles.php')
        .then(response => response.json())
        .then(roles => {
            roles.forEach(role => {
                var option = document.createElement('option');
                option.value = role.role_id;
                option.textContent = role.role_name;

                // Pre-select the current role
                if (role.role_id == roleId) {
                    option.selected = true;
                }

                roleSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching roles:', error);
        });

    $('#editUserModal').modal('show');
}
</script>

<!-- Alert Notification -->
<script>
    // Set a timeout to hide the alert after 4 seconds
    setTimeout(function() {
        var alertElement = document.getElementById('alert');
        if (alertElement) {
            alertElement.style.display = 'none';
        }
    }, 4000);
</script>

<?php include 'footer.php'; ?>

<?php
$conn->close(); // Close the database connection
?>
