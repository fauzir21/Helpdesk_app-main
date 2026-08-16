<div class="modal fade" id="consentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="consentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="consentModalLabel">Pernyataan & Persetujuan</h5>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info small mb-3">
                    <i data-feather="info" class="me-1"></i>
                    Persetujuan harian diperlukan untuk memastikan keamanan data dan kepatuhan UU PDP No. 27 Tahun 2022.
                </div>

                <div id="consentText" class="p-3 border rounded bg-light mb-3"
                    style="max-height: 300px; overflow-y: auto;">
                    <h6 class="fw-bold mb-3">Harap baca seluruh pernyataan di bawah ini:</h6>
                    <ol class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item bg-transparent px-0">
                            <strong>1. Kebenaran Data Login</strong><br>
                            Saya menyatakan bahwa data username dan password yang saya masukkan adalah benar, milik saya
                            sendiri, dan sesuai dengan identitas yang terdaftar dalam sistem.
                        </li>
                        <li class="list-group-item bg-transparent px-0">
                            <strong>2. Larangan Penyalahgunaan Data & Informasi</strong><br>
                            Saya berkomitmen untuk tidak menyalahgunakan data dan informasi yang saya akses melalui
                            sistem ini untuk kepentingan pribadi, komersial, atau tujuan lain di luar tugas dan
                            kewenangan saya.
                        </li>
                        <li class="list-group-item bg-transparent px-0">
                            <strong>3. Kerahasiaan & Keamanan Akses</strong><br>
                            Saya bertanggung jawab menjaga kerahasiaan kredensial akun saya dan tidak akan membagikan
                            username atau password kepada pihak lain, serta segera melaporkan jika terjadi akses yang
                            tidak sah.
                        </li>
                        <li class="list-group-item bg-transparent px-0">
                            <strong>4. Kepatuhan terhadap UU PDP No. 27 Tahun 2022</strong><br>
                            Saya memahami bahwa pengolahan data pribadi dalam sistem ini dilindungi oleh Undang-Undang
                            Perlindungan Data Pribadi (UU PDP) dan saya bersedia mematuhi seluruh ketentuan yang
                            berlaku.
                        </li>
                        <li class="list-group-item bg-transparent px-0">
                            <strong>5. Tanggung Jawab Hukum</strong><br>
                            Saya menyadari bahwa setiap pelanggaran atas pernyataan ini dapat dikenai sanksi
                            administratif maupun hukum sesuai peraturan perundang-undangan yang berlaku.
                        </li>
                    </ol>
                </div>

                <form id="consentForm" method="POST" action="{{ route('consent.store') }}">
                    @csrf
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="agree" id="agreeCheckbox" disabled
                            required>
                        <label class="form-check-label fw-bold" for="agreeCheckbox">
                            Saya telah membaca, memahami, dan menyetujui seluruh pernyataan di atas.
                        </label>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light">Keluar</button>
                        </form>
                        <button type="submit" id="submitConsent" class="btn btn-primary" disabled>Lanjutkan ke
                            Dashboard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const consentModal = new bootstrap.Modal(document.getElementById('consentModal'));
        const consentText = document.getElementById('consentText');
        const agreeCheckbox = document.getElementById('agreeCheckbox');
        const submitBtn = document.getElementById('submitConsent');

        // Tampilkan modal
        consentModal.show();

        // Logika Active Reading (Scroll ke bawah)
        consentText.addEventListener('scroll', function() {
            // Cek jika scroll sudah mencapai bawah (toleransi 5px)
            if (consentText.scrollHeight - consentText.scrollTop <= consentText.clientHeight + 5) {
                agreeCheckbox.disabled = false;
            }
        });

        // Logika Enable Button
        agreeCheckbox.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });
    });
</script>
