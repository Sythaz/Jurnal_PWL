<form action="{{ url('/user/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Level Pengguna</label>
                    <select name="level_id" id="level_id" class="form-control" required>
                        <option value="">- Pilih Level -</option>
                        @foreach ($level as $l)
                            <option value="{{ $l->level_id }}">{{ $l->level_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-level_id" class="error-text form-text text-danger">
                        <!-- Validasi: Kode level harus diisi, angka, dan minimal 1 digit -->
                    </small>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input value="" type="text" name="username" id="username" class="form-control" required>

                    <small id="error-username" class="error-text form-text text-danger">
                        <!-- Validasi: Username harus diisi, minimal 3 karakter, dan maximal 20 karakter -->
                    </small>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input value="" type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>

                    <small id="error-nama_lengkap" class="error-text form-text text-danger">
                        <!-- Validasi: Nama lengkap harus diisi, minimal 3 karakter, dan maximal 100 karakter -->
                    </small>
                </div>
                <div class="form-group">
                    <label>Password</label>

                    <input value="" type="password" name="password" id="password" class="form-control" required>

                    <small id="error-password" class="error-text form-text text-danger">
                        <!-- Validasi: Password harus diisi, minimal 6 karakter, dan maximal 20 karakter -->
                    </small>
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
                // Kode level harus diisi, angka, dan minimal 1 digit
                level_id: {
                    required: true,
                    number: true
                },
                // Username harus diisi, minimal 3 karakter, dan maximal 20 karakter
                username: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                // Nama Lengkap harus diisi, minimal 3 karakter, dan maximal 100 karakter
                nama_lengkap: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                // Password harus diisi, minimal 6 karakter, dan maximal 20 karakter
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
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
                            // dataUser.ajax.reload();
                            $('#table_user').DataTable().ajax.reload();
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
