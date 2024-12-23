<?php
include 'connection.php';
// Fetch berita data from the database
$query = "SELECT * FROM Berita";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Berita</title>
    <link rel="stylesheet" href="./styles/admin_berita.css">
</head>

<body>

    <div class="back-button">
        <button onclick="window.location.href='admin.php'">Back to Home</button>
    </div>

    <div class="container">
        <h1>Daftar Berita</h1>
        <button id="addNewsButton" onclick="openModal()">Tambah Berita</button>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Berita</th>
                    <th>Isi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="newsTableBody">
                <!-- Dynamic content will be inserted here -->
                <?php
                if ($result->num_rows > 0) {
                    $counter = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $counter . "</td>
                                <td>" . $row['Judul'] . "</td>
                                <td>" . $row['Deskripsi'] . "</td>
                                <td>
                                    <button onclick='openModal({$row['id']}, \"{$row['Judul']}\", \"{$row['Deskripsi']}\")'>Edit</button>
                                   <form action=\"delete_berita.php\" method=\"POST\" style=\"display:inline;\">
                                    <input type=\"hidden\" name=\"delete_id\" value=\"{$row['id']}\">
                                    <button type=\"submit\">Hapus</button>
                                </form>
                                </td>
                            </tr>";
                        $counter++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Tambah/Edit Berita</h2>
            <form id="addForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editId" name="id">
                <label for="addTitle">Judul Berita:</label>
                <input type="text" id="addTitle" name="addTitle" required>

                <label for="addDescription">Deskripsi:</label>
                <textarea id="addDescription" name="addDescription" required></textarea>

                <label for="addImage">Gambar Berita:</label>
                <input type="file" id="addImage" name="addImage" accept="image/*">

                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        // Open Modal for Add or Edit
        function openModal(id = null, title = '', description = '') {
            document.getElementById("addModal").style.display = "block";
            document.getElementById("editId").value = id || ''; // If id is null, set as empty
            document.getElementById("addTitle").value = title;
            document.getElementById("addDescription").value = description;
            document.getElementById("modalTitle").textContent = id ? "Edit Berita" : "Tambah Berita";

            // Dynamically set form action
            const form = document.getElementById("addForm");
            if (id) {
                form.action = "./update_berita.php"; // If editing, use update_berita.php
            } else {
                form.action = "./create_berita.php"; // If adding, use create_berita.php
            }
        }

        // Close the modal
        function closeModal() {
            document.getElementById("addModal").style.display = "none";
        }

    </script>

</body>

</html>