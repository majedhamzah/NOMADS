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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogja en Luxe</title>
    <link rel="stylesheet" href="./styles/review.css">
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
            <li><a href="Wisata.php">Wisata</a></li>
            <li><a href="Berita.php">Berita</a></li>
            <li><a href="pemesanan.php">Pemesanan</a></li>
            <li><a href="Review.php" class="active">Review</a></li>
            <li><a href="bantuan.php">Bantuan</a></li>
        </ul>
    </nav>

    <div class="content">
        <h1>here's what people say about us</h1>

        <div class="dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>

        <div class="testimonials">
            <div class="testimonial">
                <strong>Brilliant Tour</strong>
                <p>Germany</p>
                <p>One of the best tours ever! Highly recommended.</p>
            </div>
            <div class="testimonial">
                <strong>Brilliant Tour</strong>
                <p>Germany</p>
                <p>One of the best tours ever! Highly recommended.</p>
            </div>
            <div class="testimonial">
                <strong>Brilliant Tour</strong>
                <p>Germany</p>
                <p>One of the best tours ever! Highly recommended.</p>
            </div>
            <div class="testimonial">
                <strong>Brilliant Tour</strong>
                <p>Germany</p>
                <p>One of the best tours ever! Highly recommended.</p>
            </div>
            <div class="testimonial">
                <strong>Brilliant Tour</strong>
                <p>Germany</p>
                <p>One of the best tours ever! Highly recommended.</p>
            </div>
            <div class="testimonial">
                <strong>Brilliant Tour</strong>
                <p>Germany</p>
                <p>One of the best tours ever! Highly recommended.</p>
            </div>
            <div class="testimonial">
                <strong>Brilliant Tour</strong>
                <p>Germany</p>
                <p>One of the best tours ever! Highly recommended.</p>
            </div>
            <div class="testimonial">
                <strong>Brilliant Tour</strong>
                <p>Germany</p>
                <p>One of the best tours ever! Highly recommended.</p>
            </div>
        </div>
    </div>
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