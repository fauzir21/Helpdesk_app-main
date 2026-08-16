<x-app-layout>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="heart"></i></div>
                            Survei Kepuasan Masyarakat (SKM)
                        </h1>
                        <div class="page-header-subtitle">Silahkan berikan penilaian Anda terhadap layanan yang telah kami berikan.</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">
                Kuesioner Kepuasan - Tiket: {{ $pengajuan->nomor_tiket }}
            </div>
            <form action="{{ route('permohonan.survei.store', $pengajuan->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        Penilaian Anda sangat berharga bagi kami untuk meningkatkan kualitas layanan di masa mendatang.
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50%">Pertanyaan</th>
                                    <th>Penilaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $question)
                                <tr>
                                    <td class="align-middle fw-bold">{{ $question->nama_survei }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-3">
                                            @for($i = 1; $i <= 6; $i++)
                                            @php
                                                $label = match($i) {
                                                    1 => 'Sangat Tidak Baik',
                                                    2 => 'Tidak Baik',
                                                    3 => 'Kurang Baik',
                                                    4 => 'Cukup Baik',
                                                    5 => 'Baik',
                                                    6 => 'Sangat Baik',
                                                };
                                                $color = match($i) {
                                                    1 => 'text-danger',
                                                    2 => 'text-warning',
                                                    3 => 'text-info',
                                                    4 => 'text-primary',
                                                    5 => 'text-success',
                                                    6 => 'text-success fw-bold',
                                                };
                                            @endphp
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="nilai[{{ $question->id }}]" id="q{{ $question->id }}_{{ $i }}" value="{{ $i }}" {{ old('nilai.'.$question->id) == $i ? 'checked' : '' }} required>
                                                <label class="form-check-label small {{ $color }}" for="q{{ $question->id }}_{{ $i }}">
                                                    {{ $i }} ({{ $label }})
                                                </label>
                                            </div>
                                            @endfor
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary px-5" type="submit">Kirim Penilaian</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
