@empty($kegiatan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('/kegiatan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lihat Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>

            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="col-3">Nama Kegiatan</th>
                        <td class="col-9">{{ $kegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th class="col-3">Waktu</th>
                        <td class="col-9">{{ $kegiatan->waktu }}</td>
                    </tr>
                    <tr>
                        <th class="col-3">Catatan</th>
                        <td class="col-9">{{ $kegiatan->catatan }}</td>
                    </tr>
                </table>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
            </div>
        </div>
    </div>
@endempty
