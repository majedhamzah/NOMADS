<?php
include 'connection.php';

// Check if there's an id and handle the update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $wisataTitle = $_POST['wisata_title'];
    $location = $_POST['location'];
    $price = $_POST['harga'];
    $description = $_POST['wisata_description'];

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE wisata SET Nama_Wisata = ?, Lokasi = ?, Harga = ?, Deskripsi = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $wisataTitle, $location, $price, $description, $id);

    // Execute the query
    if ($stmt->execute()) {
        echo "Wisata updated successfully!";
        header("Location: dashboard.php");
    } else {
        // Print MySQL error
        echo "Error updating wisata: " . $stmt->error;
    }

    $stmt->close();
}
?>