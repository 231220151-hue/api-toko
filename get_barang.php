<?php
header("Content-Type: application/json");

include 'koneksi.php';

$cari = $_GET['cari'] ?? '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$limit = 5;
$offset = ($page - 1) * $limit;

$cari_aman = mysqli_real_escape_string($koneksi, $cari);

$queryTotal = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM barang WHERE nama_barang LIKE '%$cari_aman%'"
);

$rowTotal = mysqli_fetch_assoc($queryTotal);
$total_data = (int) $rowTotal['total'];
$total_halaman = ceil($total_data / $limit);

if($total_halaman < 1){
    $total_halaman = 1;
}

$queryData = mysqli_query(
    $koneksi,
    "SELECT * FROM barang 
     WHERE nama_barang LIKE '%$cari_aman%' 
     ORDER BY id DESC 
     LIMIT $limit OFFSET $offset"
);

$data_barang = [];

while($row = mysqli_fetch_assoc($queryData)){
    $data_barang[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $data_barang,
    "total_data" => $total_data,
    "total_halaman" => $total_halaman,
    "halaman_saat_ini" => $page
]);
?>