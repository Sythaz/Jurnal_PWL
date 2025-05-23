<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    // Menampilkan halaman awal level
    public function index()
    {
        // Cek apakah user sudah login. Mengambil fungsi dari class Controller
        $check = $this->checkSession();
        if ($check) return $check;

        // Cek apakah user adalah owner, akan mengembalikan boolean. Mengambil fungsi dari class Controller
        $isOwner = $this->isOwner();

        // Title dan breadcrumb (navigasi) untuk halaman
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        // Judul untuk card
        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif

        $level = LevelModel::all();

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu, 'isOwner' => $isOwner]);
    }

    // Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        // Mengambil data level
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');

        if ($request->level_kode) {
            $level->where('level_kode', $request->level_kode);
        }

        return DataTables::of($level)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                $btn = '<div class="text-center">'; // Menengahkan tombol
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                $btn .= '</div>';
                
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // level_kode harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_level kolom level_kode
                'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }
            LevelModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil disimpan'
            ]);
            redirect('/');
        }
    }

    // Menampilkan halaman form edit level ajax
    public function show_ajax(string $id)
    {
        // Mencari data level berdasarkan ID
        $level = LevelModel::find($id);
        return view('level.show_ajax', ['level' => $level]);
    }

    public function edit_ajax(string $id)
    {
        // Mencari data level berdasarkan ID
        $level = LevelModel::find($id);
        return view('level.edit_ajax', ['level' => $level]);
    }

    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            // aturan validasi input
            $rules = [
                'level_kode' => [
                    'required', // level_kode harus diisi
                    'string', // level_kode harus berupa string
                    'min:3', // level_kode harus berisi minimal 3 karakter
                    Rule::unique('m_level', 'level_kode')->ignore($id, 'level_id'), // level_kode harus bernilai unik di tabel m_level kolom level_kode, kecuali data yang sedang diupdate
                ],
                'level_nama' => 'required|string|max:100', // level_nama harus diisi, berupa string, dan maximal 100 karakter
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }

            $level = LevelModel::find($id);
            if ($level) {
                $level->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
                return redirect('/');
            }
        }
    }
}
