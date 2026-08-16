<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Layanan
                        </h1>
                        <div class="page-header-subtitle">Manajemen Data Layanan</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">
                Daftar Master Layanan
                <button class="btn btn-primary btn-sm float-end" type="button" onclick="addData()">
                    <i data-feather="plus" class="me-1"></i> Tambah Master Layanan
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Layanan</th>
                                <th>Deskripsi</th>
                                <th>Durasi</th>
                                <th>Input Tambahan</th>
                                <th>Kategori User</th>
                                <th>Status</th>
                                <th>Tim Kerja</th>
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
    <div class="modal fade" id="layananModal" tabindex="-1" role="dialog" aria-labelledby="layananModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="layananModalLabel">Tambah Layanan</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="layananForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="nama_layanan">Nama Layanan <span class="text-danger">*</span></label>
                            <input class="form-control" id="nama_layanan" name="nama_layanan" type="text"
                                placeholder="Masukkan nama layanan" required>
                            <div class="invalid-feedback" id="error-nama_timkerja"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                            <input class="form-control" id="deskripsi" name="deskripsi" type="text"
                                placeholder="Masukkan deskripsi" required>
                            <div class="invalid-feedback" id="error-deskripsi"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="durasi_hari">Durasi Layanan (Hari) <span class="text-danger">*</span></label>
                            <input class="form-control" id="durasi_hari" name="durasi_hari" type="number"
                                min="0" placeholder="Masukkan durasi layanan dalam hari" required>
                            <div class="invalid-feedback" id="error-durasi_hari"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="user_category">Kategori User <span class="text-danger">*</span></label>
                            <select class="form-control" id="user_category" name="user_category" required>
                                <option value="semua">Semua</option>
                                <option value="umum">Umum</option>
                                <option value="pemerintah">Pemerintah</option>
                            </select>
                            <div class="invalid-feedback" id="error-user_category"></div>
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
                            <label class="small mb-1" for="tim_kerja">Tim Kerja <span class="text-danger">*</span></label>
                            <select class="form-control" id="tim_kerja_id" name="tim_kerja_id" required>
                                <option value="">Pilih Tim Kerja</option>
                                @foreach ($timKerja as $tim)
                                    <option value="{{ $tim->id_tim_kerja }}">{{ $tim->nama_timkerja }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-tim_kerja"></div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="small fw-bold mb-0">Input Tambahan (Opsional)</label>
                            <button type="button" class="btn btn-xs btn-outline-primary" onclick="addInputField()">
                                <i data-feather="plus" class="me-1" style="width: 12px; height: 12px;"></i> Tambah Field
                            </button>
                        </div>
                        <div id="inputTambahanContainer">
                            <!-- Dynamic fields will be added here -->
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
        <style>
            .btn-datatable {
                height: 2rem;
                width: 2rem;
                padding: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .extra-small {
                font-size: 0.75rem;
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
                    ajax: "{{ route('layanan.data') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_layanan',
                            name: 'nama_layanan'
                        },
                        {
                            data: 'deskripsi',
                            name: 'deskripsi',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'durasi_hari',
                            name: 'durasi_hari',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'input_tambahan',
                            name: 'input_tambahan',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user_category',
                            name: 'user_category',
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
                            data: 'tim_kerja_id',
                            name: 'tim_kerja_id',
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

                $('#layananForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#id').val();
                    let url = id ? "{{ route('layanan.update', ':id') }}".replace(':id', id) :
                        "{{ route('layanan.store') }}";

                    $('#btnSave').prop('disabled', true).text('Menyimpan...');
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#layananModal').modal('hide');
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

            let fieldCount = 0;

            function addInputField(data = null) {
                const container = $('#inputTambahanContainer');
                const id = fieldCount++;

                const html = `
                    <div class="card bg-light mb-2 input-field-item" id="field-${id}">
                        <div class="card-body p-2">
                            <div class="row gx-2">
                                <div class="col-md-5">
                                    <input type="text" name="input_tambahan[${id}][label]" class="form-control form-control-sm" placeholder="Label Field" value="${data ? data.label : ''}" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="input_tambahan[${id}][type]" class="form-select form-select-sm" required>
                                        <option value="text" ${data && data.type === 'text' ? 'selected' : ''}>Teks</option>
                                        <option value="number" ${data && data.type === 'number' ? 'selected' : ''}>Angka</option>
                                        <option value="date" ${data && data.type === 'date' ? 'selected' : ''}>Tanggal</option>
                                        <option value="textarea" ${data && data.type === 'textarea' ? 'selected' : ''}>Textarea</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                    <div class="form-check form-switch mb-0">
                                        <input type="hidden" name="input_tambahan[${id}][required]" value="0">
                                        <input class="form-check-input" type="checkbox" name="input_tambahan[${id}][required]" value="1" ${data && (data.required == 1 || data.required == "1") ? 'checked' : ''}>
                                        <label class="extra-small ms-1">Wajib</label>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger border-0" onclick="removeInputField(${id})">
                                        <i data-feather="x" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                container.append(html);
                feather.replace();
            }

            function removeInputField(id) {
                $(`#field-${id}`).remove();
            }

            function addData() {
                $('#layananForm')[0].reset();
                $('#id').val('');
                $('#inputTambahanContainer').empty();
                fieldCount = 0;
                $('#formMethod').val('POST');
                $('#layananModalLabel').text('Tambah Layanan');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#layananModal').modal('show');
            }

            function editData(id) {
                $.get("{{ route('layanan.index') }}/" + id + "/edit", function(data) {
                    $('#layananModalLabel').text('Edit Layanan');
                    $('#id').val(data.id);
                    $('#nama_layanan').val(data.nama_layanan);
                    $('#deskripsi').val(data.deskripsi);
                    $('#durasi_hari').val(data.durasi_hari);
                    $('#user_category').val(data.user_category);
                    $('#status').val(data.status);
                    $('#tim_kerja_id').val(data.tim_kerja_id);

                    $('#inputTambahanContainer').empty();
                    fieldCount = 0;

                    if (data.input_tambahan) {
                        // Handle both array and object (associative array)
                        let fields = Array.isArray(data.input_tambahan) ?
                            data.input_tambahan :
                            Object.values(data.input_tambahan);

                        fields.forEach(field => {
                            if (field && typeof field === 'object') {
                                addInputField(field);
                            }
                        });
                    }

                    $('#formMethod').val('PATCH');
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    $('#layananModal').modal('show');
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
                            url: "{{ route('layanan.index') }}/" + id,
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
