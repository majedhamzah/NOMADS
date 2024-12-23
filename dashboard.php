<?php
include 'connection.php';
// Fetch wisata data from the database
$query = "SELECT * FROM wisata";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Wisata</title>
    <link rel="stylesheet" href="./styles/dashboard.css">
</head>

<body>

    <div class="back-button">
        <button onclick="window.location.href='admin.php'">Back to Home</button>
    </div>

    <div class="container">
        <h1>Daftar Wisata</h1>
        <button id="addButton" onclick="openModal()">Tambah Wisata</button>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Wisata</th>
                    <th>Lokasi</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['Nama_Wisata']); ?></td>
                            <td><?php echo htmlspecialchars($row['Lokasi']); ?></td>
                            <td><?php echo htmlspecialchars($row['Harga']); ?></td>
                            <td><?php echo htmlspecialchars($row['Deskripsi']); ?></td>
                            <td>
                                <button
                                    onclick="openModal(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['Nama_Wisata']); ?>', '<?php echo htmlspecialchars($row['Lokasi']); ?>', '<?php echo htmlspecialchars($row['Harga']); ?>', '<?php echo htmlspecialchars($row['Deskripsi']); ?>')">Edit</button>
                                <form action="delete_wisata.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit">Hapus</button>
                                </form>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Tambah/Edit Wisata</h2>
            <form id="wisata-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editId">
                <input type="text" name="wisata_title" id="wisataTitle" placeholder="Judul Wisata" required>
                <textarea name="wisata_description" id="wisataDescription" placeholder="Deskripsi Wisata"
                    required></textarea>
                <input type="text" name="location" id="location" placeholder="Lokasi" required>
                <input type="number" name="harga" id="harga" placeholder="Harga" required>
                <input type="date" name="tanggal" id="tanggal" placeholder="Tanggal">
                <input type="file" name="gambar" id="gambar" accept="image/*">
                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id = null, title = '', location = '', price = '', description = '') {
            document.getElementById("modal").style.display = "block";
            document.getElementById("editId").value = id || ''; // if id is null, set as empty
            document.getElementById("wisataTitle").value = title;
            document.getElementById("location").value = location;
            document.getElementById("harga").value = price;
            document.getElementById("wisataDescription").value = description;
            document.getElementById("modalTitle").textContent = id ? "Edit Wisata" : "Tambah Wisata";

            // Dynamically set form action
            const form = document.getElementById("wisata-form");
            if (id) {
                form.action = "./wisata_update.php"; // If editing, use update_wisata.php
            } else {
                form.action = "./save_wisata.php"; // If adding, use save_wisata.php
            }
        }

        function closeModal() {
            document.getElementById("modal").style.display = "none";
            document.getElementById("wisata-form").reset();
        }
    </script>

</body>

</html>