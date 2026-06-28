<?php
header("Content-Type: application/json");

include 'koneksi.php';
include 'auth.php';

$conn = $koneksi;

wajib_login($conn);

$id = $_POST['id'] ?? '';
$nama = $_POST['nama_barang'] ?? '';
$harga = $_POST['harga'] ?? 0;
$stok = $_POST['stok'] ?? 0;
$gambar = null;

if($id == '' || $nama == '' || $harga == '' || $stok == ''){
    echo json_encode([
        "status" => "error",
        "message" => "Data barang wajib lengkap"
    ]);
    exit;
}

if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK){

    $uploadDir = __DIR__ . "/uploads/";

    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    $tmpFile = $_FILES['gambar']['tmp_name'];
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];

    $ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];

    $mimeValid = ['image/jpeg', 'image/png', 'image/webp'];
    $mimeFile = mime_content_type($tmpFile);

    if(!in_array($ekstensi, $ekstensiValid) || !in_array($mimeFile, $mimeValid)){
        echo json_encode([
            "status" => "error",
            "message" => "Format file harus JPG, PNG, atau WEBP"
        ]);
        exit;
    }

    if($ukuranFile > 2 * 1024 * 1024){
        echo json_encode([
            "status" => "error",
            "message" => "Ukuran gambar maksimal 2MB"
        ]);
        exit;
    }

    $namaBaru = "barang_" . time() . "_" . rand(1000, 9999) . "." . $ekstensi;
    $tujuan = $uploadDir . $namaBaru;

    if(!move_uploaded_file($tmpFile, $tujuan)){
        echo json_encode([
            "status" => "error",
            "message" => "Gagal mengupload gambar"
        ]);
        exit;
    }

    $gambar = "uploads/" . $namaBaru;
}

if($gambar){
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE barang SET nama_barang = ?, harga = ?, stok = ?, gambar = ? WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, "siisi", $nama, $harga, $stok, $gambar, $id);
}else{
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE barang SET nama_barang = ?, harga = ?, stok = ? WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, "siii", $nama, $harga, $stok, $id);
}

if(mysqli_stmt_execute($stmt)){
    echo json_encode([
        "status" => "success",
        "message" => "Barang berhasil diupdate"
    ]);
}else{
    echo json_encode([
        "status" => "error",
        "message" => "Barang gagal diupdate"
    ]);
}
?>