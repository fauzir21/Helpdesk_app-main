<x-app-layout>
    <div class="container-xl px-4 mt-4" x-data="permohonanLayanan()" x-init="fetchLayanans()">
        <!-- Knowledge base home header option-->
        <header class="card card-waves">
            <div class="card-body px-5 pt-5 pb-0">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-6">
                        <h1 class="text-primary">Permohonan Layanan</h1>
                        <p class="lead mb-4">Silahkan pilih layanan yang ingin diajukan!</p>
                        <div class="mb-4">
                            <a href="{{ route('permohonan.list') }}" class="btn btn-primary-soft text-primary">
                                <i class="me-1" data-feather="list"></i>
                                Lihat Status Permohonan Saya
                            </a>
                        </div>
                        <div class="shadow rounded">
                            <div class="input-group input-group-joined input-group-joined-xl border-0">
                                <input class="form-control me-0" type="text" placeholder="Cari Layanan..."
                                    aria-label="Search" autofocus x-model="search"
                                    @input.debounce.500ms="fetchLayanans()" />
                                <span class="input-group-text"><i data-feather="search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4"><img class="img-fluid" src="assets/img/illustrations/problem-solving.svg" />
                    </div>
                </div>
            </div>
        </header>

        <h4 class="mb-0 mt-5">Layanan</h4>
        <hr class="mt-2 mb-4" />

        <!-- Loading state -->
        <template x-if="loading">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat layanan...</p>
            </div>
        </template>

        <!-- No data state -->
        <template x-if="!loading && layanans.length === 0">
            <div class="alert alert-info py-5 text-center">
                Layanan tidak ditemukan.
            </div>
        </template>

        <div class="row" x-show="!loading">
            <template x-for="layanan in layanans" :key="layanan.id">
                <div class="col-lg-4 mb-4">
                    <!-- Knowledge base category card-->
                    <a class="card lift lift-sm h-100" :href="'{{ route('permohonan.create') }}?layanan=' + layanan.slug">

                        <div class="card-body py-5">
                            <h5 class="card-title text-primary mb-2">
                                <i class="me-2" data-feather="file-text"></i>
                                <span x-text="layanan.nama_layanan"></span>
                            </h5>
                            <p class="card-text" x-text="layanan.deskripsi"></p>
                        </div>
                        <div class="card-footer">
                            <div class="small text-muted"
                                x-text="'Jumlah Persyaratan: ' + (layanan.persyaratan_count || 0)"></div>
                        </div>
                    </a>
                </div>
            </template>
        </div>
    </div>

    @push('after')
        <script>
            function permohonanLayanan() {
                return {
                    search: '',
                    layanans: [],
                    loading: false,
                    fetchLayanans() {
                        this.loading = true;
                        fetch(`{{ route('permohonan.layanan') }}?search=${this.search}`)
                            .then(res => res.json())
                            .then(data => {
                                this.layanans = data;
                                this.loading = false;
                                this.$nextTick(() => {
                                    feather.replace();
                                });
                            });
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
