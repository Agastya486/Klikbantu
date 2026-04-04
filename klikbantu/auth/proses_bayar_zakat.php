<?php
    session_start();
    require_once dirname(__FILE__) . '/../vendor/autoload.php'; //path ke Midtrans SDK
    include '../includes/koneksi.php';

    // Konfigurasi Midtrans
    \Midtrans\Config::$serverKey = 'Mid-server-2weLjAGDvIYX-m4dei8X4oh-'; // Ganti dengan Server Key asli dari Midtrans
    \Midtrans\Config::$isProduction = false; // Set true kalau sudah live
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // Tangkap data dari Form zakat.php
    $nama = $_POST['nama_muzakki'];
    $jenis = $_POST['jenis_zakat'];
    $jumlah = $_POST['jumlah_zakat'];
    $id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $order_id = 'ZKT-' . time();
    $is_anonim  = (int) ($_POST['is_anonim'] ?? 0);

    $email_user = 'customer@example.com'; // default
    if ($id_user) {
        $qu = $conn->prepare("SELECT email FROM users WHERE id = ?");
        $qu->bind_param("i", $id_user);
        $qu->execute();
        $res = $qu->get_result()->fetch_assoc();
        if ($res) $email_user = $res['email']; // Jika email ditemukan, gunakan email user tersebut
    }

    $params = [
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => (int)$jumlah,
        ],
        'customer_details' => [
            'first_name' => $is_anonim ? 'Hamba Allah' : $nama,
            'email'      => $email_user,
        ],
        // Simpan Nama di custom_field biar aman
        'custom_field1' => $jenis,
        'custom_field2' => $nama, 
        'custom_field3' => $id_user,
    ];

    try {
        // Dapatkan snap token dari Midtrans
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        echo json_encode(['token' => $snapToken]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }