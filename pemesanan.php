<?php
session_start();

include 'connection.php';
$isLoggedIn = false;
$userName = '';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fetch user name from the database
    $stmt = $conn->prepare("SELECT Nama FROM Login_Register WHERE User_Id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($userName);
    $stmt->fetch();
    $stmt->close();

    $isLoggedIn = true;
}


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogja en Luxe</title>
    <link rel="stylesheet" href="./styles/pemesanan.css">
</head>

<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <img src="./Screenshot 2024-12-16 165139.png">
                <span>Jogja en Luxe</span>
            </div>
            <div class="search-container">
                <form method="GET" action="wisata.php">
                    <input type="text" name="search" placeholder="Cari destinasi" class="search-input"
                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
            <nav class="top-nav">
                <?php if ($isLoggedIn): ?>
                    <div class="dropdown">
                        <button class="dropbtn">Hello, <?php echo htmlspecialchars($userName); ?> ▼</button>
                        <div class="dropdown-content">
                            <a href="profile.php">Profile</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <button class="login-button" onclick="location.href='login.php'">Masuk</button>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <nav class="main-nav">
        <ul class="nav-list">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="wisata.php">Wisata</a></li>
            <li><a href="Berita.php">Berita</a></li>
            <li><a href="pemesanan.php" class="active">Pemesanan</a></li>
            <li><a href="Review.php">Review</a></li>
            <li><a href="bantuan.php">Bantuan</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <div class="card-container">
            <?php

            // Enable error reporting for debugging
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            // Include database connection
            include 'connection.php';

            // Check if the user is logged in
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                echo "You must be logged in to view your bookings.";
                exit;
            }

            // Get user ID from session
            $userId = $_SESSION['user_id'];

            // Query to fetch wisata IDs by userId from the pembelian table with status 'Di Pemesanan'
            $sql = "SELECT * FROM pembelian WHERE userId = ? AND status = 'Di Pemesanan'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);

            if ($stmt->execute()) {
                $result = $stmt->get_result();


                // Check if there are any bookings for the user
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Get the wisataId from the result
                        $wisataId = $row['wisataId'];
                        $pembelianId = $row['id'];

                        // Fetch wisata details from the wisata table
                        $wisataSql = "SELECT * FROM wisata WHERE id = ?";
                        $wisataStmt = $conn->prepare($wisataSql);
                        $wisataStmt->bind_param("i", $wisataId);

                        if ($wisataStmt->execute()) {
                            $wisataResult = $wisataStmt->get_result();
                            if ($wisataResult->num_rows > 0) {
                                $wisata = $wisataResult->fetch_assoc();

                                // Check if image is a BLOB and convert it to base64
                                $imageData = $wisata['Image'];
                                $base64Image = base64_encode($imageData); // Convert BLOB to base64
            
                                // Display the wisata details in the card format
                                echo "<div class='card' id='card{$wisata['id']}'>";
                                echo "<img src='data:image/jpeg;base64,{$base64Image}' alt='{$wisata['Nama_Wisata']}' class='card-image'>";
                                echo "<div class='card-content'>";
                                echo "<h2>{$wisata['Nama_Wisata']}</h2>";
                                echo "<p class='location'>{$wisata['Lokasi']}</p>";
                                echo "<p class='date'>{$wisata['Tanggal']}</p>";
                                echo "<div class='price-section'>";
                                echo "<p class='price-label'>Total Price</p>";
                                echo "<p class='price'>IDR {$wisata['Harga']}</p>";
                                echo "</div>";
                                echo "<div class='button-group'>";
                                echo "<a href='booking.php?id={$pembelianId}' class='checkout-btn'>Checkout</a>";
                                echo "<a href='cancel.php?id={$pembelianId}' class='cancel-btn'>Cancel</a>";
                                echo "</div>";
                                echo "</div></div>";
                            } else {
                                echo "No wisata found for ID $wisataId.";
                            }
                        } else {
                            echo "Error fetching wisata details: " . $wisataStmt->error;
                        }
                        $wisataStmt->close();
                    }
                } else {
                    echo "You have no bookings.";
                }
            } else {
                echo "Error fetching pembelian data: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dropBtn = document.querySelector(".dropbtn");
            const dropdownContent = document.querySelector(".dropdown-content");

            if (dropBtn && dropdownContent) {
                dropBtn.addEventListener("click", function (event) {
                    event.stopPropagation();
                    dropdownContent.classList.toggle("show");
                });

                // Close the dropdown when clicking outside of it
                document.addEventListener("click", function () {
                    dropdownContent.classList.remove("show");
                });
            }
        });
    </script>
</body>

</html>