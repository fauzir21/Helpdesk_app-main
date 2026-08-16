<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pemenuhan Persyaratan - {{ $pengajuan->nomor_tiket }}</title>
    <style>
        @page {
            margin: 1cm 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat Styles */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            margin-bottom: 20px;
        }

        .kop-logo {
            width: 80px;
            vertical-align: middle;
            text-align: left;
            padding-bottom: 10px;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 80px; /* Counter-balance for logo width to keep text centered */
            padding-bottom: 10px;
        }

        .kop-text h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 1.2;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .kop-text p {
            margin: 2px 0 0 0;
            font-size: 10px;
            line-height: 1.1;
        }

        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title h3 {
            margin: 0;
            text-transform: uppercase;
            text-decoration: underline;
            font-size: 14px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-table td.label {
            width: 140px;
            font-weight: bold;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .category-row {
            background-color: #e9e9e9;
            font-weight: bold;
        }

        .status-selesai {
            color: green;
            font-weight: bold;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            width: 300px;
            float: right;
        }

        .signature-space {
            height: 70px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('assets/img/logo_kotabogor.png') }}" style="width: 75px; height: auto;">
            </td>
            <td class="kop-text">
                <h1>Pemerintah Kota Bogor</h1>
                <h2>Dinas Komunikasi dan Informatika</h2>
                <p>Jl. Ir. H. Juanda No. 10, Kota Bogor, Jawa Barat 16121</p>
                <p>Telepon: (0251) 8321075 | Email: diskominfo@kotabogor.go.id</p>
                <p>Website: www.kotabogor.go.id</p>
            </td>
        </tr>
    </table>

    <div class="report-title">
        <h3>Laporan Pemenuhan Persyaratan</h3>
        <div style="font-size: 11px; margin-top: 5px;">Nomor Tiket: {{ $pengajuan->nomor_tiket }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Pemohon</td>
            <td>: {{ $pengajuan->user->name }}</td>
        </tr>
        <tr>
            <td class="label">Layanan</td>
            <td>: {{ $pengajuan->layanan->nama_layanan }}</td>
        </tr>
        <tr>
            <td class="label">Tim Kerja</td>
            <td>: {{ $pengajuan->layanan->timKerja->nama_timkerja }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pengajuan</td>
            <td>: {{ $pengajuan->tanggal_pengajuan->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Selesai</td>
            <td>: {{ $pengajuan->tanggal_selesai ? $pengajuan->tanggal_selesai->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        @foreach ($pengajuan->layanan->input_tambahan as $field)
            @php
                $fieldSlug = \Illuminate\Support\Str::slug($field['label'], '_');
                $value = $pengajuan->detail_tambahan[$fieldSlug] ?? '-';
            @endphp
            <tr>
                <td class="label">{{ $field['label'] }}</td>
                <td>: {{ $value }}</td>
            </tr>
        @endforeach
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 40%;">Kategori & Persyaratan</th>
                <th style="width: 15%;">Pemenuhan</th>
                <th style="width: 45%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedPersyaratan as $kategori => $persyaratans)
                <tr class="category-row">
                    <td colspan="3">{{ $kategori }}</td>
                </tr>
                @foreach ($persyaratans as $lp)
                    @php
                        $dokumen = $pengajuan->dokumen->where('id_layanan_persyaratan', $lp->id)->first();
                        $status = $dokumen
                            ? ($dokumen->status === 'SELESAI'
                                ? 'Selesai'
                                : str_replace('_', ' ', $dokumen->status))
                            : 'Belum Ada';

                        $catatanList = [];
                        if ($dokumen) {
                            foreach ($dokumen->catatans as $c) {
                                $timestamp = $c->created_at->translatedFormat('d/m/Y H:i');
                                $catatanList[] = "<strong>[{$timestamp}]</strong> {$c->user->name}: {$c->catatan}";
                            }
                        }
                    @endphp
                    <tr>
                        <td style="padding-left: 20px;">- {{ $lp->persyaratan->nama_persyaratan }}</td>
                        <td class="{{ $status === 'Selesai' ? 'status-selesai' : '' }}">{{ $status }}</td>
                        <td style="font-size: 10px;">
                            @if (count($catatanList) > 0)
                                {!! implode('<br>', $catatanList) !!}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="clear"></div>
    
    <div class="footer">
        <div>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</div>
        <div class="signature-space"></div>
        <div><strong>{{ $pengajuan->layanan->timKerja->ketua->name }}</strong></div>
        <div>Ketua Tim {{ $pengajuan->layanan->timKerja->nama_timkerja }}</div>
    </div>
</body>

</html>
