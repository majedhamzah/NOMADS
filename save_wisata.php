<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php';

// Get form data
$wisataTitle = $_POST['wisata_title'] ?? '';
$wisataDescription = $_POST['wisata_description'] ?? '';
$location = $_POST['location'] ?? '';
$harga = $_POST['harga'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';

// Validate required fields
if (empty($wisataTitle) || empty($wisataDescription) || empty($location) || empty($harga)) {
    echo "All fields are required.";
    exit();
}

// Initialize image variables
$imageData = null;
$imageType = null;

// Check if an image was uploaded
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    // Get image details
    $imageData = file_get_contents($_FILES['gambar']['tmp_name']);
    $imageType = $_FILES['gambar']['type'];
} else {
    echo "No image uploaded or there was an error with the image upload.";
    exit();
}

// Insert new record with image as BLOB
$stmt = $conn->prepare("INSERT INTO wisata (Nama_Wisata, Deskripsi, Lokasi, Harga, Tanggal, Image, Image_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("sssisss", $wisataTitle, $wisataDescription, $location, $harga, $tanggal, $imageData, $imageType);

    if ($stmt->execute()) {
        echo "Wisata added successfully!";
        header("Location: dashboard.php");
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