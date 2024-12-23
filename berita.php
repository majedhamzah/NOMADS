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

// Fetch wisata data from the database
$wisataList = [];
$stmt = $conn->prepare("SELECT id, Judul, Deskripsi, Image, Image_type FROM Berita");
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
    <link rel="stylesheet" href="./styles/berita.css">
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
            <li><a href="Berita.php" class="active">Berita</a></li>
            <li><a href="pemesanan.php">Pemesanan</a></li>
            <li><a href="Review.php">Review</a></li>
            <li><a href="bantuan.php">Bantuan</a></li>
        </ul>
    </nav>

    <section class="destination">
        <div class="destination-container">
            <?php
            $count = 0; // Counter to limit the items
            foreach ($wisataList as $wisata): ?>
                <div class="card">
                    <img src="data:<?php echo htmlspecialchars($wisata['Image_type']); ?>;base64,<?php echo base64_encode($wisata['Image']); ?>"/>
                    <h4><?php echo htmlspecialchars($wisata['Judul']); ?></h4>
                    <p><?php echo htmlspecialchars($wisata['Deskripsi']); ?></p>
                </div>
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