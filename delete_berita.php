<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include 'connection.php';

// Check if 'delete_id' is set in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    // Start a transaction to ensure atomicity (all or nothing)
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE FROM berita WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare delete statement for wisata: " . $conn->error);
        }

        // Commit the transaction
        $conn->commit();

        // Redirect back to the dashboard
        header("Location: admin_berita.php");
        exit();
    } catch (Exception $e) {
        // If there was any error, rollback the transaction
        $conn->rollback();

        // Output the error message
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No ID provided or wrong request method.";
}
?>