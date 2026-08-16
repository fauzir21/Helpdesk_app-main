<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="heart"></i></div>
                            Master Survei Kepuasan
                        </h1>
                        <div class="page-header-subtitle">Manajemen Pertanyaan Survei Kepuasan Masyarakat (SKM)</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">
                Daftar Pertanyaan Survei
                <button class="btn btn-primary btn-sm float-end" type="button" onclick="addData()">
                    <i data-feather="plus" class="me-1"></i> Tambah Pertanyaan
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pertanyaan Survei</th>
                                <th>Status</th>
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
    <div class="modal fade" id="surveiModal" tabindex="-1" role="dialog" aria-labelledby="surveiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="surveiModalLabel">Tambah Pertanyaan</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="surveiForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="nama_survei">Pertanyaan Survei</label>
                            <input class="form-control" id="nama_survei" name="nama_survei" type="text" placeholder="Masukkan pertanyaan survei" required>
                            <div class="invalid-feedback" id="error-nama_survei"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
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
            .btn-datatable { height: 2rem; width: 2rem; padding: 0; display: inline-flex; align-items: center; justify-content: center; }
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
                    ajax: "{{ route('master-survei.data') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'nama_survei', name: 'nama_survei' },
                        { data: 'status', name: 'status' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    drawCallback: function() {
                        feather.replace();
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    }
                });

                $('#surveiForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#id').val();
                    let url = id ? "{{ route('master-survei.update', ':id') }}".replace(':id', id) : "{{ route('master-survei.store') }}";
                    
                    $('#btnSave').prop('disabled', true).text('Menyimpan...');
                    $('.is-invalid').removeClass('is-invalid');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#surveiModal').modal('hide');
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
                                Swal.fire('Error', 'Terjadi kesalahan.', 'error');
                            }
                        },
                        complete: function() {
                            $('#btnSave').prop('disabled', false).text('Simpan');
                        }
                    });
                });
            });

            function addData() {
                $('#surveiForm')[0].reset();
                $('#id').val('');
                $('#formMethod').val('POST');
                $('#surveiModalLabel').text('Tambah Pertanyaan');
                $('#surveiModal').modal('show');
            }

            function editData(id) {
                $.get("{{ route('master-survei.index') }}/" + id + "/edit", function(data) {
                    $('#surveiModalLabel').text('Edit Pertanyaan');
                    $('#id').val(data.id);
                    $('#nama_survei').val(data.nama_survei);
                    $('#status').val(data.status);
                    $('#formMethod').val('PATCH');
                    $('#surveiModal').modal('show');
                });
            }

            function deleteData(id) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('master-survei.index') }}/" + id,
                            type: 'POST',
                            data: { _method: 'DELETE', _token: "{{ csrf_token() }}" },
                            success: function(response) {
                                Swal.fire('Berhasil', response.message, 'success');
                                table.ajax.reload();
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
