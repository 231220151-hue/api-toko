<?php
header("Content-Type: application/json");

include 'koneksi.php';

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode([
        "status" => "error",
        "message" => "Username dan password wajib diisi"
    ]);
    exit;
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM users WHERE username = ? AND password = ? LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Username atau password salah"
    ]);
    exit;
}

mysqli_stmt_bind_result($stmt, $user_id);
mysqli_stmt_fetch($stmt);

$token = md5(uniqid(rand(), true));

$update = mysqli_prepare(
    $koneksi,
    "UPDATE users SET token = ? WHERE id = ?"
);

mysqli_stmt_bind_param($update, "si", $token, $user_id);
mysqli_stmt_execute($update);

echo json_encode([
    "status" => "success",
    "message" => "Login berhasil",
    "token" => $token
]);
?>