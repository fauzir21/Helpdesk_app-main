<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="list"></i></div>
                            Layanan Persyaratan
                        </h1>
                        <div class="page-header-subtitle">Manajemen Data Layanan Persyaratan, Kategori & Penugasan</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">
                Daftar Layanan Persyaratan
                <button class="btn btn-primary btn-sm float-end" type="button" onclick="addData()">
                    <i data-feather="plus" class="me-1"></i> Tambah Layanan Persyaratan
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Layanan</th>
                                <th>Jumlah Persyaratan</th>
                                <th>Kategori & Penugasan</th>
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

    <!-- Modal Layanan Persyaratan (Hanya untuk inisiasi tambah banyak persyaratan ke layanan) -->
    <div class="modal fade" id="layananPersyaratanModal" tabindex="-1" role="dialog"
        aria-labelledby="layananPersyaratanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="layananPersyaratanModalLabel">Tambah Layanan Persyaratan</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="layananPersyaratanForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="layanan_id">Layanan</label>
                            <select class="form-control" id="layanan_id" name="layanan_id" required>
                                <option value="">Pilih Layanan</option>
                                @foreach ($layanan as $l)
                                    <option value="{{ $l->id }}">{{ $l->nama_layanan }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-layanan_id"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="persyaratan_ids">Persyaratan</label>
                            <select class="form-control select2" id="persyaratan_ids" name="persyaratan_ids[]"
                                multiple="multiple" style="width: 100%;" required>
                                @foreach ($persyaratan as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->nama_persyaratan }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-persyaratan_ids"></div>
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
                table = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('layanan-persyaratan.data') }}",
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
                            data: 'jumlah_persyaratan',
                            name: 'jumlah_persyaratan',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action_kategori',
                            name: 'action_kategori',
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

                $('.select2').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#layananPersyaratanModal')
                });

                $('#layananPersyaratanForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#id').val();
                    let url = id ? "{{ route('layanan-persyaratan.update', ':id') }}".replace(':id', id) :
                        "{{ route('layanan-persyaratan.store') }}";

                    $('#btnSave').prop('disabled', true).text('Menyimpan...');
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#layananPersyaratanModal').modal('hide');
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
                $('#layananPersyaratanForm')[0].reset();
                $('#persyaratan_ids').val(null).trigger('change');
                $('#id').val('');
                $('#formMethod').val('POST');
                $('#layananPersyaratanModalLabel').text('Tambah Layanan Persyaratan');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#layananPersyaratanModal').modal('show');
            }

            function editData(id) {
                $.get("{{ route('layanan-persyaratan.index') }}/" + id + "/edit", function(data) {
                    $('#layananPersyaratanModalLabel').text('Edit Layanan Persyaratan');
                    $('#id').val(data.layanan.id);
                    $('#layanan_id').val(data.layanan.id);
                    $('#persyaratan_ids').val(data.selected_ids).trigger('change');
                    $('#formMethod').val('PATCH');
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    $('#layananPersyaratanModal').modal('show');
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
                            url: "{{ route('layanan-persyaratan.index') }}/" + id,
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
