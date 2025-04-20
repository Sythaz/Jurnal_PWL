<form action="{{ url('/kegiatan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input value="" type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required>
                    <!-- Validasi: Nama Kegiatan harus diisi, minimal 3 karakter, dan maximal 20 karakter -->
                    <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Waktu</label>
                    <input value="" type="datetime-local" name="waktu" id="waktu" class="form-control" required>
                    <!-- Validasi: Waktu harus diisi dan formatnya datetime-local -->
                    <small id="error-waktu" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    <input value="" type="text" name="catatan" id="catatan" class="form-control">
                    <!-- Validasi: Catatan tidak wajib diisi -->
                    <small id="error-catatan" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                nama_kegiatan: {
                    required: true, // Nama Kegiatan harus diisi
                    minlength: 3,   // Minimal 3 karakter
                    maxlength: 20   // Maksimal 20 karakter
                },
                waktu: {
                    required: true, // Waktu harus diisi
                    date: true      // Format harus tanggal
                },
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


