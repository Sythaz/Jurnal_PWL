<?php

namespace App\Http\Controllers;

use App\Models\KegiatanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KegiatanController extends Controller
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
            'title' => 'Daftar Kegiatan',
            'list' => ['Home', 'Kegiatan']
        ];

        // Judul untuk card
        $page = (object) [
            'title' => 'Daftar kegiatan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kegiatan'; // Set menu yang sedang aktif
        
        $userId = session('user_id');

        $kegiatans = KegiatanModel::select('kegiatan_id', 'user_id', 'nama_kegiatan', 'waktu', 'catatan')
            ->with('user');
        if (!$isOwner) {
            $kegiatans->where('user_id', $userId); // Filter berdasarkan user_id
        }
        $kegiatans = $kegiatans->get(); // Menggunakan get() untuk mengeksekusi query dan mengambil data dari database sebagai collection

        return view('kegiatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kegiatans' => $kegiatans, 'activeMenu' => $activeMenu, 'isOwner' => $isOwner]);
    }

    // Ambil data kegiatan dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        // Cek apakah user sudah login. Mengambil fungsi dari class Controller
        $userId = session('user_id');

        // Cek apakah user adalah owner, akan mengembalikan boolean. Mengambil fungsi dari class Controller
        $isOwner = $this->isOwner();

        // Menggunakan query builder untuk mengambil data kegiatan
        $kegiatans = KegiatanModel::select('kegiatan_id', 'user_id', 'nama_kegiatan', 'waktu', 'catatan')->with('user');
        if (!$isOwner) {
            $kegiatans->where('user_id', $userId); // Filter berdasarkan user_id
        }

        // Filtering jika terdapat request dari ajax (filter)
        if ($request->nama_kegiatan) {
            $kegiatans->where('nama_kegiatan', $request->nama_kegiatan);
        }

        return DataTables::of($kegiatans)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) { // menambahkan kolom aksi
                $btn = '<div class="text-center">'; // Menengahkan tombol
                $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->kegiatan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->kegiatan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kegiatan/' . $kegiatan->kegiatan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                $btn .= '</div>';

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
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                // Aturan validasi input
                // nama_kegiatan harus diisi, string, minimal 3 karakter
                // waktu harus diisi, berupa tanggal
                // catatan tidak wajib diisi, string, maximal 255 karakter
                $rules = [
                    'nama_kegiatan' => 'required|string|min:3',
                    'waktu' => 'required|date',
                    'catatan' => 'nullable|string|max:255',
                ];
                
                // Validasi input
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false, // response status, false: error/gagal, true: berhasil
                        'message' => 'Validasi Gagal disimpan',
                        'msgField' => $validator->errors(), // pesan error validasi
                    ]);
                }

                // Menggunakan session untuk menentukan user yang sedang login
                // (Tidak tampil di tabel, hanya untuk mengisi kolom user_id)
                $user_id = session('user_id');
                $request->merge(['user_id' => $user_id]);

                KegiatanModel::create($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data kegiatan berhasil disimpan'
                ]);
                redirect('/');
            }
    }

    public function show_ajax(string $id)
    {
        $kegiatan = KegiatanModel::find($id);
        return view('kegiatan.show_ajax', ['kegiatan' => $kegiatan]);
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
            // Aturan validasi input
            // nama_kegiatan harus diisi, string, minimal 3 karakter
            // waktu harus diisi, berupa tanggal
            // catatan tidak wajib diisi, string, maximal 255 karakter
            $rules = [
                'nama_kegiatan' => 'required|string|min:3',
                'waktu' => 'required|date',
                'catatan' => 'nullable|string|max:255',
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

            $kegiatan = KegiatanModel::find($id);
            if ($kegiatan) {
                $kegiatan->update($request->all());
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

