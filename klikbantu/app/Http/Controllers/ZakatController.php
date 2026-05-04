<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class ZakatController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false; // Set true kalau sudah live
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $user = Auth::user();
        return view('zakat.index', compact('user'));
    }

    /**
     * Membuat transaksi zakat dan mendapatkan Snap Token dari Midtrans
     */
    public function createTransaction(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_muzakki' => 'required|string|max:255',
            'jenis_zakat' => 'required|string',
            'jumlah_zakat' => 'required|numeric|min:10000',
            'is_anonim' => 'nullable|boolean',
        ]);

        $user = Auth::user(); // Bisa null kalau user memilih anonim
        $isAnonim = $request->is_anonim ?? false; // Default false (bukan anonim)
        $orderId = 'ZKT-' . time() . '-' . rand(100, 999);
        
        // Simpan ke database
        DB::table('zakats')->insert([
            'id_user' => $user ? $user->id : null,
            'nama_pembayar' => $isAnonim ? 'Hamba Allah' : $request->nama_muzakki,
            'jenis_zakat' => $request->jenis_zakat,
            'nominal' => (int) $request->jumlah_zakat,
            'no_hp' => '-',
            'status_pembayaran' => 'pending',
            'snap_token' => $orderId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->jumlah_zakat,
            ],
            'customer_details' => [
                'first_name' => $isAnonim ? 'Hamba Allah' : $request->nama_muzakki,
                'email' => $user ? $user->email : 'customer@example.com',
            ],
            'custom_field1' => $request->jenis_zakat,
            'custom_field2' => $request->nama_muzakki,
            'custom_field3' => $user ? $user->id : null,
            'item_details' => [
                [
                    'id' => $request->jenis_zakat,
                    'price' => (int) $request->jumlah_zakat,
                    'quantity' => 1,
                    'name' => 'Zakat ' . ucfirst($request->jenis_zakat),
                ]
            ],
            // INI PENTING: Set finish_url ke URL ngrok
            'finish_url' => config('app.url') . '/zakat/finish',
            'callback_url' => config('app.url') . '/midtrans/notification',
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Update snap_token
            DB::table('zakats')
                ->where('snap_token', $orderId)
                ->update(['snap_token' => $snapToken]);
            
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Handle notification dari Midtrans (Webhook)
     */
    public function handleNotification(Request $request)
    {
        try {
            $notif = new Notification();
            
            $status = $notif->transaction_status;
            $orderId = $notif->order_id;
            $grossAmount = (int) $notif->gross_amount;
            
            // Cek apakah ini transaksi Zakat (Prefix ZKT-)
            if (strpos($orderId, 'ZKT-') === 0) {
                
                if ($status === 'settlement' || $status === 'capture') {
                    // Update status zakat menjadi success
                    $updated = DB::table('zakats')
                        ->where('snap_token', $orderId)
                        ->orWhere('snap_token', $notif->transaction_id)
                        ->update([
                            'status_pembayaran' => 'success',
                            'updated_at' => now()
                        ]);
                    
                    if ($updated) {
                        // Optional: Kirim email notifikasi ke user
                        // Mail::to($user->email)->send(new ZakatSuccessNotification($zakat));
                        
                        \Log::info("Zakat $orderId berhasil diproses");
                        return response()->json(['status' => 'ok'], 200);
                    }
                    
                } elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
                    DB::table('zakats')
                        ->where('snap_token', $orderId)
                        ->orWhere('snap_token', $notif->transaction_id)
                        ->update([
                            'status_pembayaran' => 'failed',
                            'updated_at' => now()
                        ]);
                    
                    \Log::info("Zakat $orderId gagal: $status");
                    return response()->json(['status' => 'ok'], 200);
                }
                
            } else {
                \Log::warning("Order ID tidak dikenal: $orderId");
                return response()->json(['status' => 'unknown_order'], 400);
            }
            
        } catch (\Exception $e) {
            \Log::error("Midtrans Notification Error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
        return response()->json(['status' => 'ok'], 200);
    }
    
    /**
     * Riwayat zakat user
     */
    public function history()
    {
        $user = Auth::user();
        $zakats = DB::table('zakats')
            ->where('id_user', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('zakat.history', compact('zakats'));
    }

    // Route untuk update status manual (sementara gak dipake yg ini)
    public function updateStatusManual(Request $request, $orderId)
    {
        // Cari berdasarkan snap_token yang mengandung order_id
        $zakat = DB::table('zakats')
            ->where('snap_token', 'like', '%' . $orderId . '%')
            ->first();
        
        if ($zakat) {
            DB::table('zakats')
                ->where('id', $zakat->id)
                ->update([
                    'status_pembayaran' => $request->status,
                    'updated_at' => now()
                ]);
            
            return response()->json(['message' => 'Status updated to ' . $request->status]);
        }
        
        return response()->json(['message' => 'Zakat not found'], 404);
    }

    // Route untuk halaman finish setelah pembayaran
    public function finish(Request $request)
    {
        // Ambil parameter dari Midtrans
        $orderId = $request->get('order_id');
        $statusCode = $request->get('status_code');
        $transactionStatus = $request->get('transaction_status');
        
        // Update status jika perlu
        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            DB::table('zakats')
                ->where('snap_token', 'like', '%' . $orderId . '%')
                ->update([
                    'status_pembayaran' => 'success',
                    'updated_at' => now()
                ]);
        }
        
        return redirect()->route('zakat.history')
            ->with('success', 'Terima kasih! Zakat Anda berhasil dibayarkan.');
    }
    
    // Route untuk update status manual via URL
    public function manualUpdate($orderId)
    {
        // Cari transaksi berdasarkan snap_token
        $zakat = DB::table('zakats')
            ->where('snap_token', 'like', '%' . $orderId . '%')
            ->first();
        
        if (!$zakat) {
            return "Transaksi dengan order ID {$orderId} tidak ditemukan!";
        }
        
        // Update status
        DB::table('zakats')
            ->where('id', $zakat->id)
            ->update([
                'status_pembayaran' => 'success',
                'updated_at' => now()
            ]);
        
        return "✅ Status zakat berhasil diupdate menjadi SUCCESS!<br>
                Order ID: {$orderId}<br>
                Nama: {$zakat->nama_pembayar}<br>
                Nominal: Rp " . number_format($zakat->nominal, 0, ',', '.');
    }
}