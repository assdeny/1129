<?php
header("Content-Type: application/json");
require_once 'config.php';

// Baca data JSON
$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// Cek input kosong
if (empty($username) || empty($password)) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'status' => false,
        'message' => 'Username dan password wajib diisi.'
    ]);
    exit;
}

// Query user berdasarkan username
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah user ditemukan
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verifikasi password (belum menggunakan hash)
    if ($password === $user['password']) {
        http_response_code(200); // OK
        echo json_encode([
            'status' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user_id' => $user['user_id'],
                'name'    => $user['name'],
                'username'=> $user['username'],
                'role'    => $user['role']
            ]
        ]);
    } else {
        http_response_code(401); // Unauthorized
        echo json_encode([
            'status' => false,
            'message' => 'Password salah.'
        ]);
    }
} else {
    http_response_code(404); // Not Found
    echo json_encode([
        'status' => false,
        'message' => 'Pengguna tidak ditemukan.'
    ]);
}
?>
