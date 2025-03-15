<?php
// Database connection
require 'database_connection.php';

// Validate and get the contribution ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $loan_id = intval($_GET['id']);

    // Fetch the contribution record for the given ID
    $sql = "SELECT 
            n.loan_id,
            e.employee_id,
            CONCAT(e.last_name, ', ', e.first_name, ' ', e.suffix_title) AS Name,
            e.employee_type AS Type,
            e.basic_salary AS Basic,
            n.ret, n.sss, n.hdmf_pag


            FROM loans n
            JOIN employees e ON n.employee_id = e.employee_id
            WHERE n.loan_id = ?";




    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $loan = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Invalid contribution ID.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>No contribution ID provided.</div>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $ret = $_POST['ret'];
    $sss = $_POST['sss'];
    $hdmf_pag = $_POST['hdmf_pag'];




    // Validate numeric inputs
    if (!is_numeric($ret) || !is_numeric($sss) || !is_numeric($hdmf_pag)) {
        echo "<div class='alert alert-danger'>Please enter valid numeric values for all fields.</div>";
        exit;
    }

    $update_sql = "UPDATE loans 
                   SET ret = ?, sss = ?, hdmf_pag = ?
                   WHERE loan_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param(
        "dddi",
        $ret,
        $sss,
        $hdmf_pag,
        $loan_id
    );

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Loans updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating loans: " . $stmt->error]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Contribution</title>
</head>

<?php include 'aside.php'; ?> <!-- This will import the sidebar -->
<body>
<div class="d-flex justify-content-center align-items-center">
    <div class="container mt-5">
        <form method="POST">
            <div class="row g-4 justify-content-center">
                <!-- Employer Share Column -->
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded shadow">
                        <h3 class="text-center mb-4">NAME: <?= htmlspecialchars($loan['Name']) ?></h3>

                        <!-- loans Section -->
                        <div class="mb-4">
                            <h4 class="text-center mb-4 text-primary fw-bold">Edit Credits</h4>

                            <div class="d-flex justify-content-center gap-3">
                                <!-- Retirement loan  -->
                                <div>
                                    <label for="ret" class="form-label fw-semibold">Retirement Loan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" name="ret" id="ret"
                                            class="form-control form-control-sm border-primary text-center"
                                            value="<?= htmlspecialchars($loan['ret']) ?>" placeholder="retirement" style="max-width: 150px;" required>
                                    </div>
                                </div>

                                <!-- SSS loan -->
                                <div>
                                    <label for="sss" class="form-label fw-semibold">SSS Loan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" name="sss" id="sss"
                                            class="form-control form-control-sm border-primary text-center"
                                            value="<?= htmlspecialchars($loan['sss']) ?>" placeholder="sss" style="max-width: 150px;" required>
                                    </div>
                                </div>

                                <!-- HDMF pag ibig loans -->
                                <div>
                                    <label for="hdmf_pag" class="form-label fw-semibold">HDMF Loans</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" name="hdmf_pag" id="hdmf_pag"
                                            class="form-control form-control-sm border-primary text-center"
                                            value="<?= htmlspecialchars($loan['hdmf_pag']) ?>" placeholder="pag-ibig" style="max-width: 150px;" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                            <a href="manage_loans.php" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


    <script>
        setTimeout(() => {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 3000); // 3 seconds
    </script>

<script>
        document.querySelector("form").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent normal form submission

            let formData = new FormData(this); // Get form data

            fetch("manage_loans_edit.php?id=<?= $loan_id ?>", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Success: " + data.message); // Success alert
                        window.location.href = "manage_loans.php"; // Redirect after success
                    } else {
                        alert("Error: " + data.message); // Error alert
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred, please try again.");
                });
        });
    </script>
</body>

</html>