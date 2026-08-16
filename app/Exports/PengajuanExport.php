<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengajuanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tim_kerja;

    protected $status;

    protected $date;

    protected $month;

    protected $quarter;

    protected $year;

    public function __construct($status = null, $date = null, $month = null, $quarter = null, $year = null, $tim_kerja = null)
    {
        $this->status = $status;
        $this->date = $date;
        $this->month = $month;
        $this->quarter = $quarter;
        $this->year = $year ?: date('Y');
        $this->tim_kerja = $tim_kerja;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $query = Pengajuan::with(['layanan', 'user']);

        if ($this->tim_kerja) {
            $query->whereHas('layanan', function ($q) {
                $q->where('tim_kerja_id', $this->tim_kerja);
            });
        }

        if ($this->status) {
            $query->where('status_pengajuan', $this->status);
        }

        if ($this->date) {
            $query->whereDate('tanggal_pengajuan', $this->date);
        }

        if ($this->month) {
            $query->whereMonth('tanggal_pengajuan', $this->month)
                ->whereYear('tanggal_pengajuan', $this->year);
        }

        if ($this->quarter) {
            $months = match ((int) $this->quarter) {
                1 => [1, 2, 3],
                2 => [4, 5, 6],
                3 => [7, 8, 9],
                4 => [10, 11, 12],
                default => []
            };
            $query->whereIn(\DB::raw('MONTH(tanggal_pengajuan)'), $months)
                ->whereYear('tanggal_pengajuan', $this->year);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Tiket',
            'Layanan',
            'Pemohon',
            'Tanggal Pengajuan',
            'Deadline',
            'Status',
            'Tanggal Selesai',
        ];
    }

    public function map($pengajuan): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $pengajuan->nomor_tiket,
            $pengajuan->layanan->nama_layanan,
            $pengajuan->user->name,
            Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y'),
            $pengajuan->deadline ? Carbon::parse($pengajuan->deadline)->translatedFormat('d F Y') : '-',
            str_replace('_', ' ', $pengajuan->status_pengajuan),
            $pengajuan->tanggal_selesai ? Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') : '-',
        ];
    }
}
