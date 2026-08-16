<x-landing-layout>
    <header class="py-10 bg-gradient-primary-to-secondary text-white">
        <div class="container px-5 text-center">
            <h1 class="display-4 fw-bold mb-2 text-white">Lacak Permohonan</h1>
            <p class="lead mb-0 text-white">Masukkan nomor tiket Anda untuk melihat status terkini dari permohonan Anda.
            </p>
        </div>
    </header>

    <section class="py-10 bg-light">
        <div class="container px-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-lg mb-5">
                        <div class="card-body p-5">
                            <form action="{{ route('lacak.proses') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="small mb-1" for="nomor_tiket">Nomor Tiket</label>
                                    <input class="form-control form-control-solid py-4 @error('nomor_tiket') is-invalid @enderror"
                                        id="nomor_tiket" name="nomor_tiket" type="text"
                                        placeholder="Masukkan Nomor Tiket (Contoh: TKT-20260420-XXXX)"
                                        value="{{ $nomor_tiket ?? '' }}" required />
                                    @error('nomor_tiket')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- CAPTCHA -->
                                <div class="mb-4">
                                    <label class="small mb-1" for="captcha">Captcha</label>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <img src="{{ route('captcha13.image') }}" alt="captcha" class="rounded border"
                                            id="captcha-img">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="document.getElementById('captcha-img').src = '{{ route('captcha13.image') }}?' + Math.random();">
                                            <i data-feather="refresh-cw"></i>
                                        </button>
                                    </div>
                                    <input class="form-control @error('captcha') is-invalid @enderror" id="captcha"
                                        type="text" name="captcha" required placeholder="Masukkan kode captcha" />
                                    @error('captcha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button class="btn btn-primary btn-block py-3 w-100" type="submit">Cari
                                    Tiket</button>
                            </form>
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger border-0 shadow-sm mb-5" role="alert">
                            <div class="d-flex align-items-center">
                                <i data-feather="alert-circle" class="me-2"></i>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    @if ($pengajuan)
                        <div class="card shadow-sm border-0 rounded-lg overflow-hidden mb-5">
                            <div class="card-header bg-white py-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold text-primary">Hasil Pencarian</h5>
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
                                        class="badge {{ $statusClass }} py-2 px-3">{{ str_replace('_', ' ', $pengajuan->status_pengajuan) }}</span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="small text-muted text-uppercase fw-700 mb-1">Nomor Tiket</div>
                                        <div class="h5 fw-bold mb-0">{{ $pengajuan->nomor_tiket }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted text-uppercase fw-700 mb-1">Jenis Layanan</div>
                                        <div class="h5 fw-bold mb-0">{{ $pengajuan->layanan->nama_layanan }}</div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="small text-muted text-uppercase fw-700 mb-1">Tanggal Pengajuan</div>
                                        <div class="h5 mb-0">
                                            {{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->translatedFormat('d F Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted text-uppercase fw-700 mb-1">Update Terakhir</div>
                                        <div class="h5 mb-0">
                                            {{ $pengajuan->updated_at->translatedFormat('d F Y, H:i') }}</div>
                                    </div>
                                </div>

                                <hr class="my-4" />

                                <h6 class="text-uppercase-expanded text-xs mb-4">Riwayat Perjalanan Tiket</h6>
                                <div class="timeline timeline-xs">
                                    @forelse($pengajuan->riwayat as $log)
                                        <div class="timeline-item">
                                            <div class="timeline-item-marker">
                                                <div class="timeline-item-marker-text">
                                                    {{ \Carbon\Carbon::parse($log->tanggal_disposisi)->translatedFormat('d M') }}
                                                </div>
                                                @php
                                                    $markerClass = match ($log->status) {
                                                        'DRAFT' => 'bg-secondary',
                                                        'MENUNGGU_DIPROSES' => 'bg-warning',
                                                        'DIPROSES' => 'bg-info',
                                                        'DITOLAK' => 'bg-danger',
                                                        'PERBAIKAN' => 'bg-dark',
                                                        'SELESAI' => 'bg-success',
                                                        default => 'bg-primary',
                                                    };
                                                @endphp
                                                <div class="timeline-item-marker-indicator {{ $markerClass }}"></div>
                                            </div>
                                            <div class="timeline-item-content">
                                                <div class="fw-bold text-dark">
                                                    {{ str_replace('_', ' ', $log->status) }}</div>
                                                <p class="text-gray-600 mb-0">{{ $log->keterangan }}</p>
                                                <div class="small text-muted">
                                                    {{ \Carbon\Carbon::parse($log->tanggal_disposisi)->translatedFormat('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-muted">Belum ada riwayat pergerakan tiket.
                                        </div>
                                    @endforelse
                                </div>

                                @if ($pengajuan->status_pengajuan === 'SELESAI' && $pengajuan->dokumenHasil->count() > 0)
                                    <div class="mt-5 p-4 bg-green-soft rounded-lg border border-success">
                                        <h6 class="text-success fw-bold mb-3"><i data-feather="check-circle"
                                                class="me-2"></i> Dokumen Hasil Selesai</h6>
                                        <p class="small text-gray-700 mb-3">Permohonan Anda telah selesai diproses. Anda
                                            dapat mengunduh dokumen hasil melalui dashboard setelah melakukan login.</p>
                                        <a href="{{ route('login') }}" class="btn btn-success btn-sm px-4">Login untuk
                                            Unduh Hasil</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-10 bg-white" id="faq">
        <div class="container px-5">
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h2 class="display-5 fw-bold text-dark">Bantuan & FAQ</h2>
                        <p class="lead text-gray-500">Punya kendala saat melacak tiket? Simak informasi berikut.</p>
                    </div>
                    <div class="accordion accordion-flush shadow-sm rounded border" id="accordionLacak">
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="h1">
                                <button class="accordion-button fw-bold py-4" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#c1">
                                    Dimana saya bisa mendapatkan Nomor Tiket?
                                </button>
                            </h3>
                            <div id="c1" class="accordion-collapse collapse show"
                                data-bs-parent="#accordionLacak">
                                <div class="accordion-body text-gray-600 pb-4">
                                    Nomor tiket didapatkan sesaat setelah Anda berhasil membuat permohonan baru di
                                    dashboard. Nomor tiket juga biasanya dikirimkan melalui email notifikasi (jika
                                    dikonfigurasi).
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="h2">
                                <button class="accordion-button collapsed fw-bold py-4" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#c2">
                                    Tiket saya tidak ditemukan, apa yang harus saya lakukan?
                                </button>
                            </h3>
                            <div id="c2" class="accordion-collapse collapse" data-bs-parent="#accordionLacak">
                                <div class="accordion-body text-gray-600 pb-4">
                                    Pastikan format nomor tiket sudah benar. Jika masih tidak ditemukan, kemungkinan
                                    tiket telah dihapus atau terjadi kesalahan sistem. Silahkan hubungi admin melalui
                                    kontak yang tersedia.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-landing-layout>
