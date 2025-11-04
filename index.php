<?php
session_start();
$success_message = '';
$error_message = '';
$success_message_title = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    // Hapus session agar tidak muncul lagi setelah refresh
    unset($_SESSION['success_message']); 
}
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); 
}
if (isset($_SESSION['success_message_title'])) {
    $success_message_title = $_SESSION['success_message_title'];
    unset($_SESSION['success_message_title']);
}

include 'config/database.php';
include 'config/footer.php';

$select = "SELECT * FROM `tugas`";
if (isset($_POST['cari'])) {
    $keyword = $_POST['cari'];
    $safe_keyword = "%" . $connect->real_escape_string($keyword) . "%";
    $select = "SELECT * FROM `tugas` WHERE `hp` LIKE '$safe_keyword' OR `tipe` LIKE '$safe_keyword' ";
}
$result = mysqli_query($connect, $select);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Wahyu Khoirur Rizal</title>
    <!-- bootsrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- jquery -->
     <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="full-height d-flex justify-content-center align-items-center">

        <div class="p-3 text-center" style="width: 80%;">
            
            <h1 class="mb-4">Daftar Table</h1>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="w-25">
                    <form action="" method="post" class="d-flex align-items-center gap-2">
                            <label class="form-label">Cari</label>
                            <input type="text" name="cari" class="form-control" placeholder="Cari berdasarkan Brand atau Tipe">
                            <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>

                <form action="form.php" method="post">
                    <input type="hidden" name="form" value="insert">
                    <button type="submit" class="btn btn-success">Tambah</button>
                </form>
            </div>
            
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Tipe</th>
                        <th scope="col">Total</th>
                        <th scope="col">Grade</th>
                        <th scope="col">action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<th scope='row'>" . $no++ . "</th>";
                        echo "<td>" . htmlspecialchars($row['hp']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tipe']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['grade']) . "</td>";
                    ?>
                    <td>
                        <form action="form.php" method="post" class="d-inline">
                            <input type="hidden" name="form" value="update">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-warning">Edit</button>
                        </form>
                        <button type="button" class="btn btn-danger delete-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#confirmDeleteModal"
                                data-id="<?php echo $row['id'] ?>" 
                                data-hp="<?php echo htmlspecialchars($row['hp']); ?>">
                            Delete
                        </button>
                    </td>
                    <?php
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?= myFooter() ?>

    <!-- modal error -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">Terjadi Kesalahan! ⚠️</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><?= $error_message ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal success -->
    <div class="modal fade" id="successModalInsert" tabindex="-1" aria-labelledby="successModalInsertLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalInsertLabel"><?= $success_message_title ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= $success_message ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="confirmDeleteModalLabel">Konfirmasi Penghapusan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus tugas: 
                    <strong id="modal-delete-task-name" class="text-danger"></strong> 
                    dengan ID: <strong id="modal-delete-task-id" class="text-danger"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    
                    <form action="action.php" method="post">
                        <input type="hidden" name="id" id="modal-delete-id" value="">
                        <input type="hidden" name="form" value="delete">
                        <button type="submit" class="btn btn-danger">Hapus Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Cek apakah ada pesan sukses dari PHP
        <?php if (!empty($success_message)): ?>
            // Jika ada, tampilkan modal sukses
            const successModalInsert = new bootstrap.Modal(document.getElementById('successModalInsert'));
            successModalInsert.show();
        <?php endif; ?>

        // Cek apakah ada pesan error dari PHP
        <?php if (!empty($error_message)): ?>
            // Jika ada, tampilkan modal error
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        <?php endif; ?>
    </script>
</body>
</html>