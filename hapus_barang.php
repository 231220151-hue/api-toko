<?php
header("Content-Type: application/json");

include 'koneksi.php';
include 'auth.php';

$conn = $koneksi;

wajib_login($conn);

$id = $_POST['id'] ?? '';

if($id === ''){
    echo json_encode([
        "status" => "error",
        "message" => "ID barang wajib dikirim"
    ]);
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM barang WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if(mysqli_stmt_execute($stmt)){
    echo json_encode([
        "status" => "success",
        "message" => "Barang berhasil dihapus"
    ]);
}else{
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menghapus barang"
    ]);
}
?>