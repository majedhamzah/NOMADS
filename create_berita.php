<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php';

// Get form data
$beritaTitle = $_POST['addTitle'] ?? '';
$beritaDescription = $_POST['addDescription'] ?? '';

// Validate required fields
if (empty($beritaTitle) || empty($beritaDescription)) {
    echo "All fields are required.";
    exit();
}

// Initialize image variables
$imageData = null;
$imageType = null;

// Check if an image was uploaded
if (isset($_FILES['addImage']) && $_FILES['addImage']['error'] === UPLOAD_ERR_OK) {
    // Get image details
    $imageData = file_get_contents($_FILES['addImage']['tmp_name']);
    $imageType = $_FILES['addImage']['type'];
} else {
    echo "No image uploaded or there was an error with the image upload.";
    exit();
}

// Set character set for the connection (important for BLOB data)
$conn->set_charset('utf8mb4');

// Prepare and execute the insert query
$stmt = $conn->prepare("INSERT INTO berita (Judul, Deskripsi, Image, Image_type) VALUES (?, ?, ?, ?)");
if ($stmt) {
    // Bind the parameters for the prepared statement
    $stmt->bind_param("ssss", $beritaTitle, $beritaDescription, $imageData, $imageType);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Berita added successfully!";
        header("Location: admin_berita.php"); // Redirect to another page after success
        exit();
    } else {
        echo "Error inserting data: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Failed to prepare statement for insert: " . $conn->error;
    exit();
}

exit();
?>