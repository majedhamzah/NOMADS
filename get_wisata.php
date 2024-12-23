<?php
include('connection.php');

if (isset($_GET['id'])) {
    $scholarshipId = $_GET['id'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM Wisata WHERE id = ?");
    $stmt->bind_param("i", $scholarshipId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Return scholarship data as JSON
        echo json_encode($row);
    } else {
        echo json_encode(array()); // Return empty JSON if scholarship not found
    }
}

$conn->close();