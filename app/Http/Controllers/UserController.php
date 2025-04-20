<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah login. Mengambil fungsi dari class Controller
        $check = $this->checkSession();
        if ($check) return $check;

        // Cek apakah user adalah owner, akan mengembalikan boolean. Mengambil fungsi dari class Controller
        $isOwner = $this->isOwner();

        // Title dan breadcrumb (navigasi) untuk halaman
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        // Judul untuk card
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // Set menu yang sedang aktif

        $level = LevelModel::all();

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu, 'isOwner' => $isOwner]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        // Query untuk mengambil data user
        $users = UserModel::select('user_id', 'username', 'nama_lengkap', 'level_id')->with('level');

        // Filtering jika terdapat request dari ajax (filter)
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
                $btn = '<div class="text-center">'; // Menengahkan tombol
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                $btn .= '</div>';

                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax()
    {
        // Mengambil data level untuk dropdown
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user.create_ajax')->with('level', $level);
    }

    public function store_ajax(Request $request)
    {
        // Memeriksa apakah request berupa AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer', // level_id harus diisi dan berupa integer
                'username' => 'required|string|min:3|unique:m_user,username', // username harus diisi, berupa string, minimal 3 karakter, dan unik di tabel m_user kolom username
                'nama_lengkap' => 'required|string|max:100', // nama_lengkap harus diisi, berupa string, dan maksimal 100 karakter
                'password' => 'required|min:6' // password harus diisi dan minimal 6 karakter
            ];

            // Menggunakan Validator untuk melakukan validasi input
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                // Jika validasi gagal, mengembalikan respons JSON dengan status false dan pesan kesalahan
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Menyimpan data pengguna ke dalam database
            UserModel::create($request->all());
            // Mengembalikan respons JSON dengan status true dan pesan berhasil
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
            // Mengarahkan kembali ke halaman sebelumnya
            redirect('/');
        }
    }

    // Menampilkan halaman form edit user ajax
    // Menerima parameter string $id yang akan dijadikan sebagai filter data user berdasarkan kolom id
    public function show_ajax(string $id)
    {
        // Mengambil data user berdasarkan id yang di filter
        $user = UserModel::find($id);
        // Mengambil data level untuk dropdown
        $level = LevelModel::select('level_id', 'level_nama')->get();
        // Mengembalikan view show_ajax beserta data user dan level
        return view('user.show_ajax', ['user' => $user, 'level' => $level]);
    }

    // Menampilkan halaman form edit user menggunakan AJAX.
    public function edit_ajax(string $id)
    {
        // Mencari data user berdasarkan ID
        $user = UserModel::find($id);
        // Mengambil data level untuk dropdown
        $level = LevelModel::select('level_id', 'level_nama')->get();
        // Mengembalikan tampilan edit_ajax dengan data user dan level
        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }

    // fungsi untuk mengupdate data user menggunakan AJAX
    // menerima parameter $id yang akan dijadikan sebagai filter data user berdasarkan kolom id
    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            // aturan validasi input
            $rules = [
                'level_id' => 'required|integer', // level_id harus diisi dan berupa integer
                'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id', // username harus diisi, berupa string, maximal 20 karakter, dan unik di tabel m_user kolom username, kecuali data yang sedang diupdate
                'nama_lengkap' => 'required|max:100', // nama_lengkap harus diisi, berupa string, dan maximal 100 karakter
                'password' => 'nullable|min:6|max:20' // password boleh dikosongkan, berupa string, minimal 6 karakter, dan maximal 20 karakter
            ];

            // menggunakan Validator untuk melakukan validasi input
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                // jika validasi gagal, mengembalikan respons JSON dengan status false dan pesan kesalahan
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.', // pesan kesalahan
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }

            // mencari data user berdasarkan id yang di filter
            $check = UserModel::find($id);
            if ($check) {
                // jika password tidak diisi, maka hapus dari request
                if (!$request->filled('password')) {
                    $request->request->remove('password');
                }
                // update data user
                $check->update($request->all());
                // mengembalikan respons JSON dengan status true dan pesan berhasil
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                // mengembalikan respons JSON dengan status false dan pesan gagal
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        // mengarahkan kembali ke halaman sebelumnya
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        // Mencari data user berdasarkan ID
        $user = UserModel::find($id);
        // Mengembalikan view confirm_ajax dengan data user
        return view('user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // Memeriksa apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            // Mencari data user berdasarkan ID
            $user = UserModel::find($id);
            if ($user) {
                // Menghapus data user jika ditemukan
                $user->delete();
                // Mengembalikan respons JSON dengan status berhasil
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                // Mengembalikan respons JSON dengan status gagal jika data tidak ditemukan
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
                // Mengarahkan kembali ke halaman sebelumnya
                return redirect('/');
            }
        }
    }
}
