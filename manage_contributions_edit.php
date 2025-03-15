<?php
// Database connection
require 'database_connection.php';

// Validate and get the contribution ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $contribution_id = intval($_GET['id']);

    // Fetch the contribution record for the given ID
    $sql = "SELECT 
                c.contributions_id,
                e.employee_id,
                CONCAT(e.last_name, ', ', e.first_name, ', ', e.suffix_title) AS Name,
                c.sss_ee,
                c.pag_ibig_ee,
                c.philhealth_ee,
                c.sss_er,
                c.pag_ibig_er,
                c.philhealth_er,
                c.medical_savings,
                c.retirement,
                c.mp2
            FROM contributions c
            JOIN employees e ON c.employee_id = e.employee_id
            WHERE c.contributions_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contribution_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $contribution = $result->fetch_assoc();
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
    $sss_ee = $_POST['sss_ee'];
    $sss_er = $_POST['sss_er'];
    $pagibig_ee = $_POST['pagibig_ee'];
    $pagibig_er = $_POST['pagibig_er'];
    $philhealth_ee = $_POST['philhealth_ee'];
    $philhealth_er = $_POST['philhealth_er'];
    $medical_savings = $_POST['medical_savings'];
    $retirement = $_POST['retirement'];
    $mp2 = $_POST['mp2'];

    // Validate numeric inputs
    if (
        !is_numeric($sss_ee) || !is_numeric($sss_er) || !is_numeric($pagibig_ee) || !is_numeric($pagibig_er) ||
        !is_numeric($philhealth_ee) || !is_numeric($philhealth_er) || !is_numeric($medical_savings) ||
        !is_numeric($retirement) || !is_numeric($mp2)
    ) {
        echo "<div class='alert alert-danger'>Please enter valid numeric values for all fields.</div>";
        exit;
    }

    $update_sql = "UPDATE contributions 
                   SET sss_ee = ?, pag_ibig_ee = ?, philhealth_ee = ?, sss_er = ?, 
                   pag_ibig_er = ?, philhealth_er = ?, medical_savings = ?, retirement = ?, mp2 = ?
                   WHERE contributions_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param(
        "dddddddddi",
        $sss_ee,
        $pagibig_ee,
        $philhealth_ee,
        $sss_er,
        $pagibig_er,
        $philhealth_er,
        $medical_savings,
        $retirement,
        $mp2,
        $contribution_id
    );

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Contributions updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating contribution: " . $stmt->error]);
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

<body>

    <?php include 'aside.php'; ?> <!-- This will import the sidebar -->
    <div class="container mt-5">
        <h3 class="text-center mb-4">NAME: <?= htmlspecialchars($contribution['Name']) ?></h3>

        <form method="POST">
            <div class="row">
                <!-- Employer Share Column -->
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded shadow">
                        <h5 class="text-center text-success fw-bold">Employer Share</h5>

                        <!-- SSS Employer Share -->
                        <div class="mb-3">
                            <label for="sss_er" class="form-label fw-semibold">SSS (Employer Share)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="sss_er" id="sss_er" class="form-control form-control-sm border-primary" value="<?= htmlspecialchars($contribution['sss_er']) ?>" required>
                            </div>
                        </div>

                        <!-- Pag-ibig Employer Share -->
                        <div class="mb-3">
                            <label for="pagibig_er" class="form-label fw-semibold">Pag-ibig (Employer Share)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="pagibig_er" id="pagibig_er" class="form-control form-control-sm border-primary" value="<?= htmlspecialchars($contribution['pag_ibig_er']) ?>" required>
                            </div>
                        </div>

                        <!-- PhilHealth Employer Share -->
                        <div class="mb-3">
                            <label for="philhealth_er" class="form-label fw-semibold">PhilHealth (Employer Share)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="philhealth_er" id="philhealth_er" class="form-control form-control-sm border-primary" value="<?= htmlspecialchars($contribution['philhealth_er']) ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employee Share Column -->
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded shadow">
                        <h5 class="text-center text-primary fw-bold">Employee Share</h5>

                        <!-- SSS Employee Share -->
                        <div class="mb-3">
                            <label for="sss_ee" class="form-label fw-semibold">SSS (Employee Share)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="sss_ee" id="sss_ee" class="form-control form-control-sm border-primary" value="<?= htmlspecialchars($contribution['sss_ee']) ?>" required>
                            </div>
                        </div>

                        <!-- Pag-ibig Employee Share -->
                        <div class="mb-3">
                            <label for="pagibig_ee" class="form-label fw-semibold">Pag-ibig (Employee Share)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="pagibig_ee" id="pagibig_ee" class="form-control form-control-sm border-primary" value="<?= htmlspecialchars($contribution['pag_ibig_ee']) ?>" required>
                            </div>
                        </div>

                        <!-- PhilHealth Employee Share -->
                        <div class="mb-3">
                            <label for="philhealth_ee" class="form-label fw-semibold">PhilHealth (Employee Share)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="philhealth_ee" id="philhealth_ee" class="form-control form-control-sm border-primary" value="<?= htmlspecialchars($contribution['philhealth_ee']) ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Savings Section -->
            <div class="mb-4">
                <h4 class="text-center mb-4 text-primary fw-bold">Savings</h4>

                <div class="d-flex justify-content-center gap-3">
                    <!-- Medical Savings Contribution -->
                    <div>
                        <label for="medical_savings" class="form-label fw-semibold">Medical</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" name="medical_savings" id="medical_savings"
                                class="form-control form-control-sm border-primary text-center"
                                value="<?= htmlspecialchars($contribution['medical_savings']) ?>" placeholder="Medical" style="max-width: 150px;" required>
                        </div>
                    </div>

                    <!-- Retirement Contribution -->
                    <div>
                        <label for="retirement" class="form-label fw-semibold">Retirement</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" name="retirement" id="retirement"
                                class="form-control form-control-sm border-primary text-center"
                                value="<?= htmlspecialchars($contribution['retirement']) ?>" placeholder="Retirement" style="max-width: 150px;" required>
                        </div>
                    </div>


                    <!-- mp2 Contribution -->
                    <div>
                        <label for="mp2" class="form-label fw-semibold">MP2</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" name="mp2" id="mp2"
                                class="form-control form-control-sm border-primary text-center"
                                value="<?= htmlspecialchars($contribution['retirement']) ?>" placeholder="mp2" style="max-width: 150px;" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="manage_contributions.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent normal form submission

            let formData = new FormData(this); // Get form data

            fetch("manage_contributions_edit.php?id=<?= $contribution_id ?>", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Success: " + data.message); // Success alert
                        window.location.href = "manage_contributions.php"; // Redirect after success
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