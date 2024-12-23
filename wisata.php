<?php
// Start session
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

// Fetch wisata data with optional search query
$wisataList = [];
$searchQuery = $_GET['search'] ?? '';

if (!empty($searchQuery)) {
    // If a search query exists, filter by name
    $searchQuery = "%{$searchQuery}%";
    $stmt = $conn->prepare("SELECT Id, Nama_Wisata, Deskripsi, Lokasi, Harga, Tanggal, Image, Image_type FROM wisata WHERE Nama_Wisata LIKE ?");
    $stmt->bind_param("s", $searchQuery);
} else {
    // Otherwise, fetch all data
    $stmt = $conn->prepare("SELECT Id, Nama_Wisata, Deskripsi, Lokasi, Harga, Tanggal, Image, Image_type FROM wisata");
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $wisataList[] = $row;
}

$stmt->close();
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogja en Luxe</title>
    <link rel="stylesheet" href="./styles/wisata.css">
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
            <li><a href="wisata.php" class="active">Wisata</a></li>
            <li><a href="Berita.php">Berita</a></li>
            <li><a href="pemesanan.php">Pemesanan</a></li>
            <li><a href="Review.php">Review</a></li>
            <li><a href="bantuan.php">Bantuan</a></li>
        </ul>
    </nav>

    <section class="destination">
        <h2 class="section-title">Destinasi Impian di Yogyakarta</h2>
        <div class="destination-container">
            <?php
            foreach ($wisataList as $wisata):
                ?>
                <a href="detail.php?id=<?php echo $wisata['Id']; ?>" class="destination-link">
                    <div class="card">
                        <img src="data:<?php echo htmlspecialchars($wisata['Image_type']); ?>;base64,<?php echo base64_encode($wisata['Image']); ?>"
                            alt="<?php echo htmlspecialchars($wisata['Nama_Wisata']); ?>">
                        <h4><?php echo htmlspecialchars($wisata['Nama_Wisata']); ?></h4>
                        <p><?php echo htmlspecialchars($wisata['Lokasi']); ?></p>
                        <p class="price">IDR <?php echo number_format($wisata['Harga'], 0, ',', '.'); ?></p>
                        <p><?php echo htmlspecialchars($wisata['Tanggal']); ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        </div>
    </section>
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