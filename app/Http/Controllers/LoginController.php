<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Fungsi untuk menampilkan halaman login
    public function index()
    {
        // Mengembalikan view login
        return view('login');
    }

    // Fungsi untuk melakukan proses login
    public function login(Request $request)
    {
        // Validasi input dengan menggunakan Validation Request
        $request->validate([
            'username' => 'required|string', // username harus diisi dan berupa string
            'password' => 'required|string' // password harus diisi dan berupa string
        ]);

        // Mencari data user berdasarkan username yang diinputkan
        $user = UserModel::where('username', $request->username)->first();

        // Jika user ditemukan dan passwordnya sesuai maka simpan id user dan levelnya ke dalam session
        if ($user && $user->password === $request->password) {
            session([
                'user_id' => $user->user_id, // simpan id user ke dalam session
                'level_id' => $user->level_id // simpan level id ke dalam session
            ]);

            // Redirect ke halaman kegiatan setelah login berhasil
            return redirect()->route('kegiatan.index');
        }

        // Jika login gagal maka kembali ke halaman login dengan pesan error
        return redirect()->route('welcome')
            ->with('error', 'Username atau password salah'); // pesan error jika login gagal
    }
}

