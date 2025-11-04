<?php
session_start();
include 'config/database.php';
include 'config/route.php';
include 'config/footer.php';

$typeForm = '';
if (isset($_GET['form'])) {
    $typeForm = $_GET['form'];
    $title = "Tambah Data Table";
    $form = "add";
    $action = "insert";
} elseif (isset($_POST['form'])) {
    $typeForm = $_POST['form'];
}else {
    $_SESSION['error_message'] = "Aksi tidak dikenali.";
    redirect("index.php");
}
$error_message = '';
$hp = '';
$tipe = '';
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); 
}

if ($typeForm === 'insert') {
    $title = "Tambah Data Table";
    $form = "add";
    $action = "insert";
} elseif ($typeForm === 'update' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $selectQuery = "SELECT * FROM tugas WHERE id = $id";
    $result = mysqli_query($connect, $selectQuery);
    $data = mysqli_fetch_assoc($result);
    $hp = $data['hp'];
    $tipe = $data['tipe'];
    $title = "Edit Data Table";
    $form = "edit";
    $action = "update";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Wahyu Khoirur Rizal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="full-height d-flex justify-content-center align-items-center">

        <div class="p-5 ml-5" style="width: 80%;">
            <div class="d-flex align-items-center mb-4">
                <a href="index.php" class="text-decoration-none me-3">
                    <button type="button" class="btn btn-secondary d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        <span class="ms-2 d-none d-sm-inline">Kembali</span>
                    </button>
                </a>
                
                <h1 class="m-0"><?= $title ?></h1>
            </div>
            <form action="action.php" method="post">
                <div class="mb-3">
                    <label for="brand" class="form-label">Brand HP</label>
                    <input type="text" class="form-control" value="<?= $hp ?>" id="brand" name="brand" placeholder="Masukkan Brand HP" required>
                </div>
                <div class="mb-3">
                    <label for="tipe" class="form-label">Tipe HP</label>
                    <input type="text" class="form-control" id="tipe" value="<?= $tipe ?>" name="tipe" placeholder="Masukkan Tipe HP" required>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" placeholder="Masukkan Harga" min="1" max="2147483648" required>
                </div>
                <div class="mb-3">
                    <label for="jumlah" class="form-label">jumlah</label>
                    <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan jumlah" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="kualitas" class="form-label">Kualitas</label>
                    <input type="number" class="form-control" id="kualitas" name="kualitas" placeholder="Masukkan Kualitas" max="100" required>
                </div>
                <input type="hidden" name="form" value="<?= $action ?>">
                <?php if ($typeForm === 'update'): ?>
                    <input type="hidden" name="id" value="<?= $id ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Cek apakah ada pesan error dari PHP
        <?php if (!empty($error_message)): ?>
            // Jika ada, tampilkan modal error
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        <?php endif; ?>
    </script>
</body>
</html>