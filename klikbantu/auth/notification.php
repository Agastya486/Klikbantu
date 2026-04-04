<?php
    require_once dirname(__FILE__) . '/../vendor/autoload.php';
    include '../includes/koneksi.php';

    \Midtrans\Config::$serverKey = 'Mid-server-2weLjAGDvIYX-m4dei8X4oh-';
    \Midtrans\Config::$isProduction = false;

    try {
        // PARSING NOTIFIKASI DARI MIDTRANS (Parsing = menerjemah format mentah jadi bahasa yg mudah dipahami bahasa pemrograman)
        $notif = new \Midtrans\Notification(); // Membuat objek baru untuk menangani notifikasi
        $status = $notif->transaction_status; // Mendapatkan status transaksi
        $order_id = $notif->order_id; // Mendapatkan order_id yang dikirim oleh Midtrans
        $gross_amount = (int) $notif->gross_amount; // Mendapatkan jumlah pembayaran dan mengkonversinya ke integer

        // Cek apakah ini transaksi Donasi (Prefix DON-) atau Zakat (Prefix ZKT-)
        if (strpos($order_id, 'DON-') === 0) {
            // Mengembalikan nilai custom_field yang sudah kita set di frontend
            $id_campaign = (int) $notif->custom_field1;
            $id_user     = (int) $notif->custom_field2;
            $is_anonim   = (int) $notif->custom_field3;

            if ($status === 'settlement' || $status === 'capture') {
                $upd = $conn->prepare("UPDATE donations SET status_pembayaran='success' WHERE snap_token=? AND id_campaign=? AND id_user=?");
                $upd->bind_param("sii", $order_id, $id_campaign, $id_user); // sii = string, int, int
                $upd->execute();

                $updCampaign = $conn->prepare("UPDATE campaigns SET dana_terkumpul = dana_terkumpul + ? WHERE id = ?");
                $updCampaign->bind_param("ii", $gross_amount, $id_campaign);
                $updCampaign->execute();

                http_response_code(200);
                error_log("Donasi $order_id berhasil.");
            } elseif ($status === 'expire' || $status === 'cancel' || $status === 'deny') {
                $upd = $conn->prepare("UPDATE donations SET status_pembayaran='failed' WHERE snap_token=?");
                $upd->bind_param("s", $order_id);
                $upd->execute();
                http_response_code(200);
            }

        } elseif (strpos($order_id, 'ZKT-') === 0) {
            if ($status == 'settlement' || $status == 'capture') {
                $jenis_zakat   = $notif->custom_field1;
                $nama_pembayar = $notif->custom_field2;
                $id_user       = $notif->custom_field3;

                $sql = "INSERT INTO zakats (id_user, nama_pembayar, jenis_zakat, nominal, no_hp, status_pembayaran, snap_token, created_at) 
                        VALUES (?, ?, ?, ?, '-', 'success', ?, NOW())";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issis", $id_user, $nama_pembayar, $jenis_zakat, $gross_amount, $order_id);
                
                if ($stmt->execute()) {
                    http_response_code(200);
                    error_log("Zakat $order_id masuk DB!");
                } else {
                    http_response_code(500);
                }
            }
        } else {
            error_log("Order ID tidak dikenal: $order_id");
            http_response_code(400);
        }

    } catch (Exception $e) {
        error_log("System Error: " . $e->getMessage());
        http_response_code(500);
    }