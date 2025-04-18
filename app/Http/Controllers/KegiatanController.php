<?php

namespace App\Http\Controllers;

use App\Models\KegiatanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KegiatanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kegiatan',
            'list' => ['Home', 'Kegiatan']
        ];
        $page = (object) [
            'title' => 'Daftar kegiatan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kegiatan'; // Set menu yang sedang aktif

        $kegiatans = KegiatanModel::all();

        return view('kegiatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kegiatans' => $kegiatans, 'activeMenu' => $activeMenu]);
    }

    // Ambil data kegiatan dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $kegiatans = KegiatanModel::select('kegiatan_id', 'user_id', 'nama_kegiatan', 'waktu', 'catatan')->with('user');

        if ($request->nama_kegiatan) {
            $kegiatans->where('nama_kegiatan', $request->nama_kegiatan);
        }

        return DataTables::of($kegiatans)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->kegiatan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->kegiatan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->kegiatan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax()
    {
        return view('kegiatan.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kegiatan' => 'required|string|min:3|unique:m_kegiatan,nama_kegiatan',
                'waktu' => 'required|date',
                'catatan' => 'nullable|string|max:255',
            ];
            // use Illuminate \Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            // Input manual karena belum menggunakan Auth
            $request->merge(['user_id' => 1]);

            KegiatanModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data kegiatan berhasil disimpan'
            ]);
            redirect('/');
        }
    }

    // Menampilkan halaman form edit kegiatan ajax
    public function edit_ajax(string $id)
    {
        $kegiatan = KegiatanModel::find($id);
        return view('kegiatan.edit_ajax', ['kegiatan' => $kegiatan]);
    }

    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kegiatan_id' => 'required|integer',
                'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                'nama_lengkap' => 'required|max:100',
                'password' => 'nullable|min:6|max:20'
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

            $check = UserModel::find($id);
            if ($check) {
                if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request

                    $request->request->remove('password');
                }
                $check->update($request->all());
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
        $kegiatan = KegiatanModel::find($id);
        return view('kegiatan.confirm_ajax', ['kegiatan' => $kegiatan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $kegiatan = KegiatanModel::find($id);
            if ($kegiatan) {
                $kegiatan->delete();
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
