<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="briefcase"></i></div>
                            Panel Layanan
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
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="download" class="me-1"></i> Rekap Pengajuan
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportData('excel')"><i class="far fa-file-excel me-2 text-success"></i> Excel</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportData('pdf')"><i class="far fa-file-pdf me-2 text-danger"></i> PDF</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4 g-3">
                    @if (Auth::user()->tipe === 'admin' || Auth::user()->tipe === 'helpdesk')
                        <div class="col-md-3">
                            <label class="small mb-1">Filter Tim Kerja</label>
                            <select class="form-select form-select-sm" id="timKerjaFilter">
                                <option value="">Semua Tim Kerja</option>
                                @foreach ($timKerja as $tim)
                                    <option value="{{ $tim->id_tim_kerja }}">{{ $tim->nama_timkerja }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <label class="small mb-1">Filter Status</label>
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="MENUNGGU_DIPROSES">MENUNGGU DIPROSES</option>
                            <option value="DIPROSES">DIPROSES</option>
                            <option value="DITOLAK">DITOLAK</option>
                            <option value="PERBAIKAN">PERBAIKAN</option>
                            <option value="SELESAI">SELESAI</option>
                            <option value="SELESAI_PEMERIKSAAN">SELESAI PEMERIKSAAN</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small mb-1">Filter Tanggal</label>
                        <input type="date" class="form-control form-control-sm" id="dateFilter">
                    </div>
                    <div class="col-md-2">
                        <label class="small mb-1">Filter Bulan</label>
                        <select class="form-select form-select-sm" id="monthFilter">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small mb-1">Filter Triwulan</label>
                        <select class="form-select form-select-sm" id="quarterFilter">
                            <option value="">Semua Triwulan</option>
                            <option value="1">Triwulan 1 (Jan - Mar)</option>
                            <option value="2">Triwulan 2 (Apr - Jun)</option>
                            <option value="3">Triwulan 3 (Jul - Sep)</option>
                            <option value="4">Triwulan 4 (Okt - Des)</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="small mb-1">Tahun</label>
                        <select class="form-select form-select-sm" id="yearFilter">
                            @foreach(range(date('Y'), date('Y') - 5) as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-sm btn-secondary w-100" onclick="resetFilters()">
                            <i data-feather="refresh-cw" class="me-1"></i> Reset
                        </button>
                    </div>
                </div>

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
                            d.tim_kerja = $('#timKerjaFilter').val();
                            d.status = $('#statusFilter').val();
                            d.date = $('#dateFilter').val();
                            d.month = $('#monthFilter').val();
                            d.quarter = $('#quarterFilter').val();
                            d.year = $('#yearFilter').val();
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

                $('#timKerjaFilter, #statusFilter, #dateFilter, #monthFilter, #quarterFilter, #yearFilter').on('change', function() {
                    helpdeskTable.ajax.reload();
                });

                @if(request()->has('q') && request()->get('q') != '')
                    helpdeskTable.search("{{ request()->get('q') }}").draw();
                @endif
            });

            function exportData(format) {
                const tim_kerja = $('#timKerjaFilter').val();
                const status = $('#statusFilter').val();
                const date = $('#dateFilter').val();
                const month = $('#monthFilter').val();
                const quarter = $('#quarterFilter').val();
                const year = $('#yearFilter').val();

                let url = "{{ route('permohonan.export') }}?format=" + format;
                if (tim_kerja) url += "&tim_kerja=" + tim_kerja;
                if (status) url += "&status=" + status;
                if (date) url += "&date=" + date;
                if (month) url += "&month=" + month;
                if (quarter) url += "&quarter=" + quarter;
                if (year) url += "&year=" + year;

                window.location.href = url;
            }

            function resetFilters() {
                $('#timKerjaFilter').val('');
                $('#statusFilter').val('');
                $('#dateFilter').val('');
                $('#monthFilter').val('');
                $('#quarterFilter').val('');
                $('#yearFilter').val("{{ date('Y') }}");
                helpdeskTable.ajax.reload();
            }

            function updateStatus(id, status) {
                let actionText = '';
                let confirmColor = '';
                let isFinal = status === 'SELESAI';

                if (status === 'DIPROSES') {
                    actionText = 'memproses';
                    confirmColor = '#00ac69';
                } else if (status === 'DITOLAK') {
                    actionText = 'menolak';
                    confirmColor = '#e81500';
                } else if (status === 'SELESAI') {
                    actionText = 'menyelesaikan';
                    confirmColor = '#00ac69';
                }

                if (isFinal) {
                    Swal.fire({
                        title: 'Selesaikan Permohonan',
                        html: `
                            <div class="text-start mb-3">
                                <p class="small mb-2">Permohonan akan ditandai Selesai. Pastikan semua berkas sudah diverifikasi dan sertakan dokumen hasil.</p>
                                <label class="small fw-bold mb-1">Dokumen Hasil (Bisa pilih lebih dari satu)</label>
                                <input type="file" id="swal-file-hasil-helpdesk" class="form-control" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="mt-3">
                                    <label class="small fw-bold mb-1">Keterangan (Opsional)</label>
                                    <textarea id="swal-keterangan-helpdesk" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Selesaikan',
                        cancelButtonText: 'Batal',
                        preConfirm: () => {
                            const files = document.getElementById('swal-file-hasil-helpdesk').files;
                            const keterangan = document.getElementById('swal-keterangan-helpdesk').value;
                            if (files.length === 0) {
                                Swal.showValidationMessage('Harap pilih minimal satu dokumen hasil.');
                                return false;
                            }
                            return { files: files, keterangan: keterangan };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData();
                            formData.append('_token', '{{ csrf_token() }}');
                            formData.append('status', status);
                            formData.append('keterangan', result.value.keterangan || 'Permohonan telah selesai diproses.');
                            
                            for (let i = 0; i < result.value.files.length; i++) {
                                formData.append('file_hasil[]', result.value.files[i]);
                            }

                            sendAjaxUpdate(id, formData);
                        }
                    });
                } else {
                    Swal.fire({
                        title: `Apakah anda yakin ingin ${actionText} permohonan ini?`,
                        text: "Berikan alasan atau keterangan (opsional):",
                        input: 'textarea',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        confirmButtonText: `Ya, ${status.replace('_', ' ')}!`,
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData();
                            formData.append('_token', "{{ csrf_token() }}");
                            formData.append('status', status);
                            formData.append('keterangan', result.value || '');
                            
                            sendAjaxUpdate(id, formData);
                        }
                    });
                }
            }

            function sendAjaxUpdate(id, formData) {
                $.ajax({
                    url: "{{ url('permohonan') }}/" + id + "/update-status",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (res) => {
                        Swal.fire('Berhasil', res.message, 'success');
                        helpdeskTable.ajax.reload();
                    },
                    error: (xhr) => {
                        const msg = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
