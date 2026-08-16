@forelse($timeline as $log)
    <div class="timeline-item">
        <div class="timeline-item-marker">
            <div class="timeline-item-marker-indicator {{ $log['type'] == 'status' ? 'bg-primary' : 'bg-info' }}"></div>
        </div>
        <div class="timeline-item-content pt-0">
            <div class="card shadow-none border-0 mb-3 bg-light">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="small text-muted">{{ \Carbon\Carbon::parse($log['tanggal'])->translatedFormat('d F Y, H:i') }} WIB</div>
                        <span class="badge {{ $log['type'] == 'status' ? 'bg-primary-soft text-primary' : 'bg-info-soft text-info' }} small">{{ str_replace('_', ' ', $log['status']) }}</span>
                    </div>
                    <div class="mb-1">{!! nl2br(e($log['keterangan'])) !!}</div>
                    <div class="small text-muted mt-2">
                        <i data-feather="user" class="me-1" style="width: 12px; height: 12px;"></i>
                        {{ $log['user'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-4 text-muted">Belum ada riwayat pergerakan tiket.</div>
@endforelse
