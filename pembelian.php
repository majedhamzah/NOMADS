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
    echo "You must be logged in to make a purchase.";
    exit;
}

// Get user ID and wisata ID
$userId = $_SESSION['user_id'];
$wisataId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$status = "Di Pemesanan";

if ($wisataId === 0) {
    echo "Invalid wisata ID.";
    exit;
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO Pembelian (userId, wisataId, status) VALUES (?, ?, ?)");

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters and execute query
$stmt->bind_param("iis", $userId, $wisataId, $status); // Fixed: use 'iis' for integer, integer, string

if ($stmt->execute()) {
    echo "Purchase successful! Redirecting...";
    header("Refresh: 3; url=index.php"); // Redirect to the homepage after 3 seconds
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>