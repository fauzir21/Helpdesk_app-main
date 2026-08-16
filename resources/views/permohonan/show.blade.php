<x-app-layout>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file-text"></i></div>
                            Detail Permohonan: {{ $pengajuan->nomor_tiket }}
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        @if (Auth::user()->tipe === 'users')
                            @if (in_array($pengajuan->status_pengajuan, ['DRAFT', 'DITOLAK']))
                                <form action="{{ route('permohonan.destroy', $pengajuan->id) }}" method="POST"
                                    class="d-inline" id="deleteForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger me-2" onclick="confirmDelete()">
                                        <i class="me-1" data-feather="trash-2"></i>
                                        Hapus Permohonan
                                    </button>
                                </form>
                            @endif
                        @endif
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('permohonan.index') }}">
                            <i class="me-1" data-feather="arrow-left"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">Berhasil!</h5>
                {{ session('success') }}
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">Gagal!</h5>
                {{ session('error') }}
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <!-- Status Card-->
                <div class="card mb-4">
                    <div class="card-header">Informasi Permohonan</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3 fw-bold">Nomor Tiket:</div>
                            <div class="col-sm-9"><span
                                    class="badge bg-primary text-white">{{ $pengajuan->nomor_tiket }}</span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3 fw-bold">Layanan:</div>
                            <div class="col-sm-9">{{ $pengajuan->layanan->nama_layanan }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3 fw-bold">Tanggal Pengajuan:</div>
                            <div class="col-sm-9">
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3 fw-bold">Deadline:</div>
                            <div class="col-sm-9">
                                @if ($pengajuan->deadline)
                                    @php
                                        $isOverdue =
                                            $pengajuan->deadline->isPast() &&
                                            !in_array($pengajuan->status_pengajuan, ['SELESAI', 'DITOLAK']);
                                    @endphp
                                    <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                        {{ $pengajuan->deadline->translatedFormat('d F Y') }}
                                        @if ($isOverdue)
                                            <i class="fas fa-exclamation-circle ms-1" title="Melewati Deadline"></i>
                                        @endif
                                    </span>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3 fw-bold">Status:</div>
                            <div class="col-sm-9">
                                @php
                                    $statusClass = match ($pengajuan->status_pengajuan) {
                                        'DRAFT' => 'bg-secondary',
                                        'MENUNGGU_DIPROSES' => 'bg-warning',
                                        'DIPROSES' => 'bg-info',
                                        'DITOLAK' => 'bg-danger',
                                        'PERBAIKAN' => 'bg-dark',
                                        'SELESAI' => 'bg-success',
                                        'SELESAI_PEMERIKSAAN' => 'bg-primary',
                                        default => 'bg-light text-dark',
                                    };
                                @endphp
                                <span
                                    class="badge {{ $statusClass }}">{{ str_replace('_', ' ', $pengajuan->status_pengajuan) }}</span>
                            </div>
                        </div>

                        @if ($pengajuan->detail_tambahan && count($pengajuan->detail_tambahan) > 0)
                            <hr class="my-3">
                            <h6 class="text-primary mb-3">Detail Informasi Tambahan</h6>
                            @foreach ($pengajuan->layanan->input_tambahan as $field)
                                @php
                                    $fieldSlug = \Illuminate\Support\Str::slug($field['label'], '_');
                                    $value = $pengajuan->detail_tambahan[$fieldSlug] ?? '-';
                                @endphp
                                <div class="row mb-2">
                                    <div class="col-sm-3 text-muted small">{{ $field['label'] }}:</div>
                                    <div class="col-sm-9 small fw-500">{{ $value }}</div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Download Report Button for Ketua Tim --}}
                        @if (Auth::user()->tipe === 'pegawai' && $pengajuan->layanan->timKerja->ketua_id === Auth::id())
                            @if ($pengajuan->isAllDocumentsApproved())
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <hr>
                                        <a href="{{ route('permohonan.report', $pengajuan->id) }}"
                                            class="btn btn-primary w-100">
                                            <i class="fas fa-file-pdf me-2"></i> Download Laporan Pemenuhan Persyaratan
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                @if ($pengajuan->status_pengajuan === 'SELESAI' && $pengajuan->dokumenHasil->count() > 0)
                    <div class="card mb-4 border-start-lg border-start-success">
                        <div class="card-header text-success fw-bold">
                            <i class="fas fa-file-check me-1"></i> Dokumen Hasil Permohonan
                        </div>
                        <div class="card-body">
                            <p class="small text-muted mb-3">Berikut adalah dokumen hasil dari permohonan Anda yang
                                telah selesai diproses.</p>
                            <div class="list-group list-group-flush">
                                @foreach ($pengajuan->dokumenHasil as $hasil)
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                            {{ $hasil->nama_file }}
                                        </div>
                                        <a href="{{ route('permohonan.view-hasil', $hasil->id) }}" target="_blank"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-download me-1"></i> Lihat / Unduh
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Persyaratan & Berkas Card-->
                <div class="card mb-4" x-data="permohonanDokumen()">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>Persyaratan & Berkas</div>
                        @if (Auth::user()->tipe === 'users')
                            <template
                                x-if="allRequirementsMet() && (status === 'DRAFT' || status === 'PERBAIKAN' || status === 'DITOLAK')">
                                <form action="{{ route('permohonan.submit', $pengajuan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm shadow-sm">
                                        <i class="fas fa-paper-plane me-1"></i> Kirim Permohonan
                                    </button>
                                </form>
                            </template>
                        @endif

                        @if (Auth::user()->tipe === 'pegawai' &&
                                Auth::user()->timKerjas->contains('id_tim_kerja', $pengajuan->layanan->tim_kerja_id) &&
                                $pengajuan->status_pengajuan === 'DIPROSES')
                            <div class="d-flex gap-2">
                                @if ($pengajuan->layanan->timKerja && $pengajuan->layanan->timKerja->ketua_id === Auth::id())
                                    <button class="btn btn-dark btn-sm shadow-sm"
                                        @click="updatePengajuanStatus('PERBAIKAN')">
                                        <i class="fas fa-undo me-1"></i> Perbaikan
                                    </button>
                                    <button class="btn btn-success btn-sm shadow-sm"
                                        @click="updatePengajuanStatus('SELESAI')">
                                        <i class="fas fa-check-double me-1"></i> Selesai
                                    </button>
                                @endif
                            </div>
                        @endif

                        @if (Auth::user()->tipe === 'helpdesk')
                            <div class="d-flex gap-2">
                                @if ($pengajuan->status_pengajuan === 'MENUNGGU_DIPROSES')
                                    <button class="btn btn-success btn-sm shadow-sm"
                                        @click="updatePengajuanStatus('DIPROSES')">
                                        <i class="fas fa-check-circle me-1"></i> Lanjutkan
                                    </button>
                                    <button class="btn btn-danger btn-sm shadow-sm"
                                        @click="updatePengajuanStatus('DITOLAK')">
                                        <i class="fas fa-x-circle me-1"></i> Tolak
                                    </button>
                                @elseif ($pengajuan->status_pengajuan === 'SELESAI_PEMERIKSAAN')
                                    <button class="btn btn-success btn-sm shadow-sm"
                                        @click="updatePengajuanStatus('SELESAI')">
                                        <i class="fas fa-check-double me-1"></i> Selesai
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- Summary & Search -->
                        <div class="row align-items-end mb-4 gx-3">
                            <div class="col-lg-6 mb-3 mb-lg-0">
                                @php
                                    $tipeUser = Auth::user()->tipe;
                                    // Selalu tampilkan progress pengisian untuk user, atau tampilkan untuk petugas agar tahu kelengkapan berkas
                                    $showPengisian = in_array($tipeUser, ['users', 'helpdesk']);
                                    // Tampilkan progress verifikasi untuk petugas (pegawai/helpdesk), atau untuk user jika status bukan DRAFT
                                    $showVerifikasi =
                                        in_array($tipeUser, ['pegawai', 'helpdesk']) ||
                                        ($tipeUser === 'users' && $pengajuan->status_pengajuan !== 'DRAFT');
                                @endphp

                                {{-- Progress Pengisian --}}
                                @if ($showPengisian)
                                    <div class="{{ $showVerifikasi ? 'mb-3' : '' }}">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="small fw-bold text-muted">Progress Pengisian Berkas</span>
                                            <span class="small fw-bold text-primary"
                                                x-text="allRequirementsCount > 0 ? Math.round((filledRequirements.length / allRequirementsCount) * 100) + '%' : '0%'"></span>
                                        </div>
                                        <div class="progress mb-2" style="height: 10px; border-radius: 5px;">
                                            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
                                                role="progressbar"
                                                :style="'width: ' + (allRequirementsCount > 0 ? (filledRequirements.length /
                                                    allRequirementsCount * 100) : 0) + '%'">
                                            </div>
                                        </div>
                                        <div class="small text-muted d-flex justify-content-between">
                                            <span><span x-text="filledRequirements.length"></span> dari <span
                                                    x-text="allRequirementsCount"></span> persyaratan terpenuhi</span>
                                            <template x-if="allRequirementsMet()">
                                                @if ($pengajuan->status_pengajuan === 'DRAFT')
                                                    <span class="text-success fw-bold"><i
                                                            class="fas fa-check-circle me-1"></i>
                                                        Siap Dikirim</span>
                                                @endif
                                            </template>
                                        </div>
                                    </div>
                                @endif

                                {{-- Progress Verifikasi --}}
                                @if ($showVerifikasi)
                                    <div class="{{ $showPengisian ? 'mt-3' : '' }}">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="small fw-bold text-muted">Progress Verifikasi Berkas</span>
                                            <span class="small fw-bold text-success"
                                                x-text="allRequirementsCount > 0 ? Math.round((verifiedRequirements.length / allRequirementsCount) * 100) + '%' : '0%'"></span>
                                        </div>
                                        <div class="progress mb-2" style="height: 10px; border-radius: 5px;">
                                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                role="progressbar"
                                                :style="'width: ' + (allRequirementsCount > 0 ? (verifiedRequirements.length /
                                                    allRequirementsCount * 100) : 0) + '%'">
                                            </div>
                                        </div>
                                        <div class="small text-muted">
                                            <span><span x-text="verifiedRequirements.length"></span> dari <span
                                                    x-text="allRequirementsCount"></span> berkas selesai
                                                diverifikasi</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <div class="row gx-2">
                                    <div class="col-sm-7 mb-2 mb-sm-0">
                                        <div class="input-group input-group-joined border-1 shadow-none">
                                            <span class="input-group-text"><i data-feather="search"></i></span>
                                            <input class="form-control ps-0" type="text" x-model="searchQuery"
                                                placeholder="Cari nama persyaratan...">
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <select class="form-select" x-model="filterStatus">
                                            @if (Auth::user()->tipe === 'pegawai')
                                                <option value="all">Semua Status</option>
                                                <option value="unreviewed">Belum Diperiksa</option>
                                                <option value="approved">Sudah Sesuai</option>
                                                <option value="revision">Perlu Perbaikan</option>
                                                <option value="empty">Belum Diunggah</option>
                                            @else
                                                <option value="all">Semua</option>
                                                <option value="filled">Terisi</option>
                                                <option value="empty">Belum Terisi</option>
                                                <option value="revision">Perlu Perbaikan</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="mb-3 text-muted small text-uppercase fw-700">Dokumen Persyaratan:</h6>

                        @foreach ($groupedPersyaratan as $kategori => $persyaratans)
                            @php
                                $totalItems = count($persyaratans);
                                $unreviewedCount = $persyaratans
                                    ->filter(function ($lp) use ($pengajuan) {
                                        $doc = $pengajuan->dokumen->where('id_layanan_persyaratan', $lp->id)->first();
                                        return $doc && in_array($doc->status, ['DRAFT', 'BELUM_SELESAI']);
                                    })
                                    ->count();
                            @endphp
                            <div class="mb-3 border rounded shadow-none overflow-hidden" x-data="{ open: true }"
                                x-show="searchQuery === '' || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                                <div class="bg-light px-3 py-2 d-flex justify-content-between align-items-center cursor-pointer border-bottom"
                                    @click="open = !open">
                                    <div class="small fw-bold text-uppercase text-primary">
                                        <i class="fas fa-folder me-2 opacity-50"></i>{{ $kategori }}
                                        <span class="badge bg-primary-soft text-primary ms-1"
                                            style="font-size: 0.6rem">{{ $totalItems }}</span>
                                        @if (Auth::user()->tipe === 'pegawai' && $unreviewedCount > 0)
                                            <span class="badge bg-warning-soft text-warning ms-1"
                                                style="font-size: 0.6rem">
                                                {{ $unreviewedCount }} Perlu Periksa
                                            </span>
                                        @endif
                                        <br>
                                        @if (Auth::user()->tipe === 'pegawai')
                                            @php
                                                $categoryObj = $persyaratans->first()->kategori;
                                                $officers = $categoryObj ? $categoryObj->users : null;
                                            @endphp
                                            <span class="text-muted text-none fw-normal"
                                                style="text-transform: none; font-size: 0.7rem;">
                                                <i class="fas fa-user-shield me-1"></i> Petugas:
                                                @if ($officers && $officers->count() > 0)
                                                    {{ $officers->pluck('name')->implode(', ') }}
                                                @else
                                                    <span class="fst-italic">Seluruh Tim Kerja</span>
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    <i class="fas fa-chevron-down text-muted transition-300"
                                        :class="{ 'rotate-180': !open }"></i>
                                </div>
                                <div class="list-group list-group-flush" x-show="open" x-collapse>
                                    @foreach ($persyaratans as $lp)
                                        @php
                                            $existingDoc = $pengajuan->dokumen
                                                ->where('id_layanan_persyaratan', $lp->id)
                                                ->first();
                                            $isFilled = $existingDoc && ($existingDoc->file || $existingDoc->text);
                                            $docStatus = $existingDoc ? $existingDoc->status : 'EMPTY';
                                        @endphp
                                        <div class="list-group-item px-3 py-3 border-bottom-0"
                                            x-show="(searchQuery === '' || '{{ strtolower($lp->persyaratan->nama_persyaratan) }}'.includes(searchQuery.toLowerCase())) && 
                                            (filterStatus === 'all' || 
                                                (filterStatus === 'filled' && {{ $isFilled ? 'true' : 'false' }}) || 
                                                (filterStatus === 'empty' && {{ $isFilled ? 'false' : 'true' }}) ||
                                                (filterStatus === 'unreviewed' && '{{ $docStatus }}' !== 'EMPTY' && ['DRAFT', 'BELUM_SELESAI'].includes('{{ $docStatus }}')) ||
                                                (filterStatus === 'approved' && '{{ $docStatus }}' === 'SELESAI') ||
                                                (filterStatus === 'revision' && '{{ $docStatus }}' === 'PERBAIKAN'))
