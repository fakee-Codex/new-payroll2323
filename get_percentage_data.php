<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change with your database username
$password = ""; // Change with your database password
$dbname = "gfi_exel"; // Change with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest contribution percentages
$sql = "SELECT * FROM contribution_percentages ORDER BY percentage_id DESC LIMIT 1";
$result = $conn->query($sql);

$data = null;
if ($result->num_rows > 0) {
    // Fetch the data
    $data = $result->fetch_assoc();
} 

// Return data as JSON
echo json_encode($data);

// Close connection
$conn->close();
?>
