<!-- Modal Riwayat -->
<div class="modal fade" id="riwayatModal" tabindex="-1" aria-labelledby="riwayatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="riwayatModalLabel">Riwayat Status: <span id="modalTiket"></span>
                </h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="timeline timeline-xs" id="riwayatContent">
                    <!-- Content will be loaded here -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 small text-muted">Memuat riwayat...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button class="btn btn-light btn-sm" type="button" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showRiwayat(nomorTiket) {
        // Find or create modal instance
        let modalEl = document.getElementById('riwayatModal');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (!modal) {
            modal = new bootstrap.Modal(modalEl);
        }

        const content = document.getElementById('riwayatContent');
        const label = document.getElementById('modalTiket');

        label.innerText = nomorTiket;
        content.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 small text-muted">Memuat riwayat...</p>
            </div>
        `;

        modal.show();

        // Use jQuery AJAX if available, otherwise fetch
        const url = "{{ route('permohonan.riwayat', ':tiket') }}".replace(':tiket', nomorTiket);

        if (window.jQuery) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    content.innerHTML = response;
                    if (typeof feather !== 'undefined') feather.replace();
                },
                error: function() {
                    content.innerHTML = '<div class="alert alert-danger small">Gagal memuat riwayat.</div>';
                }
            });
        } else {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;
                    if (typeof feather !== 'undefined') feather.replace();
                })
                .catch(() => {
                    content.innerHTML = '<div class="alert alert-danger small">Gagal memuat riwayat.</div>';
                });
        }
    }
</script>
