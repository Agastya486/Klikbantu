<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';
include '../includes/koneksi.php';

\Midtrans\Config::$serverKey = 'midtrans_secret_code';
\Midtrans\Config::$isProduction = false;

try {
    $notif = new \Midtrans\Notification();
    $transaction = $notif->transaction_status;
    $order_id = $notif->order_id;

    if ($transaction == 'settlement' || $transaction == 'capture') {
        // Ambil balik data yang kita titipin tadi
        $jenis_zakat = $notif->custom_field1; // Ini isinya: fitrah, maal, dll
        $nama_pembayar = $notif->custom_field2;
        $id_user = $notif->custom_field3 ?: null;
        $jumlah = $notif->gross_amount;

        // Query JIT (Insert pas sudah pasti bayar)
        // Kolom no_hp gue isi '-' karena di SQL lu dia NOT NULL
        $sql = "INSERT INTO zakats (id_user, nama_pembayar, jenis_zakat, nominal, no_hp, status_pembayaran, snap_token, created_at) 
                VALUES (?, ?, ?, ?, '-', 'success', ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        // Bind param: i=int, s=string. (id_user, nama, jenis, nominal, order_id)
        $stmt->bind_param("issis", $id_user, $nama_pembayar, $jenis_zakat, $jumlah, $order_id);
        
        if ($stmt->execute()) {
            http_response_code(200);
            error_log("Zakat $jenis_zakat dari $nama_pembayar masuk DB!");
        } else {
            error_log("SQL Error: " . $conn->error);
            http_response_code(500);
        }
    }
} catch (Exception $e) {
    error_log("System Error: " . $e->getMessage());
    http_response_code(500);
}