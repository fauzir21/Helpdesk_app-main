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
                <!-- Log Card-->
                <div class="card mb-4">
                    <div class="card-header">Riwayat Status</div>
                    <div class="card-body">
                        <div class="timeline timeline-xs">
                            @forelse($timeline as $log)
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">
                                        <div class="timeline-item-marker-text">
                                            {{ \Carbon\Carbon::parse($log['tanggal'])->translatedFormat('d M') }}
                                        </div>
                                        @php
                                            $markerClass = match ($log['status']) {
                                                'DRAFT' => 'bg-secondary',
                                                'MENUNGGU_DIPROSES' => 'bg-warning',
                                                'DIPROSES' => 'bg-info',
                                                'DITOLAK' => 'bg-danger',
                                                'PERBAIKAN' => 'bg-dark',
                                                'SELESAI' => 'bg-success',
                                                'UPDATE_DOKUMEN' => 'bg-primary',
                                                default => 'bg-primary',
                                            };
                                        @endphp
                                        <div class="timeline-item-marker-indicator {{ $markerClass }}"></div>
                                    </div>
                                    <div class="timeline-item-content">
                                        <div class="fw-bold text-dark">
                                            {{ $log['status'] === 'UPDATE_DOKUMEN' ? 'Upload Berkas' : str_replace('_', ' ', $log['status']) }}
                                        </div>
                                        {{ $log['keterangan'] }}
                                        <div class="small text-muted d-flex justify-content-between">
                                            <span>Oleh: {{ $log['user'] }}</span>
                                            <span>{{ \Carbon\Carbon::parse($log['tanggal'])->translatedFormat('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center small text-muted">Belum ada riwayat.</div>
                            @endforelse
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
                        this.reviewCatatan = catatan;
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
                        console.log('hai');

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
