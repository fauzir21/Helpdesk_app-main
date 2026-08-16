<x-landing-layout>
    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container px-5">
            <div class="row gx-5 align-items-center">
                <div class="col-lg-6">
                    <div class="mb-5 mb-lg-0 text-center text-lg-start">
                        <h1 class="display-3 lh-tight mb-3 fw-bold text-primary">Solusi Bantuan TIK Terpadu</h1>
                        <p class="lead fw-normal text-gray-500 mb-5">Kami hadir untuk memberikan kemudahan dalam
                            pengajuan layanan Teknologi Informasi dan Komunikasi. Mulai dari {{ $sampleLayananText }}.</p>
                        <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start">
                            <a class="btn btn-primary btn-lg px-4 me-sm-3 mb-3 mb-sm-0 fw-500 shadow"
                                href="{{ route('login') }}">Mulai Sekarang</a>
                            <a class="btn btn-primary-soft text-primary btn-lg px-4 fw-500" href="#layanan">Lihat
                                Layanan</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid" src="{{ asset('assets/img/illustrations/processing.svg') }}"
                        alt="Layanan Illustration" />
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Section -->
    <section class="bg-white py-10">
        <div class="container px-5">
            <div class="row gx-5 text-center">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="display-4 fw-bold text-primary mb-2">{{ $tiketSelesai }}+</div>
                    <div class="text-uppercase-expanded small fw-700 text-gray-500">Tiket Selesai</div>
                </div>
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="display-4 fw-bold text-primary mb-2">24/7</div>
                    <div class="text-uppercase-expanded small fw-700 text-gray-500">Dukungan Sistem</div>
                </div>
                <div class="col-lg-4">
                    <div class="display-4 fw-bold text-primary mb-2">{{ $kepuasanLayanan }}%</div>
                    <div class="text-uppercase-expanded small fw-700 text-gray-500">Kepuasan Layanan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-10 bg-light" id="layanan">
        <div class="container px-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark">Layanan</h2>
                <p class="lead text-gray-500">Tersedia {{ $totalLayanan }} kategori layanan yang dapat Anda akses secara
                    mudah dan cepat.
                </p>
            </div>
            <div class="row gx-5">
                @foreach ($layanan as $item)
                    <div class="col-lg-4 mb-5">
                        <div class="card h-100 border-0 shadow hover-lift transition-all">
                            <div class="card-body p-5">
                                <div class="icon-stack bg-blue-soft text-blue mb-4"><i data-feather="file-text"></i>
                                </div>
                                <h3 class="fw-bold text-dark">{{ $item->nama_layanan }}</h3>
                                <p class="text-gray-500 mb-4">{{ Str::limit($item->deskripsi, 100) }}</p>
                                <a class="btn btn-primary-soft text-primary fw-500"
                                    href="{{ route('layanan.detail', $item->slug) }}">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-2">
                <a class="btn btn-primary btn-lg px-5 fw-bold rounded-pill shadow"
                    href="{{ route('layanan.semua') }}">Lihat Semua Layanan <i class="ms-1"
                        data-feather="arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-10 bg-white" id="faq">
        <div class="container px-5">
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h2 class="display-5 fw-bold text-dark">Pertanyaan Umum (FAQ)</h2>
                        <p class="lead text-gray-500">Informasi tambahan untuk membantu Anda memahami prosedur layanan
                            kami.</p>
                    </div>
                    <div class="accordion accordion-flush shadow rounded" id="accordionFAQ">
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingOne">
                                <button class="accordion-button fw-bold py-4" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Bagaimana cara mengajukan tiket bantuan?
                                </button>
                            </h3>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-gray-600 pb-4">
                                    Anda cukup melakukan pendaftaran akun, masuk ke dashboard, dan pilih menu "Tambah
                                    Tiket". Pilih kategori layanan yang sesuai dan isi formulir yang tersedia.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed fw-bold py-4" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    Berapa lama waktu proses pengerjaan tiket?
                                </button>
                            </h3>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-gray-600 pb-4">
                                    Waktu pengerjaan bervariasi tergantung pada tingkat kompleksitas layanan, umumnya
                                    diproses dalam waktu 1-3 hari kerja.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed fw-bold py-4" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                    Apakah saya bisa memantau status tiket saya?
                                </button>
                            </h3>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-gray-600 pb-4">
                                    Ya, Anda dapat melihat status terkini dari tiket Anda melalui menu "Data Pengajuan"
                                    di dashboard akun Anda.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-10 bg-gradient-primary-to-secondary text-white">
        <div class="container px-5 text-center">
            <h2 class="display-4 fw-bold mb-4 text-white">Siap untuk Memulai?</h2>
            <p class="lead mb-5">Daftarkan akun Anda sekarang dan nikmati kemudahan layanan TIK terpadu dalam satu
                genggaman.</p>
            <a class="btn btn-white btn-lg px-5 fw-bold text-primary rounded-pill shadow"
                href="{{ route('register') }}">Daftar Sekarang</a>
        </div>
    </section>
</x-landing-layout>
