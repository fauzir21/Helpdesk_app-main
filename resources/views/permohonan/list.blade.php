<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="list"></i></div>
                            Daftar Permohonan Saya
                        </h1>
                        <div class="page-header-subtitle">Pantau status permohonan yang telah Anda ajukan</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    Daftar Permohonan
                </div>
                <div class="d-flex align-items-center">
                    <select class="form-select form-select-sm me-3" id="statusFilter" style="width: auto;">
                        <option value="">Semua Status</option>
                        <option value="DRAFT">DRAFT</option>
                        <option value="MENUNGGU_DIPROSES">MENUNGGU DIPROSES</option>
                        <option value="DIPROSES">DIPROSES</option>
                        <option value="DITOLAK">DITOLAK</option>
                        <option value="PERBAIKAN">PERBAIKAN</option>
                        <option value="SELESAI">SELESAI</option>
                    </select>
                    <a href="{{ route('permohonan.index') }}" class="btn btn-primary btn-sm">
                        <i data-feather="plus" class="me-1"></i> Buat Permohonan Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="permohonanTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nomor Tiket</th>
                                <th>Layanan</th>
                                <th>Detail Tambahan</th>
                                <th width="20%">Tanggal Pengajuan</th>
                                <th width="15%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('after-styles')
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
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
    @endpush

    @push('after')
        <script>
            $(document).ready(function() {
                let permohonanTable = $('#permohonanTable').DataTable({
                    processing: true,
                    serverSide: false, // We use client side for simplicity here as we get all data in one go
                    ajax: {
                        url: "{{ route('permohonan.data') }}",
                        data: function(d) {
                            d.status = $('#statusFilter').val();
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                        { data: 'nomor_tiket', name: 'nomor_tiket' },
                        { data: 'nama_layanan', name: 'nama_layanan' },
                        { data: 'detail_tambahan', name: 'detail_tambahan' },
                        { data: 'tanggal', name: 'tanggal' },
                        { data: 'status', name: 'status' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    drawCallback: function() {
                        feather.replace();
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    },
                    language: {
                        url: "{{ asset('js/datatables/id.json') }}"
                    }
                });

                $('#statusFilter').on('change', function() {
                    permohonanTable.ajax.reload();
                });

                @if(request()->has('q') && request()->get('q') != '')
                    permohonanTable.search("{{ request()->get('q') }}").draw();
                @endif
            });
        </script>
    @endpush
</x-app-layout>
