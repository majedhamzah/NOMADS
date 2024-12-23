<?php
// Start session
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "You must be logged in to access this page.";
    exit;
}

// Check if the 'id' parameter is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid booking ID.";
    exit;
}

// Get the booking ID from the URL
$pembelianId = $_GET['id'];

// Prepare SQL statement to update the status
$sql = "UPDATE pembelian SET status = 'Sudah dibayar' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pembelianId);

// Execute the statement
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Booking status updated to 'Sudah dibayar'.";
        // Optionally redirect to a confirmation page or back to pemesanan.php
        header("Location: pemesanan.php");
        exit;
    } else {
        echo "No booking found with the specified ID.";
    }
} else {
    echo "Error updating booking status: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>