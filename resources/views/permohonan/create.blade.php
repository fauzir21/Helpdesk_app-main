<x-app-layout>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file-plus"></i></div>
                            Ajukan Permohonan: {{ $layanan->nama_layanan }}
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('permohonan.index') }}">
                            <i class="me-1" data-feather="arrow-left"></i>
                            Kembali ke Daftar Layanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        <div class="row">
            <div class="col-xl-8">
                <!-- Service Details Card-->
                <div class="card mb-4">
                    <div class="card-header">Detail Layanan</div>
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $layanan->nama_layanan }}</h5>
                        <p class="card-text text-muted">{{ $layanan->deskripsi }}</p>

                        <hr class="my-4" />

                        <h6 class="mb-3">Persyaratan yang Harus Dipenuhi:</h6>
                        @if ($groupedPersyaratan->count() > 0)
                            @foreach ($groupedPersyaratan as $kategori => $persyaratans)
                                <div class="mb-4">
                                    <div class="small fw-bold text-uppercase text-primary mb-2 border-bottom pb-1">
                                        {{ $kategori }}
                                    </div>
                                    <div class="list-group list-group-flush">
                                        @foreach ($persyaratans as $lp)
                                            <div class="list-group-item d-flex align-items-start px-0 border-0 mb-1">
                                                <div class="icon-stack bg-primary-soft text-primary me-3 flex-shrink-0">
                                                    <i data-feather="check"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-500">{{ $lp->persyaratan->nama_persyaratan }}</div>
                                                    @if ($lp->persyaratan->wajib == 1)
                                                        <span class="badge bg-red-soft text-red ms-1"
                                                            style="font-size: 0.65rem">Wajib</span>
                                                    @else
                                                        <span class="badge bg-gray-200 text-gray-600 ms-1"
                                                            style="font-size: 0.65rem">Opsional</span>
                                                    @endif
                                                    @if ($lp->persyaratan->deskripsi)
                                                        <div class="small text-muted">{{ $lp->persyaratan->deskripsi }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                Tidak ada persyaratan khusus untuk layanan ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <!-- Action Card-->
                <div class="card mb-4 shadow-sm border-start-lg border-start-primary">
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3">Siap Mengajukan?</h5>
                        <p class="small text-muted mb-4">
                            Dengan menekan tombol di bawah ini, Anda akan membuat permohonan baru untuk layanan
                            <strong>{{ $layanan->nama_layanan }}</strong>.
                        </p>
                        <form action="{{ route('permohonan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_layanan" value="{{ $layanan->id }}">

                            @if ($layanan->input_tambahan && count($layanan->input_tambahan) > 0)
                                <div class="bg-light p-3 rounded mb-4 border shadow-none">
                                    <h6 class="text-primary mb-3"><i data-feather="edit-3" class="me-2"
                                            style="width: 14px"></i> Detail Informasi Tambahan</h6>
                                    @foreach ($layanan->input_tambahan as $field)
                                        @php
                                            $fieldSlug = \Illuminate\Support\Str::slug($field['label'], '_');
                                            $fieldName = "detail_tambahan[{$fieldSlug}]";
                                        @endphp
                                        <div class="mb-3">
                                            <label class="small mb-1 fw-500">
                                                {{ $field['label'] }}
                                                @if ($field['required'] ?? false)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>

                                            @if ($field['type'] === 'textarea')
                                                <textarea name="{{ $fieldName }}" class="form-control @error($fieldName) is-invalid @enderror" rows="3"
                                                    placeholder="Masukkan {{ $field['label'] }}" {{ ($field['required'] ?? false) ? 'required' : '' }}>{{ old($fieldName) }}</textarea>
                                            @else
                                                <input type="{{ $field['type'] }}" name="{{ $fieldName }}"
                                                    class="form-control @error($fieldName) is-invalid @enderror"
                                                    value="{{ old($fieldName) }}"
                                                    placeholder="Masukkan {{ $field['label'] }}"
                                                    {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                            @endif

                                            @error($fieldName)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <button class="btn btn-primary w-100 py-3 shadow-sm" type="submit">
                                <i class="me-2" data-feather="send"></i>
                                Buat Permohonan Sekarang
                            </button>
                        </form>
                    </div>
                    <div class="card-footer bg-light small text-muted">
                        Nomor tiket akan digenerate otomatis setelah Anda menekan tombol di atas.
                    </div>
                </div>

                <!-- Tips Card-->
                <div class="card bg-primary-soft border-0">
                    <div class="card-body">
                        <h6 class="text-primary mb-2">Tips Pengajuan</h6>
                        <p class="small text-primary-dark mb-0">
                            Pastikan Anda telah menyiapkan semua dokumen persyaratan yang disebutkan di samping sebelum
                            melanjutkan ke tahap pengunggahan berkas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
