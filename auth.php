<?php
function ambil_token_authorization()
{
    $token = '';

    if (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();

        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'authorization') {
                $token = trim($value);
                break;
            }
        }
    }

    if ($token === '' && isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $token = trim($_SERVER['HTTP_AUTHORIZATION']);
    }

    if ($token === '' && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $token = trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
    }

    if ($token === '' && isset($_SERVER['HTTP_X_AUTHORIZATION'])) {
        $token = trim($_SERVER['HTTP_X_AUTHORIZATION']);
    }

    if (stripos($token, 'Bearer ') === 0) {
        $token = trim(substr($token, 7));
    }

    return $token;
}

function wajib_login($koneksi)
{
    $token = ambil_token_authorization();

    if ($token === '') {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Akses Ditolak! Token kosong."
        ]);
        exit;
    }

    $stmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE token = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 0) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Akses Ditolak! Token invalid."
        ]);
        exit;
    }
}
?>