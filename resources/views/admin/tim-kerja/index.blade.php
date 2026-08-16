<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Tim Kerja
                        </h1>
                        <div class="page-header-subtitle">Manajemen Data Tim Kerja</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">
                Daftar Tim Kerja
                <button class="btn btn-primary btn-sm float-end" type="button" onclick="addData()">
                    <i data-feather="plus" class="me-1"></i> Tambah Tim Kerja
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Tim Kerja</th>
                                <th>Ketua</th>
                                <th>Anggota</th>
                                <th>Dibuat Pada</th>
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

    <!-- Modal -->
    <div class="modal fade" id="tkModal" tabindex="-1" role="dialog" aria-labelledby="tkModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tkModalLabel">Tambah Tim Kerja</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="tkForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id_tim_kerja" id="id_tim_kerja">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="nama_timkerja">Nama Tim Kerja <span class="text-danger">*</span></label>
                            <input class="form-control" id="nama_timkerja" name="nama_timkerja" type="text"
                                placeholder="Masukkan nama tim kerja" required>
                            <div class="invalid-feedback" id="error-nama_timkerja"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="ketua_id">Ketua Tim <span class="text-danger">*</span></label>
                            <select class="form-control" id="ketua_id" name="ketua_id" required>
                                <option value="">Pilih Ketua Tim</option>
                            </select>
                            <div class="invalid-feedback" id="error-ketua_id"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="anggota_ids">Anggota Tim</label>
                            <select class="form-control" id="anggota_ids" name="anggota_ids[]" multiple>
                            </select>
                            <div class="invalid-feedback" id="error-anggota_ids"></div>
                            <small class="text-muted">Pilih satu atau lebih anggota tim</small>
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
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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
                $('#ketua_id').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#tkModal'),
                    ajax: {
                        url: "{{ route('api.pegawai') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });

                $('#anggota_ids').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#tkModal'),
                    placeholder: 'Pilih Anggota Tim',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('api.pegawai') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });

                table = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('tim-kerja.data') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_timkerja',
                            name: 'nama_timkerja'
                        },
                        {
                            data: 'ketua',
                            name: 'ketua'
                        },
                        {
                            data: 'anggota',
                            name: 'anggota',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
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

                $('#tkForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#id_tim_kerja').val();
                    let url = id ? "{{ route('tim-kerja.update', ':id') }}".replace(':id', id) :
                        "{{ route('tim-kerja.store') }}";

                    $('#btnSave').prop('disabled', true).text('Menyimpan...');
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#tkModal').modal('hide');
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

            function addData() {
                $('#tkForm')[0].reset();
                $('#ketua_id').val(null).trigger('change');
                $('#anggota_ids').val(null).trigger('change');
                $('#id_tim_kerja').val('');
                $('#formMethod').val('POST');
                $('#tkModalLabel').text('Tambah Tim Kerja');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#tkModal').modal('show');
            }

            function editData(id) {
                $.get("{{ route('tim-kerja.index') }}/" + id + "/edit", function(data) {
                    $('#tkModalLabel').text('Edit Tim Kerja');
                    $('#id_tim_kerja').val(data.id_tim_kerja);
                    $('#nama_timkerja').val(data.nama_timkerja);

                    if (data.ketua) {
                        var newOption = new Option(data.ketua.name, data.ketua.id, true, true);
                        $('#ketua_id').append(newOption).trigger('change');
                    } else {
                        $('#ketua_id').val(null).trigger('change');
                    }

                    $('#anggota_ids').val(null).empty().trigger('change');
                    if (data.users && data.users.length > 0) {
                        data.users.forEach(function(user) {
                            var newOption = new Option(user.name, user.id, true, true);
                            $('#anggota_ids').append(newOption);
                        });
                        $('#anggota_ids').trigger('change');
                    }

                    $('#formMethod').val('PATCH');
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    $('#tkModal').modal('show');
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
                            url: "{{ route('tim-kerja.index') }}/" + id,
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
