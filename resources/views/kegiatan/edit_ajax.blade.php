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
    <form action="{{ url('/kegiatan/' . $kegiatan->kegiatan_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kegiatan</label>
                        <input value="{{ $kegiatan->nama_kegiatan }}" type="text" name="nama_kegiatan" id="nama_kegiatan"
                            class="form-control" required>

                        <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Waktu</label>
                        <input value="{{ $kegiatan->waktu }}" type="datetime-local" name="waktu" id="waktu"
                            class="form-control" required>

                        <small id="error-waktu" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <input value="{{ $kegiatan->catatan }}" type="text" name="catatan" id="catatan"
                            class="form-control" required>

                        <small id="error-catatan" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    nama_kegiatan: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    waktu: {
                        required: true,
                        date: true
                    },
                    catatan: {
                        minlength: 0,
                        maxlength: 200
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                // dataKegiatan.ajax.reload();
                                $('#table_kegiatan').DataTable().ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
