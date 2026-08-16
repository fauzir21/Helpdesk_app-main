<x-landing-layout>
    <x-slot name="title">{{ $layanan->nama_layanan }} - {{ config('app.name') }}</x-slot>

    <header class="page-header-ui page-header-ui-dark bg-gradient-primary-to-secondary py-10">
        <div class="container px-5">
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-4 text-white fw-bold mb-2">{{ $layanan->nama_layanan }}</h1>
                    <p class="lead text-white-50">Silakan lengkapi persyaratan berikut untuk mengajukan permohonan layanan ini.</p>
                </div>
            </div>
        </div>
    </header>

    <section class="py-10 bg-light">
        <div class="container px-5">
            <div class="row gx-5">
                <div class="col-lg-8">
                    <!-- Deskripsi Layanan -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="m-0 fw-bold text-primary">Deskripsi Layanan</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-gray-700">{{ $layanan->deskripsi }}</p>
                        </div>
                    </div>

                    <!-- Daftar Persyaratan -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="m-0 fw-bold text-primary">Daftar Persyaratan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($layanan->persyaratan as $p)
                                <div class="list-group-item py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-stack bg-blue-soft text-blue me-3">
                                            @if($p->tipe == 'file')
                                                <i data-feather="upload-cloud"></i>
                                            @else
                                                <i data-feather="edit-2"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold">
                                                {{ $p->nama_persyaratan }}
                                                @if ($p->wajib)
                                                    <span class="badge bg-red-soft text-red ms-1"
                                                        style="font-size: 0.65rem">Wajib</span>
                                                @else
                                                    <span class="badge bg-gray-200 text-gray-600 ms-1"
                                                        style="font-size: 0.65rem">Opsional</span>
                                                @endif
                                            </div>
                                            <div class="small text-gray-500">Tipe Input: {{ ucfirst($p->tipe) }}</div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="p-5 text-center text-gray-500">
                                    Belum ada persyaratan khusus untuk layanan ini.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Card Aksi -->
                    <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                        <div class="card-body p-5">
                            <h5 class="fw-bold mb-4">Ingin Mengajukan?</h5>
                            <p class="text-gray-600 mb-4">Silakan masuk ke akun Anda untuk mulai mengisi formulir pengajuan layanan ini.</p>
                            <a class="btn btn-primary w-100 mb-3" href="{{ route('login') }}">Masuk Sekarang</a>
                            <p class="small text-muted text-center mb-0">Atau <a href="{{ route('register') }}">Daftar Akun Baru</a></p>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a class="text-arrow-icon" href="{{ route('layanan.semua') }}">
                            <i class="me-1" data-feather="arrow-left"></i>
                            Kembali ke Daftar Layanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-landing-layout>
