<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = UserModel::where('username', $request->username)->first();

        if ($user && $user->password === $request->password) {
            session([
                'user_id' => $user->user_id,
                'level_id' => $user->level_id
            ]);

            return redirect()->route('kegiatan.index');
        }

        return redirect()->route('welcome')
            ->with('error', 'Username atau password salah');
    }
}
