<?php
// Import script autoload agar bisa menggunakan library
require_once('./vendor/autoload.php');
// Import library
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
header('Content-Type: application/json');

// Cek method request apakah POST atau tidak
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}
// Ambil JSON yang dikirim oleh user
$json = file_get_contents('php://input');

// Decode json tersebut agar mudah mengambil nilainya
$input = json_decode($json);

// Jika tidak ada data email atau password
if (!isset($input->email) || !isset($input->password)) {
    http_response_code(400);
    exit();
}

$user = [
    'email' => 'oktariorifqi@student.uns.ac.id',
    'password' => 'qwerty123'
];

// Jika email atau password tidak sesuai
if ($input->email !== $user['email'] || $input->password !== $user['password']) {
    echo json_encode([
        'message' => 'Email atau password tidak sesuai'
    ]);
    exit();
}
$expired_time = time() + (15 * 60);
$payload = [
    'email' => $input->email,
    'exp' => $expired_time
];

$access_token = JWT::encode($payload, $_ENV['ACCESS_TOKEN_SECRET']);
echo json_encode([
    'accessToken' => $access_token,
    'expiry' => date(DATE_ISO8601, $expired_time)
]);
