<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php'; //path ke Midtrans SDK
include '../includes/koneksi.php';

// 1. Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'midtrans_secret_code'; // Ganti dengan Server Key asli dari Midtrans
\Midtrans\Config::$isProduction = false; // Set true kalau sudah live
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// 2. Tangkap data dari Form zakat.php
$nama = $_POST['nama_muzakki'];
$jenis = $_POST['jenis_zakat']; // Dari <select name="jenis_zakat">
$jumlah = $_POST['jumlah_zakat'];
$id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$order_id = 'ZKT-' . time();

$params = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => (int)$jumlah,
    ],
    // Simpan Nama di custom_field biar aman
    'custom_field1' => $jenis,
    'custom_field2' => $nama, 
    'custom_field3' => $id_user,
];

try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    // Kita TIDAK melakukan INSERT ke database di sini. Clean!
    echo json_encode(['token' => $snapToken]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}