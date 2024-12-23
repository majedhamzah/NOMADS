<?php
// Start the session
session_start();

// Include database connection
include 'connection.php';

// Get the ID from the query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the destination details from the database based on the ID
$stmt = $conn->prepare("SELECT * FROM wisata WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$wisata = $result->fetch_assoc();

if (!$wisata) {
    echo "Wisata not found!";
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Wisata - <?php echo htmlspecialchars($wisata['Nama_Wisata']); ?></title>
    <link rel="stylesheet" href="./styles/detail.css">
</head>

<body>
    <div class="container">
        <div class="back-button">
            <a href="index.php">
                <button>← Kembali ke Beranda</button>
            </a>
        </div>

        <div class="container">
            <div class="image-container">
                <img src="data:<?php echo htmlspecialchars($wisata['Image_type']); ?>;base64,<?php echo base64_encode($wisata['Image']); ?>"
                    alt="<?php echo htmlspecialchars($wisata['Nama_Wisata']); ?>" class="destination-image">
                <div class="info-below">
                    <p class="location">
                        <span class="icon">📍</span> <?php echo htmlspecialchars($wisata['Lokasi']); ?>
                    </p>
                    <p class="time">
                        <span class="icon">⏰</span> <?php echo htmlspecialchars($wisata['Tanggal']); ?>
                    </p>
                    <h2>Deskripsi</h2>
                    <p class="description">
                        <?php echo nl2br(htmlspecialchars($wisata['Deskripsi'])); ?>
                    </p>
                </div>
            </div>

            <div class="content">
                <h1 class="title"><?php echo htmlspecialchars($wisata['Nama_Wisata']); ?></h1>
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <a href="pembelian.php?id=<?php echo $wisata['id']; ?>" class="btn-buy">Beli</a>
                <?php else: ?>
                    <p class="login-message">Please <a href="login.php">log in</a> to purchase tickets.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>