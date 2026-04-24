<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "db_toko");

if ($conn->connect_error) {
    die(json_encode(["error" => $conn->connect_error]));
}

$sql = "SELECT * FROM barang";
$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>