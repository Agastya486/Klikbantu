<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showHomePage(){
        // ambil user login
        $user = Auth::user();

        // ambil campaigns
        $campaigns = DB::table('campaigns')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // total donasi
        $statsDonasi = DB::table('donations')
            ->where('id_user', $user->id)
            ->where('status_pembayaran', 'success')
            ->selectRaw('COUNT(*) as cnt, SUM(nominal) as total')
            ->first();

        // total zakat
        $statsZakat = DB::table('zakats')
            ->where('id_user', $user->id)
            ->where('status_pembayaran', 'success')
            ->selectRaw('SUM(nominal) as total')
            ->first();

        return view("users.home", compact(
            'user',
            'campaigns',
            'statsDonasi',
            'statsZakat'
        ));
    }

    public function showProfile(){
        $user = Auth::user(); // ambil user login
        return view("users.profile", compact('user'));
    }

    public function showUpdateProfileForm(){
        return view("users.update_profile");
    }

    public function showKeamanan(){
        return view("users.keamanan_profile");
    }

    public function updateAvatar(Request $request) {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {

            // hapus avatar lama (kalau ada)
            if ($user->avatar && file_exists(public_path('assets/img/avatars/' . $user->avatar))) {
                unlink(public_path('assets/img/avatars/' . $user->avatar));
            }

            // ambil file
            $file = $request->file('avatar');

            // bikin nama unik (mirip versi lama kamu)
            $namaBaru = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // simpan ke folder public/assets/img/avatars
            $file->move(public_path('assets/img/avatars'), $namaBaru);

            // update DB
            $user->avatar = $namaBaru;
            $user->save();

            return back()->with('success', 'Avatar berhasil diupdate');
        }

        return back()->with('error', 'Avatar gagal diupdate');
    }

    public function updateProfileInfo(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->nama = $request->name;
        $user->email = $request->email;
        $user->no_telp = $request->phone_number;
        $user->save();

        return back()->with('success', 'Profil berhasil diupdate');
    }

    public function updatePassword(Request $request){
        $user = Auth::user();

        // Validasi (replace strlen & manual check)
        if (strlen($request->new_password) < 8) {
            return back()->with('error', 'Panjang password minimal 8 karakter');
        }

        if ($request->new_password !== $request->confirm_password) {
            return back()->with('error', 'Password tidak cocok');
        }

        // Cek password lama (replace password_verify)
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah');
        }

        // Update password (replace password_hash + query)
        $user->password = Hash::make($request->new_password);

        if ($user->save()) {
            return back()->with('success', 'Password berhasil diperbarui!');
        } else {
            return back()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }
}