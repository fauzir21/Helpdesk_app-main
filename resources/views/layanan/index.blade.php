<x-landing-layout>
    <x-slot name="title">Semua Layanan - {{ config('app.name') }}</x-slot>

    <header class="page-header-ui page-header-ui-dark bg-gradient-primary-to-secondary py-10">
        <div class="container px-5">
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-4 text-white fw-bold mb-2">Daftar Seluruh Layanan</h1>
                    <p class="lead text-white-50">Temukan layanan yang Anda butuhkan dan lihat persyaratan yang diperlukan.</p>
                </div>
            </div>
        </div>
    </header>

    <section class="py-10 bg-light">
        <div class="container px-5">
            <div class="row gx-5">
                @forelse($layanan as $item)
                <div class="col-lg-4 mb-5">
                    <div class="card h-100 border-0 shadow hover-lift transition-all">
                        <div class="card-body p-5">
                            <div class="icon-stack bg-primary-soft text-primary mb-4"><i data-feather="file-text"></i></div>
                            <h3 class="fw-bold text-dark">{{ $item->nama_layanan }}</h3>
                            <p class="text-gray-500 mb-4">{{ Str::limit($item->deskripsi, 100) }}</p>
                            <a class="btn btn-primary-soft text-primary fw-500" href="{{ route('layanan.detail', $item->slug) }}">Lihat Persyaratan <i class="ms-1" data-feather="arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-gray-500">Saat ini belum ada layanan yang tersedia.</p>
                </div>
                @endforelse
            </div>
            
            <div class="d-flex justify-content-center mt-5">
                {{ $layanan->links() }}
            </div>
        </div>
    </section>
</x-landing-layout>
