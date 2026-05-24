<?php

header('Content-Type: application/json');

include 'koneksi.php';

$sql = "SELECT * FROM barang";

$result = mysqli_query($koneksi,$sql);

$data=[];

while($row=mysqli_fetch_assoc($result)){
    $data[]=$row;
}

echo json_encode($data);

?>
