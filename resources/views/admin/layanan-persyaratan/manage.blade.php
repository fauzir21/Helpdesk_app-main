<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="settings"></i></div>
                            Kelola Struktur Layanan
                        </h1>
                        <div class="page-header-subtitle">Layanan: <strong>{{ $layanan->nama_layanan }}</strong></div>
                    </div>
                    <div class="col-12 col-xl-auto mt-4">
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('layanan-persyaratan.index') }}">
                            <i class="me-1" data-feather="arrow-left"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <!-- Sidebar: Master Persyaratan -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card card-header-actions h-100">
                    <div class="card-header">
                        Master Persyaratan
                        <i class="text-muted" data-feather="info" data-bs-toggle="tooltip"
                            title="Daftar persyaratan yang tersedia di sistem"></i>
                    </div>
                    <div class="card-body">
                        <div class="input-group input-group-joined mb-3">
                            <span class="input-group-text"><i data-feather="search"></i></span>
                            <input class="form-control" type="text" id="searchMaster"
                                placeholder="Cari persyaratan..." aria-label="Search">
                        </div>
                        <div class="list-group list-group-flush scrollable-list" id="masterPersyaratanList">
                            @forelse($availablePersyaratan as $p)
                                <div class="list-group-item d-flex justify-content-between align-items-center master-item"
                                    data-nama="{{ strtolower($p->nama_persyaratan) }}">
                                    <div>
                                        <div class="small fw-500">{{ $p->nama_persyaratan }}</div>
                                        <div class="text-muted small">{{ $p->tipe_label }}</div>
                                    </div>
                                    <button class="btn btn-sm btn-icon btn-outline-primary"
                                        onclick="addToService({{ $p->id }})" title="Tambahkan ke Layanan">
                                        <i data-feather="plus"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center text-muted small py-4">Semua persyaratan sudah ditambahkan.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Workspace -->
            <div class="col-xl-8 col-lg-7">
                <!-- Uncategorized Section -->
                <div class="card mb-4 border-start-lg border-start-yellow">
                    <div
                        class="card-header bg-yellow-soft text-yellow d-flex justify-content-between align-items-center">
                        <div>
                            <i class="me-2" data-feather="alert-circle"></i>
                            Belum Dikategorikan
                            <span class="badge bg-yellow text-dark ms-2">{{ $uncategorized->count() }}</span>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="showAddCategoryModal()">
                            <i class="me-1" data-feather="plus"></i> Tambah Kategori
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="uncategorizedTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Persyaratan</th>
                                        <th width="40%">Pindahkan ke Kategori</th>
                                        <th width="10%" class="text-center">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody id="uncategorizedArea">
                                    @foreach ($uncategorized as $index => $lp)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-bold small">{{ $lp->persyaratan->nama_persyaratan }}
                                                </div>
                                                <div class="text-muted extra-small">{{ $lp->persyaratan->tipe_label }}
                                                </div>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm"
                                                    onchange="moveToCategory({{ $lp->id }}, this.value)">
                                                    <option value="">Pilih Kategori...</option>
                                                    @foreach ($layanan->kategori as $kat)
                                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-icon btn-transparent-dark"
                                                    onclick="removeFromService({{ $lp->id }})">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- card kategori penugasan --}}
    <div class="container-xl px-5 mt-4">
        <div class="row">
            <!-- Categories Section -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Kategori & Penugasan</h5>
            </div>

            <div class="row g-4" id="categoriesArea">
                @foreach ($layanan->kategori as $kat)
                    <div class="col-md-4 category-card">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                <div class="fw-bold text-primary category-name">{{ $kat->nama_kategori }}</div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-transparent-dark" type="button"
                                        data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            onclick="editCategory({{ $kat->id }}, '{{ $kat->nama_kategori }}', {{ $kat->users->pluck('id') }})">Edit
                                            Kategori</a>
                                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                                            onclick="deleteCategory({{ $kat->id }})">Hapus</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Users Assigned -->
                                <div class="mb-3">
                                    <div class="text-muted small mb-1">Pegawai Bertugas:</div>
                                    <div class="d-flex flex-wrap gap-1">
                                        @forelse($kat->users as $u)
                                            <span class="badge bg-blue-soft text-blue">{{ $u->name }}</span>
                                        @empty
                                            <span class="text-muted small italic">Belum ada pegawai
                                                ditugaskan.</span>
                                        @endforelse
                                    </div>
                                </div>
                                <hr class="my-3 bg-dark">
                                <!-- Requirements in this category -->
                                <div class="text-muted small mb-2">Daftar Persyaratan:</div>
                                <div class="list-group list-group-flush mb-3">
                                    @forelse($kat->layananPersyaratan as $lp)
                                        <div
                                            class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center border-0 categories-item">
                                            <div class="small" style="width: 70%;">-
                                                {{ $lp->persyaratan->nama_persyaratan }}</div>
                                            <div class="dropdown">

                                                <button class="btn btn-xs bg-yellow text-white" type="button"
                                                    data-bs-toggle="dropdown">
                                                    Pindah
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <h6 class="dropdown-header">Pindah ke:</h6>
                                                    @foreach ($layanan->kategori as $otherKat)
                                                        @if ($otherKat->id != $kat->id)
                                                            <a class="dropdown-item small" href="javascript:void(0)"
                                                                onclick="moveToCategory({{ $lp->id }}, {{ $otherKat->id }})">{{ $otherKat->nama_kategori }}</a>
                                                        @endif
                                                    @endforeach
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item small text-danger fw-bold"
                                                        href="javascript:void(0)"
                                                        onclick="moveToCategory({{ $lp->id }}, '')">
                                                        <i class="me-1" data-feather="log-out"
                                                            style="width: 12px"></i> Keluarkan
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @empty
                                        <div class="text-muted small py-2 italic">Belum ada persyaratan di kategori
                                            ini.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Add/Edit Kategori -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Tambah Kategori Baru</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="categoryForm">
                    @csrf
                    <input type="hidden" name="id" id="modal_kategori_id">
                    <input type="hidden" name="layanan_id" value="{{ $layanan->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-1">Nama Kategori</label>
                            <input type="text" class="form-control" name="nama_kategori" id="modal_nama_kategori"
                                required placeholder="Contoh: Berkas Administrasi">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Tugaskan Pegawai</label>
                            <select class="form-control select2" name="user_ids[]" id="modal_user_ids" multiple
                                style="width: 100%">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
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
            .scrollable-list {
                max-height: 500px;
                overflow-y: auto;
            }

            .master-item:hover {
                background-color: #f8f9fa;
            }

            .italic {
                font-style: italic;
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
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

    @push('after')
        <script>
            $(document).ready(function() {
                // Inisialisasi DataTable untuk Belum Dikategorikan
                $('#uncategorizedTable').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                    },
                    "pageLength": 10,
                    "drawCallback": function() {
                        feather.replace();
                    }
                });

                // Inisialisasi Feather Icons
                feather.replace();

                $('.select2').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#categoryModal')
                });

                // Search Filter Master Persyaratan
                $('#searchMaster').on('input', function() {
                    let val = $(this).val().toLowerCase().trim();

                    $('.master-item').each(function() {
                        // Mengambil teks hanya dari judul persyaratan (elemen fw-500)
                        let requirementName = $(this).find('.fw-500').text().toLowerCase();

                        if (requirementName.indexOf(val) > -1) {
                            $(this).removeClass('d-none').addClass('d-flex');
                        } else {
                            $(this).removeClass('d-flex').addClass('d-none');
                        }
                    });
                });

                console.log('hai');

                // Category Form Submit
                $('#categoryForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#modal_kategori_id').val();
                    let url = id ? "{{ route('layanan-kategori.update', ':id') }}".replace(':id', id) :
                        "{{ route('layanan-kategori.store') }}";

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: $(this).serialize() + (id ? '&_method=PATCH' : ''),
                        success: function(response) {
                            location.reload();
                        },
                        error: function() {
                            Swal.fire('Error', 'Gagal menyimpan kategori.', 'error');
                        }
                    });
                });
            });

            function addToService(persyaratanId) {
                $.ajax({
                    url: "{{ route('layanan-persyaratan.add-to-service', $layanan->id) }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        persyaratan_id: persyaratanId
                    },
                    success: function() {
                        location.reload();
                    }
                });
            }

            function removeFromService(pivotId) {
                Swal.fire({
                    title: 'Hapus dari Layanan?',
                    text: 'Persyaratan ini akan dihapus dari layanan ini.',
                    icon: 'warning',
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('layanan-persyaratan.remove-from-service', ':id') }}".replace(':id',
                                pivotId),
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            success: function() {
                                location.reload();
                            }
                        });
                    }
                });
            }

            function moveToCategory(pivotId, kategoriId) {
                $.ajax({
                    url: "{{ route('layanan-kategori.assign-persyaratan') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        layanan_persyaratan_ids: [pivotId],
                        layanan_kategori_id: kategoriId
                    },
                    success: function() {
                        location.reload();
                    }
                });
            }

            function showAddCategoryModal() {
                $('#categoryModalLabel').text('Tambah Kategori Baru');
                $('#modal_kategori_id').val('');
                $('#modal_nama_kategori').val('');
                $('#modal_user_ids').val(null).trigger('change');
                $('#categoryModal').modal('show');
            }

            function editCategory(id, name, userIds) {
                $('#categoryModalLabel').text('Edit Kategori');
                $('#modal_kategori_id').val(id);
                $('#modal_nama_kategori').val(name);
                $('#modal_user_ids').val(userIds).trigger('change');
                $('#categoryModal').modal('show');
            }

            function deleteCategory(id) {
                Swal.fire({
                    title: 'Hapus Kategori?',
                    text: 'Persyaratan di dalamnya akan dipindah ke "Belum Dikategorikan".',
                    icon: 'warning',
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('layanan-kategori.destroy', ':id') }}".replace(':id', id),
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            success: function() {
                                location.reload();
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
