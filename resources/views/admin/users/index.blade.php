<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Data User
                        </h1>
                        <div class="page-header-subtitle">Manajemen Data User</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">
                Daftar User
                <button class="btn btn-primary btn-sm float-end" type="button" onclick="addData()">
                    <i data-feather="plus" class="me-1"></i> Tambah User
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tipe</th>
                                <th>Tim Kerja</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="userForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id_user" id="id_user">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="name">Nama <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" name="name" type="text"
                                placeholder="Masukkan nama user" required>
                            <div class="invalid-feedback" id="error-name"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="email">Email <span class="text-danger">*</span></label>
                            <input class="form-control" id="email" name="email" type="text"
                                placeholder="Masukkan nama tim kerja" required>
                            <div class="invalid-feedback" id="error-email"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="password">Password <span class="text-danger" id="password-asterisk">*</span></label>
                            <div class="input-group">
                                <input class="form-control" id="password" name="password" type="password"
                                    placeholder="Masukkan password" required>
                                <button class="btn btn-outline-secondary px-3" type="button" id="togglePassword">
                                    <i data-feather="eye" style="width: 1rem; height: 1rem;"></i>
                                </button>
                                <div class="invalid-feedback" id="error-password"></div>
                            </div>
                            <div class="mt-2" id="password-strength-container" style="display: none;">
                                <div class="progress" style="height: 5px;">
                                    <div id="password-strength-bar" class="progress-bar" role="progressbar"
                                        style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <small id="password-strength-text" class="form-text mt-1"></small>
                            </div>
                        </div>
                        <div class="mb-3" id="tim_kerja_container">
                            <label class="small mb-1" for="tim_kerja_id">Tim Kerja</label>
                            <select class="form-control select2" id="tim_kerja_id" name="tim_kerja_id[]" multiple>
                                @foreach ($timKerja as $tk)
                                    <option value="{{ $tk->id_tim_kerja }}">{{ $tk->nama_timkerja }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-tim_kerja_id"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Tidak Aktif</option>
                            </select>
                            <div class="invalid-feedback" id="error-status"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="tipe">Tipe <span class="text-danger">*</span></label>
                            <select class="form-control" id="tipe" name="tipe" required
                                onchange="handleTipeChange(this.value)">
                                <option value="">Pilih Tipe</option>
                                <option value="admin">Admin</option>
                                <option value="users">User</option>
                                <option value="pegawai">Pegawai</option>
                                <option value="helpdesk">Helpdesk</option>
                            </select>
                            <div class="invalid-feedback" id="error-tipe"></div>
                        </div>
                        <div class="mb-3" id="kategori_user_container" style="display: none;">
                            <label class="small mb-1" for="kategori_user">Kategori User</label>
                            <select class="form-control" id="kategori_user" name="kategori_user">
                                <option value="umum">Umum</option>
                                <option value="pemerintah">Pemerintah</option>
                            </select>
                            <div class="invalid-feedback" id="error-kategori_user"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" id="btnSave" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('after-styles')
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        <style>
            .btn-datatable {
                height: 2rem;
                width: 2rem;
                padding: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    @endpush

    @push('before-scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush


    @push('after')
        <script>
            let table;
            $(document).ready(function() {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Pilih Tim Kerja',
                    dropdownParent: $('#userModal')
                });

                $('#password').on('input', function() {
                    let val = $(this).val();
                    let result = 0;
                    if (val.length > 5) result += 1;
                    if (val.match(/[a-z]/)) result += 1;
                    if (val.match(/[A-Z]/)) result += 1;
                    if (val.match(/\d/)) result += 1;
                    if (val.match(/[_\W]/)) result += 1;

                    let strength = "";
                    let color = "";
                    let width = "0%";

                    if (val.length === 0) {
                        $('#password-strength-container').hide();
                        return;
                    }

                    if (result <= 2) {
                        strength = "Lemah";
                        color = "danger";
                        width = "25%";
                    } else if (result == 3 || result == 4) {
                        strength = "Sedang";
                        color = "warning";
                        width = "75%";
                    } else if (result == 5) {
                        strength = "Kuat";
                        color = "success";
                        width = "100%";
                    }

                    $('#password-strength-text').text("Kekuatan: " + strength).removeClass(
                        'text-danger text-warning text-success').addClass('text-' + color);
                    $('#password-strength-bar').css('width', width).removeClass(
                        'bg-danger bg-warning bg-success').addClass('bg-' + color);
                    $('#password-strength-container').show();
                });

                $('#togglePassword').on('click', function() {
                    const passwordInput = $('#password');
                    const iconBtn = $(this);
                    if (passwordInput.attr('type') === 'password') {
                        passwordInput.attr('type', 'text');
                        iconBtn.html('<i data-feather="eye-off" style="width: 1rem; height: 1rem;"></i>');
                    } else {
                        passwordInput.attr('type', 'password');
                        iconBtn.html('<i data-feather="eye" style="width: 1rem; height: 1rem;"></i>');
                    }
                    feather.replace();
                });

                table = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('user.data') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'tipe',
                            name: 'tipe'
                        },
                        {
                            data: 'tim_kerja_id',
                            name: 'tim_kerja_id',
                            orderable: false
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    drawCallback: function() {
                        feather.replace();
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    }
                });

                $('#userForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#id_user').val();
                    let url = id ? "{{ route('user.update', ':id') }}".replace(':id', id) :
                        "{{ route('user.store') }}";

                    $('#btnSave').prop('disabled', true).text('Menyimpan...');
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    console.log($(this).serialize());


                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            console.log(response);

                            $('#userModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('#' + key).addClass('is-invalid');
                                    $('#error-' + key).text(value[0]);
                                });
                            } else {
                                Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                            }
                        },
                        complete: function() {
                            $('#btnSave').prop('disabled', false).text('Simpan');
                        }
                    });
                });
            });

            function handleTipeChange(tipe) {
                // Handle Kategori User visibility
                if (tipe === 'users') {
                    $('#kategori_user_container').show();
                    $('#kategori_user').attr('required', true);
                } else {
                    $('#kategori_user_container').hide();
                    $('#kategori_user').removeAttr('required');
                }

                // Handle Tim Kerja visibility and requirement
                if (tipe === 'pegawai' || tipe === 'helpdesk') {
                    $('#tim_kerja_container').show();
                    // Optional: You can make it required if you want, but based on request, 
                    // we'll just show it and ensure it's selectable.
                    // $('#tim_kerja_id').attr('required', true);
                } else {
                    $('#tim_kerja_container').hide();
                    $('#tim_kerja_id').val(null).trigger('change');
                    // $('#tim_kerja_id').removeAttr('required');
                }
            }

            function addData() {
                $('#userForm')[0].reset();
                $('#tim_kerja_id').val(null).trigger('change');
                $('#id_user').val('');
                $('#formMethod').val('POST');
                $('#userModalLabel').text('Tambah User');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#password-strength-container').hide();
                $('#password').attr('type', 'password');
                $('#togglePassword').html('<i data-feather="eye" style="width: 1rem; height: 1rem;"></i>');
                handleTipeChange('');
                feather.replace();
                $('#userModal').modal('show');
            }

            function editData(id) {
                $.get("{{ route('user.index') }}/" + id + "/edit", function(data) {
                    console.log(data);

                    $('#userModalLabel').text('Edit User');
                    $('#id_user').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#tipe').val(data.tipe);
                    handleTipeChange(data.tipe);
                    $('#kategori_user').val(data.kategori_user || 'umum');

                    if (data.tim_kerjas && (data.tipe === 'pegawai' || data.tipe === 'helpdesk')) {
                        let selectedTeams = data.tim_kerjas.map(tk => tk.id_tim_kerja);
                        $('#tim_kerja_id').val(selectedTeams).trigger('change');
                    } else {
                        $('#tim_kerja_id').val(null).trigger('change');
                    }

                    $('#status').val(data.status);
                    $('#password').val('');
                    // remove required password
                    $('#password').removeAttr('required');
                    $('#formMethod').val('PATCH');
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    $('#password-strength-container').hide();
                    $('#password').attr('type', 'password');
                    $('#togglePassword').html('<i data-feather="eye" style="width: 1rem; height: 1rem;"></i>');
                    feather.replace();
                    $('#userModal').modal('show');
                });
            }

            function deleteData(id) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('user.destroy', ':id') }}".replace(':id', id),
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire('Berhasil', response.message, 'success');
                                table.ajax.reload();
                            },
                            error: function() {
                                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
                            }
                        });
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>
