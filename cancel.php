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
    echo "You must be logged in to cancel a booking.";
    exit;
}

// Check if the booking ID is passed via the URL
if (isset($_GET['id'])) {
    $pembelianId = $_GET['id']; // Get the booking ID from the URL

    // Get user ID from session
    $userId = $_SESSION['user_id'];

    // Prepare SQL query to check if the booking belongs to the logged-in user
    $sql = "SELECT * FROM pembelian WHERE id = ? AND userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $pembelianId, $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if a booking record was found
        if ($result->num_rows > 0) {
            // Booking found, proceed to delete
            $deleteSql = "DELETE FROM pembelian WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $pembelianId);

            if ($deleteStmt->execute()) {
                // Redirect to the pemesanan.php page with a success message
                header("Location: pemesanan.php");
                exit;
            } else {
                echo "Error canceling booking: " . $deleteStmt->error;
            }
        } else {
            echo "No booking found for this ID or you do not have permission to cancel it.";
        }
    } else {
        echo "Error checking booking: " . $stmt->error;
    }

    $stmt->close();
    $deleteStmt->close();
} else {
    echo "No booking ID provided.";
}

// Close the database connection
$conn->close();
?>