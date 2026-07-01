<?php
header("Content-Type: application/json");

include 'koneksi.php';

$query = "
    SELECT nama_barang, harga
    FROM barang
    ORDER BY harga DESC
    LIMIT 5
";

$hasil = mysqli_query($koneksi, $query);

if (!$hasil) {
    echo json_encode([
        "status" => "error",
        "message" => "Query statistik gagal: " . mysqli_error($koneksi)
    ]);
    exit;
}

$labels = [];
$values = [];

while ($row = mysqli_fetch_assoc($hasil)) {
    $labels[] = $row['nama_barang'];
    $values[] = (int) $row['harga'];
}

echo json_encode([
    "status" => "success",
    "chart_data" => [
        "labels" => $labels,
        "values" => $values
    ]
]);
?>