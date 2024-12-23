<?php
include 'connection.php';

// Query untuk mengambil data pesanan
$sql = "SELECT 
            pembelian.id AS pembelianId,
            Login_Register.Nama AS namaPemesan,
            wisata.Nama_Wisata AS namaWisata,
            pembelian.status
        FROM 
            pembelian
        JOIN 
            Login_Register ON pembelian.userId = Login_Register.User_Id
        JOIN 
            wisata ON pembelian.wisataId = wisata.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan</title>
    <link rel="stylesheet" href="./styles/admin_pemesanan.css">
</head>

<body>
    <div class="back-button">
        <button onclick="window.location.href='admin.php'">Back to Home</button>
    </div>

    <div class="container">
        <h1>Kelola Pesanan</h1>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pemesan</th>
                    <th>Nama Wisata</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['namaPemesan']}</td>
                    <td>{$row['namaWisata']}</td>
                    <td>{$row['status']}</td>
                  </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='6'>No orders found</td></tr>";
                }
                ?>
            </tbody>

        </table>
    </div>

    <script src="order.js"></script>
</body>

</html>