">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-start">
                                                    <div
                                                        class="icon-stack {{ $isFilled ? 'bg-success' : 'bg-gray-200' }} text-white me-3 flex-shrink-0">
                                                        <i data-feather="{{ $isFilled ? 'check' : 'file-text' }}"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark">
                                                            {{ $lp->persyaratan->nama_persyaratan }}
                                                            @if ($lp->persyaratan->wajib)
                                                                <span class="badge bg-red-soft text-red ms-1"
                                                                    style="font-size: 0.65rem">Wajib</span>
                                                            @else
                                                                <span class="badge bg-gray-200 text-gray-600 ms-1"
                                                                    style="font-size: 0.65rem">Opsional</span>
                                                            @endif
                                                        </div>
                                                        @if ($lp->persyaratan->deskripsi)
                                                            <div class="small text-muted mb-1"
                                                                style="font-size: 0.75rem">
                                                                {{ $lp->persyaratan->deskripsi }}
                                                            </div>
                                                        @endif
                                                        <div class="small text-muted">Tipe:
                                                            {{ ucfirst($lp->persyaratan->tipe) }}</div>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    @if (Auth::user()->tipe === 'users')
                                                        @if (
                                                            ($pengajuan->status_pengajuan === 'DRAFT' ||
                                                                $pengajuan->status_pengajuan === 'PERBAIKAN' ||
                                                                $pengajuan->status_pengajuan === 'DITOLAK') &&
                                                                (!$existingDoc || $existingDoc->status !== 'SELESAI'))
                                                            <button class="btn btn-primary btn-sm px-3"
                                                                @click="openModal('{{ $lp->id }}', '{{ addslashes($lp->persyaratan->nama_persyaratan) }}', '{{ $lp->persyaratan->tipe }}', '{{ $existingDoc ? ($lp->persyaratan->tipe === 'text' ? addslashes($existingDoc->text) : '') : '' }}')">
                                                                <i class="fas fa-upload me-1 small"></i>
                                                                {{ $isFilled ? 'Ubah' : 'Unggah' }}
                                                            </button>
                                                        @elseif($existingDoc && $existingDoc->status === 'SELESAI')
                                                            <span class="badge bg-success-soft text-success px-2 py-2">
                                                                <i class="fas fa-lock me-1"></i> Terverifikasi
                                                            </span>
                                                        @endif
                                                    @endif
                                                    @if (Auth::user()->tipe === 'pegawai' &&
                                                            Auth::user()->timKerjas->contains('id_tim_kerja', $pengajuan->layanan->tim_kerja_id) &&
                                                            $isFilled &&
                                                            $pengajuan->status_pengajuan === 'DIPROSES')
                                                        @php
                                                            $userKategoriIds = Auth::user()
                                                                ->kategoriPersyaratan()
                                                                ->pluck('tb_layanan_kategoris.id');
                                                            $canReview =
                                                                is_null($lp->layanan_kategori_id) ||
                                                                $userKategoriIds->contains($lp->layanan_kategori_id);
                                                        @endphp

                                                        <div class="d-flex gap-1">
                                                            @if ($canReview)
                                                                <button class="btn btn-outline-info btn-sm"
                                                                    @click="openReviewModal('{{ $existingDoc->id }}', '{{ addslashes($lp->persyaratan->nama_persyaratan) }}', '{{ $existingDoc->status }}', {{ json_encode($existingDoc->catatan ? $existingDoc->catatan->catatan : '') }}, '{{ $lp->persyaratan->tipe }}', {{ json_encode($lp->persyaratan->tipe === 'file' ? route('dokumen.view', $existingDoc->id) : $existingDoc->text) }})">
                                                                    <i class="fas fa-search me-1"></i> Periksa
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-outline-warning btn-sm"
                                                                @click="openHistoryModal('{{ addslashes($lp->persyaratan->nama_persyaratan) }}', {{ $existingDoc->catatans->toJson() }})">
                                                                <i class="fas fa-history me-1"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if ($isFilled)
                                                <div
                                                    class="ms-5 mt-2 small d-flex align-items-center justify-content-between bg-light p-2 rounded border border-dashed">
                                                    <div>
                                                        @if ($lp->persyaratan->tipe === 'file')
                                                            <a href="{{ route('dokumen.view', $existingDoc->id) }}"
                                                                target="_blank" class="text-primary fw-600">
                                                                <i class="fas fa-file-alt me-1"></i> Lihat Dokumen yang
                                                                Diunggah
                                                            </a>
                                                        @else
                                                            <div class="text-dark">
                                                                {{ $existingDoc->text }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if ($existingDoc->status !== 'DRAFT')
                                                        <div>
                                                            @php
                                                                $docStatusClass = match ($existingDoc->status) {
                                                                    'SELESAI' => ['bg-success', 'Selesai'],
                                                                    'PERBAIKAN' => ['bg-danger', 'Perlu Perbaikan'],
                                                                    'BELUM_SELESAI' => [
                                                                        'bg-warning',
                                                                        'Sedang Diperiksa',
                                                                    ],
                                                                    default => ['bg-secondary', 'Unknown'],
                                                                };
                                                            @endphp
                                                            <span
                                                                class="badge {{ $docStatusClass[0] }}">{{ $docStatusClass[1] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                @if ($existingDoc->catatan)
                                                    @if ($existingDoc->status !== 'SELESAI' && $existingDoc->status !== 'BELUM_SELESAI' && $existingDoc->status !== 'DRAFT')
                                                        <div
                                                            class="ms-5 mt-2 p-2 bg-red-soft rounded border border-red small">
                                                            <div class="text-red fw-bold mb-1"><i
                                                                    class="fas fa-comment-dots me-1"></i> Catatan
                                                                Koreksi:
                                                            </div>
                                                            <div class="text-red">{{ $existingDoc->catatan->catatan }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach


                        <hr class="my-4" />

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Dokumen Tambahan (Opsional):</h6>
                            @if (Auth::user()->tipe === 'users')
                                @if (
                                    $pengajuan->status_pengajuan === 'DRAFT' ||
                                        $pengajuan->status_pengajuan === 'PERBAIKAN' ||
                                        $pengajuan->status_pengajuan === 'DITOLAK')
                                    <button class="btn btn-outline-primary btn-sm" @click="openTambahanModal()">
                                        <i class="fas fa-plus me-1"></i> Tambah
                                    </button>
                                @endif
                            @endif
                        </div>

                        <div class="row">
                            @forelse($pengajuan->dokumenTambahan as $dt)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border-light shadow-none bg-light">
                                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center overflow-hidden">
                                                <div class="icon-stack bg-primary text-white me-3 flex-shrink-0">
                                                    <i data-feather="file"></i>
                                                </div>
                                                <div class="text-truncate">
                                                    <div class="fw-bold text-truncate">{{ $dt->nama_dokumen }}</div>
                                                    <a href="{{ route('dokumen-tambahan.view', $dt->id) }}"
                                                        target="_blank" class="small text-primary">Lihat</a>
                                                    @if ($dt->status !== 'DRAFT')
                                                        @php
                                                            $dtStatusClass = match ($existingDoc->status) {
                                                                'SELESAI' => ['bg-success', 'Selesai'],
                                                                'PERBAIKAN' => ['bg-danger', 'Perlu Perbaikan'],
                                                                'BELUM_SELESAI' => ['bg-warning', 'Sedang Diperiksa'],
                                                                default => ['bg-secondary', 'Unknown'],
                                                            };
                                                        @endphp
                                                        <span
                                                            class="badge {{ $dtStatusClass[0] }} ms-1">{{ str_replace('_', ' ', $dtStatusClass[1]) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                @if (Auth::user()->tipe === 'pegawai' &&
                                                        Auth::user()->timKerjas->contains('id_tim_kerja', $pengajuan->layanan->tim_kerja_id) &&
                                                        $pengajuan->status_pengajuan === 'DIPROSES')
                                                    <button class="btn btn-icon btn-transparent-dark btn-sm me-1"
                                                        @click="openReviewTambahanModal('{{ $dt->id }}', '{{ $dt->nama_dokumen }}', '{{ $dt->status }}', {{ json_encode($dt->catatan ? $dt->catatan->catatan : '') }}, '{{ route('dokumen-tambahan.view', $dt->id) }}')"
                                                        title="Periksa">
                                                        <i data-feather="search"></i>
                                                    </button>
                                                @endif
                                                <button
                                                    class="btn btn-icon btn-transparent-dark btn-sm me-1 text-warning"
                                                    @click="openHistoryModal('{{ $dt->nama_dokumen }}', {{ $dt->catatans->toJson() }})"
                                                    title="Riwayat Catatan">
                                                    <i data-feather="clock"></i>
                                                </button>
                                                @if (Auth::user()->tipe === 'users')
                                                    @if (
                                                        ($pengajuan->status_pengajuan === 'DRAFT' ||
                                                            $pengajuan->status_pengajuan === 'PERBAIKAN' ||
                                                            $pengajuan->status_pengajuan === 'DITOLAK') &&
                                                            $dt->status !== 'SELESAI')
                                                        <button class="btn btn-icon btn-transparent-dark btn-sm"
                                                            @click="deleteTambahan({{ $dt->id }})">
                                                            <i data-feather="trash-2"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        @if ($dt->catatan)
                                            <div class="card-footer bg-white py-1 small text-danger border-top-0">
                                                @if ($dt->status !== 'SELESAI' && $dt->status !== 'BELUM_SELESAI' && $dt->status !== 'DRAFT')
                                                    <strong>Catatan:</strong> {{ $dt->catatan->catatan }}
                                                    {{ $dt->status }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-3 text-muted small">
                                    Belum ada dokumen tambahan.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Requirement Modal -->
                    <div class="modal fade" id="modalPersyaratan" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" x-text="modalTitle"></h5>
                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form @submit.prevent="submitRequirement">
                                    <div class="modal-body">
                                        <template x-if="modalType === 'file'">
                                            <div class="mb-3">
                                                <label class="small mb-1">Unggah Dokumen (PDF, JPG, PNG - Max
                                                    5MB)</label>
                                                <input class="form-control" type="file" @change="handleFileChange"
                                                    accept=".pdf,.jpg,.jpeg,.png">
                                            </div>
                                        </template>
                                        <template x-if="modalType === 'text'">
                                            <div class="mb-3">
                                                <label class="small mb-1">Masukkan Keterangan/Text</label>
                                                <textarea class="form-control" rows="4" x-model="modalText" required></textarea>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-primary" type="submit" :disabled="saving">
                                            <span x-show="!saving">Simpan</span>
                                            <span x-show="saving">Menyimpan...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Doc Modal -->
                    <div class="modal fade" id="modalTambahan" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Dokumen Tambahan</h5>
                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form @submit.prevent="submitTambahan">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="small mb-1">Nama Dokumen</label>
                                            <input class="form-control" type="text" x-model="tambahanNama"
                                                placeholder="Contoh: KTP, Ijazah, dll" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small mb-1">File Dokumen</label>
                                            <input class="form-control" type="file"
                                                @change="handleTambahanFileChange" accept=".pdf,.jpg,.jpeg,.png"
                                                required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-primary" type="submit" :disabled="saving">
                                            <span x-show="!saving">Unggah</span>
                                            <span x-show="saving">Mengunggah...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Review Modal -->
                    <div class="modal fade" id="modalReview" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Review: <span x-text="reviewTitle"></span></h5>
                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form @submit.prevent="submitReview">
                                    <div class="modal-body">
                                        <div class="mb-3 border rounded p-3 bg-light">
                                            <label class="small fw-bold mb-2 d-block text-uppercase">Isi Persyaratan /
                                                Dokumen</label>

                                            <!-- If Text -->
                                            <template x-if="reviewType === 'text'">
                                                <div class="p-3 bg-white border rounded shadow-sm"
                                                    style="white-space: pre-wrap; min-height: 100px;"
                                                    x-text="reviewContent"></div>
                                            </template>

                                            <!-- If File -->
                                            <template x-if="reviewType === 'file'">
                                                <div>
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-file-alt text-primary me-2"></i>
                                                            <span class="small fw-bold">Dokumen Terlampir</span>
                                                        </div>
                                                        <a :href="reviewContent" target="_blank"
                                                            class="btn btn-outline-primary btn-xs">
                                                            <i class="fas fa-external-link-alt me-1"></i> Buka di Tab
                                                            Baru
                                                        </a>
                                                    </div>
                                                    <div class="border rounded bg-white" style="height: 400px;">
                                                        <iframe :src="reviewContent"
                                                            class="w-100 h-100 border-0 rounded"></iframe>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label class="small mb-1 fw-bold">Status Verifikasi</label>
                                                    <select class="form-select" x-model="reviewStatus" required>
                                                        <option value="">Pilih Status...</option>
                                                        <option value="PERBAIKAN">Butuh Perbaikan</option>
                                                        <option value="SELESAI">Sesuai / Selesai</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="mb-3">
                                                    <label class="small mb-1 fw-bold">Catatan Koreksi
                                                        (Opsional)</label>
                                                    <textarea class="form-control" rows="4" x-model="reviewCatatan"
                                                        placeholder="Masukkan catatan jika ada perbaikan..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-primary" type="submit" :disabled="saving">
                                            <span x-show="!saving">Simpan Hasil Review</span>
                                            <span x-show="saving">Menyimpan...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- History Modal -->
                    <div class="modal fade" id="modalHistory" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Riwayat Catatan: <span x-text="historyTitle"></span></h5>
                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="timeline timeline-xs">
                                        <template x-for="item in historyData" :key="item.id">
                                            <div class="timeline-item">
                                                <div class="timeline-item-marker">
                                                    <div class="timeline-item-marker-text"
                                                        x-text="formatDate(item.created_at)"></div>
                                                    <div class="timeline-item-marker-indicator bg-warning"></div>
                                                </div>
                                                <div class="timeline-item-content">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="fw-bold text-dark"
                                                            x-text="item.user ? item.user.name : 'Unknown User'"></div>
                                                        <div class="small text-muted"
                                                            x-text="formatTime(item.created_at)"></div>
                                                    </div>
                                                    <div class="mt-1 p-2 bg-light rounded border"
                                                        x-text="item.catatan"></div>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="historyData.length === 0">
                                            <div class="text-center py-4 text-muted">Belum ada riwayat catatan.</div>
                                        </template>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button"
                                        data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('after')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function permohonanDokumen() {
                return {
                    id: '{{ $pengajuan->id }}',
                    status: '{{ $pengajuan->status_pengajuan }}',
                    saving: false,
                    searchQuery: '',
                    filterStatus: 'all',

                    // Requirement Modal State
                    modalLP: '',
                    modalTitle: '',
                    modalType: '',
                    modalText: '',
                    modalFile: null,

                    // Tambahan Modal State
                    tambahanNama: '',
                    tambahanFile: null,

                    // Review State
                    reviewId: '',
                    reviewTitle: '',
                    reviewStatus: 'SELESAI',
                    reviewCatatan: '',
                    reviewIsTambahan: false,
                    reviewType: '',
                    reviewContent: '',

                    // History State
                    historyTitle: '',
                    historyData: [],

                    // Track filled status (passed from PHP)
                    allRequirementsCount: {{ $pengajuan->layanan->layananPersyaratan->count() }},
                    mandatoryRequirements: [
                        @foreach ($pengajuan->layanan->layananPersyaratan as $lp)
                            @if ($lp->persyaratan->wajib)
                                '{{ $lp->id }}',
                            @endif
                        @endforeach
                    ],
                    filledRequirements: [
                        @foreach ($pengajuan->layanan->layananPersyaratan as $lp)
                            @php
                                $existingDoc = $pengajuan->dokumen->where('id_layanan_persyaratan', $lp->id)->first();
                                $isFilled = $existingDoc && ($existingDoc->file || $existingDoc->text);
                            @endphp
                            @if ($isFilled)
                                '{{ $lp->id }}',
                            @endif
                        @endforeach
                    ],
                    verifiedRequirements: [
                        @foreach ($pengajuan->dokumen as $dok)
                            @if (in_array($dok->status, ['SELESAI', 'PERBAIKAN']))
                                '{{ $dok->id_layanan_persyaratan }}',
                            @endif
                        @endforeach
                    ],

                    allRequirementsMet() {
                        if (this.mandatoryRequirements.length === 0) return true;
                        return this.mandatoryRequirements.every(id => this.filledRequirements.includes(id));
                    },

                    openModal(lpId, title, type, existingText) {
                        this.modalLP = lpId;
                        this.modalTitle = title;
                        this.modalType = type;
                        this.modalText = existingText;
                        this.modalFile = null;
                        const modal = new bootstrap.Modal(document.getElementById('modalPersyaratan'));
                        modal.show();
                    },

                    handleFileChange(e) {
                        this.modalFile = e.target.files[0];
                    },

                    submitRequirement() {
                        this.saving = true;
                        const formData = new FormData();
                        formData.append('id_pengajuan', this.id);
                        formData.append('id_layanan_persyaratan', this.modalLP);
                        formData.append('_token', '{{ csrf_token() }}');

                        if (this.modalType === 'file' && this.modalFile) {
                            formData.append('file', this.modalFile);
                        } else if (this.modalType === 'text') {
                            formData.append('text', this.modalText);
                        } else if (this.modalType === 'file' && !this.modalFile) {
                            Swal.fire('Error', 'Harap pilih file.', 'error');
                            this.saving = false;
                            return;
                        }

                        $.ajax({
                            url: "{{ route('dokumen.store') }}",
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: (res) => {
                                Swal.fire('Berhasil', res.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            },
                            error: (xhr) => {
                                const msg = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                                Swal.fire('Error', msg, 'error');
                            },
                            complete: () => {
                                this.saving = false;
                            }
                        });
                    },

                    openTambahanModal() {
                        this.tambahanNama = '';
                        this.tambahanFile = null;
                        const modal = new bootstrap.Modal(document.getElementById('modalTambahan'));
                        modal.show();
                    },

                    handleTambahanFileChange(e) {
                        this.tambahanFile = e.target.files[0];
                    },

                    submitTambahan() {
                        if (!this.tambahanFile) {
                            Swal.fire('Error', 'Harap pilih file.', 'error');
                            return;
                        }
                        this.saving = true;
                        const formData = new FormData();
                        formData.append('id_pengajuan', this.id);
                        formData.append('nama_dokumen', this.tambahanNama);
                        formData.append('file', this.tambahanFile);
                        formData.append('_token', '{{ csrf_token() }}');

                        $.ajax({
                            url: "{{ route('dokumen-tambahan.store') }}",
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: (res) => {
                                Swal.fire('Berhasil', res.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            },
                            error: (xhr) => {
                                const msg = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                                Swal.fire('Error', msg, 'error');
                            },
                            complete: () => {
                                this.saving = false;
                            }
                        });
                    },

                    openHistoryModal(title, history) {
                        this.historyTitle = title;
                        this.historyData = history;
                        const modal = new bootstrap.Modal(document.getElementById('modalHistory'));
                        modal.show();
                    },

                    formatDate(dateString) {
                        const date = new Date(dateString);
                        return date.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short'
                        });
                    },

                    formatTime(dateString) {
                        const date = new Date(dateString);
                        return date.toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    },

                    openReviewModal(docId, title, status, catatan, type, content) {
                        this.reviewId = docId;
                        this.reviewTitle = title;
                        this.reviewStatus = status === 'BELUM_SELESAI' ? 'SELESAI' : status;
                        // this.reviewCatatan = catatan;
                        this.reviewCatatan = '';
                        this.reviewIsTambahan = false;
                        this.reviewType = type;
                        this.reviewContent = content;
                        const modal = new bootstrap.Modal(document.getElementById('modalReview'));
                        modal.show();
                    },

                    openReviewTambahanModal(docId, title, status, catatan, content) {
                        this.reviewId = docId;
                        this.reviewTitle = title;
                        this.reviewStatus = status === 'BELUM_SELESAI' ? 'SELESAI' : status;
                        this.reviewCatatan = catatan;
                        this.reviewIsTambahan = true;
                        this.reviewType = 'file';
                        this.reviewContent = content;
                        const modal = new bootstrap.Modal(document.getElementById('modalReview'));
                        modal.show();
                    },

                    submitReview() {
                        this.saving = true;
                        const url = this.reviewIsTambahan ?
                            `{{ url('dokumen-tambahan') }}/${this.reviewId}/review` :
                            `{{ url('dokumen') }}/${this.reviewId}/review`;

                        $.ajax({
                            url: url,
                            method: "POST",
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: this.reviewStatus,
                                catatan: this.reviewCatatan
                            },
                            success: (res) => {
                                Swal.fire('Berhasil', res.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            },
                            error: (xhr) => {
                                const msg = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                                Swal.fire('Error', msg, 'error');
                            },
                            complete: () => {
                                this.saving = false;
                            }
                        });
                    },

                    updatePengajuanStatus(newStatus) {
                        let confirmText = '';
                        let keterangan = '';
                        let isFinal = newStatus === 'SELESAI';

                        if (newStatus === 'PERBAIKAN') {
                            confirmText =
                                'Permohonan akan dikembalikan ke pemohon untuk diperbaiki. Pastikan semua catatan koreksi sudah diisi.';
                            keterangan = 'Permohonan butuh perbaikan pada berkas.';
                        } else if (newStatus === 'SELESAI_PEMERIKSAAN') {
                            confirmText =
                                'Permohonan akan ditandai Selesai Pemeriksaan. Silahkan lanjutkan ke proses berikutnya.';
                            keterangan = 'Permohonan telah selesai diperiksa.';
                        } else if (newStatus === 'DIPROSES') {
                            confirmText = 'Permohonan akan diproses. Status akan berubah menjadi DIPROSES.';
                            keterangan = 'Permohonan sedang diproses.';
                        } else if (newStatus === 'DITOLAK') {
                            confirmText = 'Permohonan akan ditolak. Status akan berubah menjadi DITOLAK.';
                            keterangan = 'Permohonan ditolak.';
                        } else {
                            confirmText =
                                'Permohonan akan ditandai Selesai. Pastikan semua berkas sudah diverifikasi dan sertakan dokumen hasil.';
                            keterangan = 'Permohonan telah selesai diproses.';
                        }

                        if (isFinal) {
                            Swal.fire({
                                title: 'Selesaikan Permohonan',
                                html: `
                                    <div class="text-start mb-3">
                                        <p class="small mb-2">${confirmText}</p>
                                        <label class="small fw-bold mb-1">Dokumen Hasil (Bisa pilih lebih dari satu)</label>
                                        <input type="file" id="swal-file-hasil" class="form-control" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <div class="mt-3">
                                            <label class="small fw-bold mb-1">Keterangan (Opsional)</label>
                                            <textarea id="swal-keterangan" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                `,
                                icon: 'info',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Selesaikan',
                                cancelButtonText: 'Batal',
                                preConfirm: () => {
                                    const files = document.getElementById('swal-file-hasil').files;
                                    const keterangan = document.getElementById('swal-keterangan').value;
                                    if (files.length === 0) {
                                        Swal.showValidationMessage('Harap pilih minimal satu dokumen hasil.');
                                        return false;
                                    }
                                    return {
                                        files: files,
                                        keterangan: keterangan
                                    };
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const formData = new FormData();
                                    formData.append('_token', '{{ csrf_token() }}');
                                    formData.append('status', newStatus);
                                    formData.append('keterangan', result.value.keterangan || keterangan);

                                    for (let i = 0; i < result.value.files.length; i++) {
                                        formData.append('file_hasil[]', result.value.files[i]);
                                    }

                                    this.sendUpdateStatus(formData);
                                }
                            });
                        } else {
                            Swal.fire({
                                title: `Ubah Status ke ${newStatus.replace('_', ' ')}?`,
                                text: confirmText,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Ubah',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const formData = new FormData();
                                    formData.append('_token', '{{ csrf_token() }}');
                                    formData.append('status', newStatus);
                                    formData.append('keterangan', keterangan);

                                    this.sendUpdateStatus(formData);
                                }
                            });
                        }
                    },

                    sendUpdateStatus(formData) {
                        $.ajax({
                            url: "{{ route('permohonan.update-status', $pengajuan->id) }}",
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: (res) => {
                                Swal.fire('Berhasil', res.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            },
                            error: (xhr) => {
                                const msg = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                                Swal.fire('Error', msg, 'error');
                            }
                        });
                    },

                    deleteTambahan(id) {
                        Swal.fire({
                            title: 'Hapus Dokumen?',
                            text: "Tindakan ini tidak dapat dibatalkan.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Hapus',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `{{ url('dokumen-tambahan') }}/${id}`,
                                    method: "DELETE",
                                    data: {
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: (res) => {
                                        Swal.fire('Berhasil', res.message, 'success').then(() => {
                                            window.location.reload();
                                        });
                                    }
                                });
                            }
                        });
                    }
                }
            }

            function confirmDelete() {
                Swal.fire({
                    title: 'Hapus Permohonan?',
                    text: "Semua data dan berkas yang telah diunggah akan dihapus permanen.",
                    icon: 'danger',
                    showCancelButton: true,
                    confirmButtonColor: '#e81500',
                    confirmButtonText: 'Ya, Hapus Permanen',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm').submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
