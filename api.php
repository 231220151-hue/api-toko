<?php

header("Content-Type: application/json");

// koneksi database
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "db_toko"
);

// cek koneksi
if (!$conn) {
    die(json_encode([
        "status" => "error",
        "message" => mysqli_connect_error()
    ]));
}

// ambil data
$query = mysqli_query($conn, "SELECT * FROM barang");

$data = [];

while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

// kirim json
echo json_encode($data);

?>
