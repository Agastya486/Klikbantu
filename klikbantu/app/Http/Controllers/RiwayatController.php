<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Ambil data donasi user yang sukses
        $donasi = DB::table('donations')
            ->join('campaigns', 'donations.id_campaign', '=', 'campaigns.id')
            ->where('donations.id_user', $user->id)
            ->select(
                'donations.id',
                'donations.nominal',
                'donations.pesan',
                'donations.is_anonim',
                'donations.status_pembayaran',
                'donations.created_at',
                'campaigns.nama as nama_campaign',
                'campaigns.kategori'
            )
            ->orderBy('donations.created_at', 'desc')
            ->get();
        
        // Ambil data zakat user
        $zakat = DB::table('zakats')
            ->where('id_user', $user->id)
            ->select(
                'id',
                'nominal',
                'jenis_zakat',
                'nama_pembayar',
                'status_pembayaran',
                'created_at'
            )
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Hitung total donasi yang sukses
        $totalDonasi = DB::table('donations')
            ->where('id_user', $user->id)
            ->where('status_pembayaran', 'success')
            ->sum('nominal');
        
        // Hitung total zakat yang sukses
        $totalZakat = DB::table('zakats')
            ->where('id_user', $user->id)
            ->where('status_pembayaran', 'success')
            ->sum('nominal');
        
        // Gabungkan semua data untuk ditampilkan di tabel
        $allData = [];
        
        foreach ($donasi as $d) {
            $allData[] = (object)[
                'tipe' => 'donasi',
                'nama' => $d->is_anonim ? 'Hamba Allah' : $user->nama,
                'program' => $d->nama_campaign,
                'jumlah' => $d->nominal,
                'tanggal' => $d->created_at,
                'status' => $d->status_pembayaran,
                'kategori' => $d->kategori,
                'is_anonim' => $d->is_anonim
            ];
        }
        
        foreach ($zakat as $z) {
            $allData[] = (object)[
                'tipe' => 'zakat',
                'nama' => $z->nama_pembayar,
                'program' => 'Zakat ' . ucfirst($z->jenis_zakat),
                'jumlah' => $z->nominal,
                'tanggal' => $z->created_at,
                'status' => $z->status_pembayaran,
                'kategori' => $z->jenis_zakat,
                'is_anonim' => false
            ];
        }
        
        // Urutkan berdasarkan tanggal terbaru
        usort($allData, function($a, $b) {
            return strtotime($b->tanggal) - strtotime($a->tanggal);
        });
        
        return view('riwayat.index', compact('user', 'allData', 'totalDonasi', 'totalZakat'));
    }
    
    // API endpoint untuk filter (jika perlu AJAX)
    public function filter(Request $request)
    {
        $user = Auth::user();
        $tipe = $request->get('tipe', 'semua');
        
        $donasi = DB::table('donations')
            ->join('campaigns', 'donations.id_campaign', '=', 'campaigns.id')
            ->where('donations.id_user', $user->id)
            ->select(
                'donations.id',
                'donations.nominal',
                'donations.pesan',
                'donations.is_anonim',
                'donations.status_pembayaran',
                'donations.created_at',
                'campaigns.nama as nama_campaign'
            )
            ->get();
            
        $zakat = DB::table('zakats')
            ->where('id_user', $user->id)
            ->get();
            
        $allData = [];
        
        foreach ($donasi as $d) {
            if ($tipe === 'semua' || $tipe === 'donasi') {
                $allData[] = [
                    'tipe' => 'donasi',
                    'nama' => $d->is_anonim ? 'Hamba Allah' : $user->nama,
                    'program' => $d->nama_campaign,
                    'jumlah' => $d->nominal,
                    'tanggal' => $d->created_at->format('d M Y, H:i'),
                    'status' => $d->status_pembayaran
                ];
            }
        }
        
        foreach ($zakat as $z) {
            if ($tipe === 'semua' || $tipe === 'zakat') {
                $allData[] = [
                    'tipe' => 'zakat',
                    'nama' => $z->nama_pembayar,
                    'program' => 'Zakat ' . ucfirst($z->jenis_zakat),
                    'jumlah' => $z->nominal,
                    'tanggal' => $z->created_at->format('d M Y, H:i'),
                    'status' => $z->status_pembayaran
                ];
            }
        }
        
        usort($allData, function($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });
        
        return response()->json($allData);
    }
}
