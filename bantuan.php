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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan - Jogja en Luxe</title>
    <link rel="stylesheet" href="./styles/bantuan.css">
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
            <li><a href="pemesanan.php">Pemesanan</a></li>
            <li><a href="Review.php">Review</a></li>
            <li><a href="bantuan.php" class="active">Bantuan</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <h1>Pusat Bantuan</h1>

        <div class="search-help">
            <input type="text" placeholder="Ketik topik yang ingin dicari" class="help-search-input">
        </div>

        <section class="help-section">
            <h2>Topik Populer</h2>
            <div class="topic-list">
                <a href="#" class="topic-item">
                    <span>Cara Reschedule Tiket Wisata</span>
                    <span class="arrow">›</span>
                </a>
                <a href="#" class="topic-item">
                    <span>Cara Refund Tiket Wisata</span>
                    <span class="arrow">›</span>
                </a>
                <a href="#" class="topic-item">
                    <span>Cara Memesan Tiket Online</span>
                    <span class="arrow">›</span>
                </a>
                <a href="#" class="topic-item">
                    <span>Cara Memberi Ulasan</span>
                    <span class="arrow">›</span>
                </a>
                <a href="#" class="topic-item">
                    <span>Cara Melakukan Pembayaran</span>
                    <span class="arrow">›</span>
                </a>
            </div>
        </section>

        <section class="help-section">
            <h2>Butuh Bantuan ?</h2>
        </section>
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