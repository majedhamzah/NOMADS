<?php
include 'connection.php';

// Check if there's an id and handle the update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $beritaTitle = $_POST['addTitle'] ?? '';
    $beritaDescription = $_POST['addDescription'] ?? '';

    // Initialize image variables
    $imageData = null;
    $imageType = null;

    // Check if an image is uploaded and handle it
    if (isset($_FILES['addImage']) && $_FILES['addImage']['error'] === UPLOAD_ERR_OK) {
        // Get image details
        $imageData = file_get_contents($_FILES['addImage']['tmp_name']);
        $imageType = $_FILES['addImage']['type'];
    }

    // Prepare the update query (only update the image if a new one is uploaded)
    if ($imageData) {
        $stmt = $conn->prepare("UPDATE Berita SET Judul = ?, Deskripsi = ?, Image = ?, Image_type = ? WHERE id = ?");
        $stmt->bind_param("ssbsi", $beritaTitle, $beritaDescription, $imageData, $imageType, $id);
    } else {
        $stmt = $conn->prepare("UPDATE Berita SET Judul = ?, Deskripsi = ? WHERE id = ?");
        $stmt->bind_param("ssi", $beritaTitle, $beritaDescription, $id);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo "Berita updated successfully!";
        header("Location: admin_berita.php");
        exit();
    } else {
        // Print MySQL error
        echo "Error updating berita: " . $stmt->error;
    }

    $stmt->close();
}
?>