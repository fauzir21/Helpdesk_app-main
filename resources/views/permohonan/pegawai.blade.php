<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="briefcase"></i></div>
                            Panel Pegawai
                        </h1>
                        <div class="page-header-subtitle">Manajemen permohonan yang masuk</div>
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
                    Daftar Semua Permohonan
                </div>
                <div>
                    <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                        <option value="">Semua Status</option>
                        <option value="MENUNGGU_DIPROSES">MENUNGGU DIPROSES</option>
                        <option value="DIPROSES">DIPROSES</option>
                        <option value="DITOLAK">DITOLAK</option>
                        <option value="PERBAIKAN">PERBAIKAN</option>
                        <option value="SELESAI">SELESAI</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="helpdeskTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nomor Tiket</th>
                                <th>Layanan</th>
                                <th>Detail Tambahan</th>
                                <th>Pemohon</th>
                                <th width="15%">Tanggal Masuk</th>
                                <th width="15%">Deadline</th>
                                <th width="10%">Status</th>
                                <th width="15%">Aksi</th>
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
            let helpdeskTable;
            $(document).ready(function() {
                helpdeskTable = $('#helpdeskTable').DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "{{ route('permohonan.data') }}",
                        data: function(d) {
                            d.status = $('#statusFilter').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'nomor_tiket',
                            name: 'nomor_tiket'
                        },
                        {
                            data: 'nama_layanan',
                            name: 'nama_layanan'
                        },
                        {
                            data: 'detail_tambahan',
                            name: 'detail_tambahan'
                        },
                        {
                            data: 'user',
                            name: 'user'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'deadline',
                            name: 'deadline'
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
                    },
                    language: {
                        url: "{{ asset('js/datatables/id.json') }}"
                    }
                });

                $('#statusFilter').on('change', function() {
                    helpdeskTable.ajax.reload();
                });

                @if(request()->has('q') && request()->get('q') != '')
                    helpdeskTable.search("{{ request()->get('q') }}").draw();
                @endif
            });

            function updateStatus(id, status) {
                let actionText = status === 'DIPROSES' ? 'proses' : 'tolak';
                let confirmColor = status === 'DIPROSES' ? '#00ac69' : '#e81500';

                Swal.fire({
                    title: `Apakah anda yakin ingin ${actionText} permohonan ini?`,
                    text: "Berikan alasan atau keterangan (opsional):",
                    input: 'textarea',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    confirmButtonText: `Ya, ${status === 'DIPROSES' ? 'Proses' : 'Tolak'}!`,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('permohonan') }}/" + id + "/update-status",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                status: status,
                                keterangan: result.value
                            },
                            success: (res) => {
                                Swal.fire('Berhasil', res.message, 'success');
                                table.ajax.reload();
                            },
                            error: (xhr) => {
                                const msg = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                                Swal.fire('Error', msg, 'error');
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
