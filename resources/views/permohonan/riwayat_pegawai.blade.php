<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="check-square"></i></div>
                            Riwayat Penyelesaian Saya
                        </h1>
                        <div class="page-header-subtitle">Daftar permohonan yang telah Anda proses dan selesaikan</div>
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
                    Filter Riwayat
                </div>
                <div class="d-flex align-items-center">
                    <select class="form-select form-select-sm me-3" id="statusFilter" style="width: auto;">
                        <option value="">Semua Status</option>
                        <option value="DIPROSES">DIPROSES</option>
                        <option value="PERBAIKAN">PERBAIKAN</option>
                        <option value="SELESAI_PEMERIKSAAN">SELESAI PEMERIKSAAN</option>
                        <option value="SELESAI" selected>SELESAI</option>
                        <option value="DITOLAK">DITOLAK</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="riwayatTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nomor Tiket</th>
                                <th>Layanan</th>
                                <th>Pemohon</th>
                                <th width="15%">Tanggal Masuk</th>
                                <th width="15%">Status Akhir</th>
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
                let riwayatTable = $('#riwayatTable').DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "{{ route('riwayat-pengerjaan.data') }}",
                        data: function(d) {
                            d.status = $('#statusFilter').val();
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                        { data: 'nomor_tiket', name: 'nomor_tiket' },
                        { data: 'nama_layanan', name: 'nama_layanan' },
                        { data: 'user', name: 'user' },
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
                    },
                    order: [[0, 'asc']]
                });

                $('#statusFilter').on('change', function() {
                    riwayatTable.ajax.reload();
                });
            });
        </script>
    @endpush
</x-app-layout>
