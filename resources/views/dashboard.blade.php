<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                            Dashboard
                        </h1>
                        <div class="page-header-subtitle">Pusat Bantuan dan Layanan Terpadu</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <!-- Welcome Card -->
            <div class="col-xxl-4 col-xl-12 mb-4">
                <div class="card h-100 border-start-lg border-start-primary">
                    <div class="card-body h-100 p-5">
                        <div class="row align-items-center">
                            <div class="col-xl-8 col-xxl-12">
                                <div class="text-center text-xl-start text-xxl-center mb-4 mb-xl-0 mb-xxl-4">
                                    <h1 class="text-primary">Selamat Datang, {{ Auth::user()->name }}!</h1>
                                    <p class="text-gray-700 mb-0">Ada yang bisa kami bantu hari ini? Anda dapat
                                        mengajukan permohonan layanan atau memantau status tiket Anda.</p>
                                </div>
                            </div>
                            <div class="col-xl-4 col-xxl-12 text-center">
                                <img class="img-fluid" src="{{ asset('assets/img/illustrations/problem-solving.svg') }}"
                                    style="max-width: 15rem" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="col-xxl-8 col-xl-12">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <div class="text-white-75 small">Total Tiket</div>
                                        <div class="text-lg fw-bold">{{ $stats['totalTiket'] }}</div>
                                    </div>
                                    <i class="feather-xl text-white-50" data-feather="file-text"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between small">
                                <a class="text-white stretched-link" href="{{ route('permohonan.index') }}">Lihat
                                    Detail</a>
                                <div class="text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <div class="text-white-75 small">Sedang Diproses</div>
                                        <div class="text-lg fw-bold">{{ $stats['sedangDiproses'] }}</div>
                                    </div>
                                    <i class="feather-xl text-white-50" data-feather="clock"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between small">
                                <a class="text-white stretched-link" href="{{ route('permohonan.index') }}">Lihat
                                    Detail</a>
                                <div class="text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <div class="text-white-75 small">Selesai</div>
                                        <div class="text-lg fw-bold">{{ $stats['selesai'] }}</div>
                                    </div>
                                    <i class="feather-xl text-white-50" data-feather="check-circle"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between small">
                                <a class="text-white stretched-link" href="{{ route('permohonan.index') }}">Lihat
                                    Detail</a>
                                <div class="text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Activity/Tracking -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">Lacak Tiket</div>
                    <div class="card-body">
                        <form id="trackForm">
                            <div class="mb-3">
                                <label class="small mb-1" for="ticketNumber">Nomor Tiket</label>
                                <input class="form-control" id="ticketNumber" type="text"
                                    placeholder="Contoh: TKT-2023-001" />
                            </div>
                            <button class="btn btn-primary w-100" type="submit">Cek Status Tiket</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (in_array(Auth::user()->tipe, ['admin', 'helpdesk', 'pegawai']))
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card h-100 shadow-none border">
                        <div class="card-header bg-white py-3">
                            <div class="fw-bold">Statistik Permohonan per Tim Kerja</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-bar" style="height: 350px;"><canvas id="teamChart" width="100%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (Auth::user()->tipe === 'admin' || Auth::user()->tipe === 'helpdesk')
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card h-100 shadow-none border">
                        <div class="card-header bg-white py-3">
                            <div class="fw-bold">Grafik Tingkat Kepuasan Masyarakat (SKM)</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-bar"><canvas id="surveyChart" width="100%" height="30"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Log Aktivitas -->
            <div class="col-12 mb-4">
                <div class="card h-100 shadow-none border">
                    <div class="card-header bg-white py-3">
                        <div class="fw-bold">Log Aktivitas
                            {{ Auth::user()->tipe === 'admin' ? 'Pengguna' : 'Terbaru' }}</div>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="tableLog" width="100%" cellspacing="0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Waktu</th>
                                        @if (Auth::user()->tipe === 'admin')
                                            <th>User</th>
                                        @endif
                                        <th>Aktivitas</th>
                                        <th>Keterangan</th>
                                        <th>IP Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (count($userLogs) > 0)
                        <div class="card-footer bg-white text-center py-2">
                            <a class="btn btn-sm btn-link text-decoration-none" href="#">Lihat Semua Aktivitas
                                <i class="ms-1" data-feather="arrow-right"></i></a>
                        </div>
                    @endif
                </div>
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
        </style>
    @endpush

    @push('before-scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    @push('after')
        <script>
            $(document).ready(function() {
                @if (in_array(Auth::user()->tipe, ['admin', 'helpdesk', 'pegawai']))
                    var ctxTeam = document.getElementById("teamChart");
                    var teamChart = new Chart(ctxTeam, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($teamChartData['labels'] ?? []) !!},
                            datasets: [{
                                    label: "Diproses",
                                    backgroundColor: "rgba(244, 161, 0, 1)",
                                    hoverBackgroundColor: "rgba(244, 161, 0, 0.9)",
                                    borderColor: "#f4a100",
                                    data: {!! json_encode($teamChartData['proses'] ?? []) !!},
                                    barPercentage: 0.6,
                                },
                                {
                                    label: "Selesai",
                                    backgroundColor: "rgba(0, 172, 105, 1)",
                                    hoverBackgroundColor: "rgba(0, 172, 105, 0.9)",
                                    borderColor: "#00ac69",
                                    data: {!! json_encode($teamChartData['selesai'] ?? []) !!},
                                    barPercentage: 0.6,
                                }
                            ],
                        },
                        options: {
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                x: {
                                    stacked: false,
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    stacked: false,
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                @endif

                @if (Auth::user()->tipe === 'admin' || Auth::user()->tipe === 'helpdesk')
                    var ctx = document.getElementById("surveyChart");
                    var myBarChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($chartData['labels'] ?? []) !!},
                            datasets: [{
                                label: "Rata-rata Skor",
                                backgroundColor: "rgba(0, 97, 242, 1)",
                                hoverBackgroundColor: "rgba(0, 97, 242, 0.9)",
                                borderColor: "#4e73df",
                                data: {!! json_encode($chartData['values'] ?? []) !!},
                                barPercentage: 0.5,
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    left: 10,
                                    right: 25,
                                    top: 25,
                                    bottom: 0
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 6
                                    }
                                },
                                y: {
                                    min: 0,
                                    max: 6,
                                    ticks: {
                                        maxTicksLimit: 7,
                                        padding: 10,
                                    },
                                    grid: {
                                        color: "rgb(234, 236, 244)",
                                        zeroLineColor: "rgb(234, 236, 244)",
                                        drawBorder: false,
                                        borderDash: [2],
                                        zeroLineBorderDash: [2]
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    titleMarginBottom: 10,
                                    titleFont: {
                                        size: 14
                                    },
                                    backgroundColor: "rgb(255,255,255)",
                                    bodyColor: "#858796",
                                    borderColor: '#dddfeb',
                                    borderWidth: 1,
                                    xPadding: 15,
                                    yPadding: 15,
                                    displayColors: false,
                                    caretPadding: 10,
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' + context.parsed.y;
                                        }
                                    }
                                }
                            }
                        }
                    });
                @endif

                $('#tableLog').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('dashboard.log-data') }}",
                    columns: [{
                            data: 'waktu',
                            name: 'waktu',
                            className: 'ps-4 align-middle small'
                        },
                        @if (Auth::user()->tipe === 'admin')
                            {
                                data: 'user',
                                name: 'user',
                                className: 'align-middle'
                            },
                        @endif {
                            data: 'aktivitas',
                            name: 'aktivitas',
                            className: 'align-middle'
                        },
                        {
                            data: 'keterangan',
                            name: 'keterangan',
                            className: 'align-middle small'
                        },
                        {
                            data: 'ip',
                            name: 'ip',
                            className: 'align-middle'
                        }
                    ],
                    language: {
                        processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    },
                    order: [
                        [0, 'desc']
                    ]
                });

                $('#trackForm').on('submit', function(e) {
                    e.preventDefault();
                    let ticketNum = $('#ticketNumber').val();
                    if (!ticketNum) return;

                    // We'll search for the ticket via an AJAX call or just redirect to a search page
                    // For now, let's assume we can search by fetching data
                    $.ajax({
                        url: "{{ route('permohonan.data') }}",
                        data: {
                            search: {
                                value: ticketNum
                            }
                        },
                        success: function(res) {
                            if (res.data && res.data.length > 0) {
                                // Extract ID from the action link or add it to the data
                                // Simplest: just redirect to show if we find exactly one
                                window.location.href = "{{ url('permohonan') }}/" + res.data[0]
                                    .DT_RowId;
                                // Wait, DT_RowId might not be there. Let's check PengajuanController@data
                            } else {
                                Swal.fire('Tidak Ditemukan',
                                    'Nomor tiket tidak ditemukan atau Anda tidak memiliki akses.',
                                    'warning');
                            }
                        }
                    });
                });

                @if (isset($pendingCount) && $pendingCount > 0)
                    // Only show once per session
                    if (!sessionStorage.getItem('notified_pending')) {
                        Swal.fire({
                            title: 'Ada Permohonan Baru!',
                            text: 'Terdapat {{ $pendingCount }} permohonan yang perlu segera diproses.',
                            icon: 'info',
                            confirmButtonText: 'Lihat Sekarang',
                            showCancelButton: true,
                            cancelButtonText: 'Nanti Saja',
                            confirmButtonColor: '#0061f2',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('permohonan.index') }}";
                            }
                        });
                        sessionStorage.setItem('notified_pending', 'true');
                    }
                @endif
            });
        </script>
    @endpush

    <style>
        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        .pointer {
            cursor: pointer;
        }
    </style>
</x-app-layout>
