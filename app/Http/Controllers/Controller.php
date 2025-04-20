<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Method untuk memeriksa session sudah login atau belum.
    protected function checkSession()
    {
        $excluded = ['/'];

        if (!session()->has('user_id') && !request()->is($excluded)) {
            return redirect()->route('welcome')->with('errorAuth', 'Anda belum login! Silahkan login terlebih dahulu.');
        }
    }

    // Method untuk memeriksa apakah user adalah owner atau bukan.
    protected function isOwner()
    {
        $excluded = ['kegiatan', 'kegiatan/*'];

        if (session('level_id') != 1) {
            // Jika user bukan owner, cek apakah dia mengakses halaman yang tidak dikecualikan
            if (!request()->is($excluded)) {
                abort(403, 'Akses ditolak. Hanya pemilik (owner) yang dapat mengakses halaman ini.');
            }

            return false; // Bukan owner, tapi masih diizinkan akses halaman tertentu
        }

        return true; // Owner

    }
}
