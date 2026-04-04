<?php
    session_start();
    require_once dirname(__FILE__) . '/../vendor/autoload.php';
    include '../includes/koneksi.php';

    // Cek autentikasi user
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    // Konfigurasi Midtrans
    \Midtrans\Config::$serverKey    = 'midtrans-server-KEY';
    \Midtrans\Config::$isProduction = false;
    \Midtrans\Config::$isSanitized  = true; // Fitur untuk membersihkan inputan agar terhindar dari script injection
    \Midtrans\Config::$is3ds        = true; // Fitur untuk mengaktifkan 3D Secure (untuk kartu kredit)

    // Ambil data dari request
    $id_user    = (int) $_SESSION['user_id'];
    $id_campaign = (int) ($_POST['id_campaign'] ?? 0);
    $nominal    = (int) ($_POST['nominal'] ?? 0);
    $nama       = trim($_POST['nama'] ?? '');
    $pesan      = trim($_POST['pesan'] ?? '');
    $is_anonim  = (int) ($_POST['is_anonim'] ?? 0);

    if ($id_campaign <= 0 || $nominal < 10000 || empty($nama)) {
        echo json_encode(['error' => 'Data tidak valid']);
        exit();
    }

    // Pastikan campaign aktif
    $qc = $conn->prepare("SELECT id, nama FROM campaigns WHERE id = ? AND status = 'active'");
    $qc->bind_param("i", $id_campaign); // i = integer
    $qc->execute();
    $campaign = $qc->get_result()->fetch_assoc();
    if (!$campaign) {
        echo json_encode(['error' => 'Campaign tidak ditemukan atau sudah ditutup']);
        exit();
    }

    $order_id = 'DON-' . time() . '-' . $id_user;

    // Ambil email user untuk Midtrans
    $qu = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $qu->bind_param("i", $id_user);
    $qu->execute();
    $user = $qu->get_result()->fetch_assoc();

    // Siapkan parameter untuk Midtrans untuk dikirim ke API Midtrans
    $params = [
        'transaction_details' => [
            'order_id'     => $order_id,
            'gross_amount' => $nominal,
        ],
        'customer_details' => [
            'first_name' => $is_anonim ? 'Hamba Allah' : $nama,
            'email'      => $user['email'],
        ],
        'item_details' => [[
            'id'       => 'DON-' . $id_campaign,
            'price'    => $nominal,
            'quantity' => 1,
            'name'     => 'Donasi: ' . substr($campaign['nama'], 0, 50),
        ]],
        // Simpan data penting di custom_field untuk webhook
        'custom_field1' => $id_campaign,
        'custom_field2' => $id_user,
        'custom_field3' => $is_anonim,
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params); // Mendapatkan snap token dari Midtrans untuk transaksi ini

        $namaDonatur = $is_anonim ? 'Hamba Allah' : $nama;
        
        $stmt = $conn->prepare(
            "INSERT INTO donations (id_user, id_campaign, nominal, pesan, is_anonim, status_pembayaran, snap_token) 
            VALUES (?, ?, ?, ?, ?, 'pending', ?)"
        );
        
        $stmt->bind_param("iiisss", $id_user, $id_campaign, $nominal, $pesan, $is_anonim, $order_id);
        $stmt->execute();

        echo json_encode(['token' => $snapToken]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }