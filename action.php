<?php
session_start();

// var_dump($_POST);
include 'config/database.php';
include 'config/route.php';

$typeForm = isset($_POST['form']) ? $_POST['form'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$brand = isset($_POST['brand']) ? $_POST['brand'] : '';
$tipe = isset($_POST['tipe']) ? $_POST['tipe'] : '';
$harga = isset($_POST['harga']) ? (int) $_POST['harga'] : 0;
$jumlah = isset($_POST['jumlah']) ? (int) $_POST['jumlah'] : 0;
$kualitas = isset($_POST['kualitas']) ? (int) $_POST['kualitas'] : 0;

if ($typeForm === 'insert') {
    insertData($connect);
} elseif ($typeForm === 'update' && isset($_POST['id'])) {
    updateData($connect);
} elseif ($typeForm === 'delete' && isset($_POST['id'])) {
    deleteData($connect);
} else {
    $_SESSION['error_message'] = "Aksi tidak dikenali.";
    redirect("index.php");
}

function insertData($connect) : void
{
    global $brand, $tipe, $harga, $jumlah, $kualitas;

    $total = sumTotal($harga, $jumlah);

    $grade = grading($kualitas);

    $insertQuery = "INSERT INTO tugas (hp, tipe, total, grade) VALUES ('$brand', '$tipe', $total, '$grade')";
    try {
        
       if (mysqli_query($connect, $insertQuery)) {
           $_SESSION['success_message_title'] = "Berhasil ditambahkan! ✅";
            $_SESSION['success_message'] = "Data <strong class='text-success'>$brand - $tipe </strong>berhasil ditambahkan";
            redirect("index.php"); 

        } else {
            $_SESSION['error_message'] = "Gagal menambahkan data. Error: " . mysqli_error($connect);
            redirect("form.php?form=add");
        }
    } catch (\Throwable $th) {
        $_SESSION['error_message'] = "Terjadi kesalahan: " . $th->getMessage();
        redirect("form.php?form=add");
    } finally {
        $connect->close();
    }
}

function updateData($connect) : void
{
    global $id, $brand, $tipe, $harga, $jumlah, $kualitas;

    $total = sumTotal($harga, $jumlah);
    $grade = grading($kualitas);

    $updateQuery = "UPDATE tugas SET hp='$brand', tipe='$tipe', total=$total, grade='$grade' WHERE id=$id";
    try {
        mysqli_query($connect, $updateQuery);
        $_SESSION['success_message_title'] = "Berhasil update 🔁";
        $_SESSION['success_message'] = "Data <strong class='text-warning'>$brand - $tipe </strong> berhasil diupdate.";
        redirect("index.php");
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Gagal mengupdate data. Error: " . $e->getMessage();
        redirect("index.php");
    } finally {
        $connect->close();
    }
}

function deleteData($connect) : void {
    global $id;
    $deleteQuery = "DELETE FROM tugas WHERE id=$id";
    $selectQuery = "SELECT hp, tipe FROM tugas WHERE id = $id";
    try {
        $result = mysqli_query($connect, $selectQuery);
        $data = mysqli_fetch_assoc($result);
        $brand = $data['hp'];
        $tipe = $data['tipe'];
        mysqli_query($connect, $deleteQuery);
        $_SESSION['success_message_title'] = "Berhasil Dihapus! 🗑️";
        $_SESSION['success_message'] = "Data <strong class='text-danger'>$brand - $tipe </strong> berhasil dihapus.";
        redirect("index.php");
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Gagal menghapus data. Error: " . $e->getMessage();
        redirect("index.php");
    } finally {
        $connect->close();
    }
}

function sumTotal($a, $b) : int {
    return $a * $b;    
}

function grading(int $q) : string {
    $grade = '';
    switch ($q) {
        case ($q >= 80):
            $grade .= "A";
            break;
        case ($q >= 51):
            $grade .= "B";
            break;
        default:
            $grade .= "C";
            break;
    }
    return $grade;
}