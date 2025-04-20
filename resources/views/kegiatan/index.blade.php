@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('kegiatan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter :</label>
                        <div class="col-3">
                            <select class="form-control" id="nama_kegiatan" name="nama_kegiatan" required>
                                <option value="">- Semua -</option>
                                @foreach ($kegiatans as $item)
                                    <option value="{{ $item->nama_kegiatan }}">{{ $item->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama Kegiatan</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kegiatan">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nama Kegiatan</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data- backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        $(document).ready(function() {
            var dataKegiatan = $('#table_kegiatan').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('kegiatan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.nama_kegiatan = $('#nama_kegiatan').val();
                    }
                },
                columns: [{
                        data: "waktu",
                        orderable: true,
                        searchable: true,
                        width: "15%"
                    },
                    {
                        data: "nama_kegiatan",
                        // orderable: true, jika ingin kolom ini bisa diurutkan
                        orderable: false,
                        // searchable: true, jika ingin kolom ini bisa dicari
                        searchable: true,
                        width: "25%"
                    },
                    {
                        data: "catatan",
                        orderable: false,
                        searchable: true,
                        width: "40%"
                    }, {
                        data: "aksi",
                        orderable: false,
                        searchable: true,
                        width: "15%"
                    }
                ]
            });

            $('#nama_kegiatan').on('change', function() {
                dataKegiatan.ajax.reload();
            })
        });
    </script>
@endpush
