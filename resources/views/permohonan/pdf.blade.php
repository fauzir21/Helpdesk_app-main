<!DOCTYPE html>
<html>
<head>
    <title>Rekap Pengajuan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .filter-info {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Pengajuan Layanan</h2>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }}</p>
    </div>

    <div class="filter-info">
        @if($status) <p>Status: {{ str_replace('_', ' ', $status) }}</p> @endif
        @if($date) <p>Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p> @endif
        @if($month) <p>Bulan: {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</p> @endif
        @if($quarter) <p>Triwulan: {{ $quarter }} ({{ $year }})</p> @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nomor Tiket</th>
                <th>Layanan</th>
                <th>Pemohon</th>
                <th>Tanggal Pengajuan</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nomor_tiket }}</td>
                <td>{{ $item->layanan->nama_layanan }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                <td>{{ $item->deadline ? \Carbon\Carbon::parse($item->deadline)->translatedFormat('d F Y') : '-' }}</td>
                <td>{{ str_replace('_', ' ', $item->status_pengajuan) }}</td>
                <td>{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
