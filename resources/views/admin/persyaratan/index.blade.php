<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Persyaratan
                        </h1>
                        <div class="page-header-subtitle">Manajemen Data Persyaratan</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">
                Daftar Persyaratan
                <button class="btn btn-primary btn-sm float-end" type="button" onclick="addData()">
                    <i data-feather="plus" class="me-1"></i> Tambah Persyaratan
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Persyaratan</th>
                                <th>Deskripsi</th>
                                <th>Jenis Persyaratan</th>
                                <th>Sifat</th>
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

    <!-- Modal Detail Deskripsi -->
    <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel">Detail Deskripsi</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="fullDescription"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="persyaratanModal" tabindex="-1" role="dialog" aria-labelledby="persyaratanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="persyaratanModalLabel">Tambah Persyaratan</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="persyaratanForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="nama_persyaratan">Nama Persyaratan <span class="text-danger">*</span></label>
                            <input class="form-control" id="nama_persyaratan" name="nama_persyaratan" type="text"
                                placeholder="Masukkan nama persyaratan" required>
                            <div class="invalid-feedback" id="error-nama_persyaratan"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="deskripsi">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                placeholder="Masukkan deskripsi persyaratan"></textarea>
                            <div class="invalid-feedback" id="error-deskripsi"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="tipe">Jenis Persyaratan <span class="text-danger">*</span></label>
                            <select class="form-control" id="tipe" name="tipe" required>
                                <option value="">Pilih Jenis Persyaratan</option>
                                <option value="file">File</option>
                                <option value="text">Text</option>
                            </select>
                            <div class="invalid-feedback" id="error-tipe"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="wajib">Sifat Persyaratan <span class="text-danger">*</span></label>
                            <select class="form-control" id="wajib" name="wajib" required>
                                <option value="">Pilih Sifat Persyaratan</option>
                                <option value="1">Wajib</option>
                                <option value="0">Opsional</option>
                            </select>
                            <div class="invalid-feedback" id="error-wajib"></div>
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
    @endpush

    @push('after')
        <script>
            let table;
            $(document).ready(function() {
                table = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('persyaratan.data') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_persyaratan',
                            name: 'nama_persyaratan'
                        },
                        {
                            data: 'deskripsi',
                            name: 'deskripsi',
                            render: function(data, type, row) {
                                if (data && data.length > 50) {
                                    return data.substr(0, 50) + '... <a href="javascript:void(0)" onclick="showFullDescription(\'' + encodeURIComponent(data) + '\')">Lihat Lengkap</a>';
                                }
                                return data || '-';
                            }
                        },
                        {
                            data: 'tipe',
                            name: 'tipe',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'wajib',
                            name: 'wajib',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
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

                $('#persyaratanForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#id').val();
                    let url = id ? "{{ route('persyaratan.update', ':id') }}".replace(':id', id) :
                        "{{ route('persyaratan.store') }}";

                    $('#btnSave').prop('disabled', true).text('Menyimpan...');
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#persyaratanModal').modal('hide');
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
                $('#persyaratanForm')[0].reset();
                $('#id').val('');
                $('#formMethod').val('POST');
                $('#persyaratanModalLabel').text('Tambah Persyaratan');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#persyaratanModal').modal('show');
            }

            function editData(id) {
                $.get("{{ route('persyaratan.index') }}/" + id + "/edit", function(data) {
                    $('#persyaratanModalLabel').text('Edit Persyaratan');
                    $('#id').val(data.id);
                    $('#nama_persyaratan').val(data.nama_persyaratan);
                    $('#deskripsi').val(data.deskripsi);
                    $('#tipe').val(data.tipe);
                    $('#wajib').val(data.wajib ? "1" : "0");
                    $('#status').val(data.status);
                    $('#formMethod').val('PATCH');
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    $('#persyaratanModal').modal('show');
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
                            url: "{{ route('persyaratan.index') }}/" + id,
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

            function showFullDescription(description) {
                $('#fullDescription').text(decodeURIComponent(description));
                $('#descriptionModal').modal('show');
            }
        </script>
    @endpush
</x-app-layout>
