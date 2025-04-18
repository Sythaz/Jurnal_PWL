@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($kegiatans)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $kegiatans->kegiatan_id }}</td>
                    </tr>
                    {{-- <tr>
                        <th>Nama</th>
                        <td>{{ $kegiatans->nama_id }}</td>
                    </tr> --}}
                    <tr>
                        <th>Nama Kegiatan</th>
                        <td>{{ $kegiatans->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Waktu</th>
                        <td>{{ $kegiatans->waktu }}</td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>{{ $kegiatans->catatan }}</td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td>********</td>
                    </tr>
                </table>
            @endempty
            <a href="{{ url('kegiatan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
@endpush
