<x-auth-layout>
    <x-slot name="title">Pemulihan Password - {{ config('app.name') }}</x-slot>

    <div class="password-recovery-wrapper">

        <div class="password-recovery-card">

            {{-- HEADER --}}
            <div class="password-recovery-header">
                <h1>Pemulihan Password</h1>

                <p>
                    Masukkan alamat email Anda dan kami akan mengirimkan
                    tautan untuk mengatur ulang password Anda.
                </p>
            </div>


            {{-- BODY --}}
            <div class="password-recovery-body">

                {{-- STATUS --}}
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')"
                />

                <form
                    method="POST"
                    action="{{ route('password.email') }}"
                >
                    @csrf

                    {{-- EMAIL --}}
                    <div class="password-field">

                        <label for="email">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="Masukkan alamat email"
                            class="@error('email') password-input-error @enderror"
                        >

                        @error('email')
                            <div class="password-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- BUTTON AREA --}}
                    <div class="password-action">

                        <a
                            href="{{ route('login') }}"
                            class="password-back"
                        >
                            ← Kembali ke Login
                        </a>

                        <button
                            type="submit"
                            class="password-submit"
                        >
                            Kirim Link Reset
                        </button>

                    </div>

                </form>

            </div>


            {{-- FOOTER --}}
            <div class="password-recovery-footer">

                <span>
                    Belum punya akun?
                </span>

                <a href="{{ route('register') }}">
                    Daftar Sekarang
                </a>

            </div>

        </div>

    </div>

</x-auth-layout>

/* =========================================================
   PEMULIHAN PASSWORD
   ========================================================= */

.password-recovery-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 20px;
}


.password-recovery-card {
    width: min(620px, 100%);

    background: rgba(255, 255, 255, 0.92);

    border: 1px solid rgba(255, 255, 255, 0.95);

    border-radius: 24px;

    overflow: hidden;

    box-shadow:
        0 20px 60px rgba(0, 0, 0, 0.18);

    backdrop-filter: blur(10px);
}


/* HEADER */

.password-recovery-header {
    padding: 38px 45px 25px;

    text-align: center;
}

.password-recovery-header h1 {
    margin: 0 0 10px;

    font-family: "Poppins", sans-serif;

    font-size: 27px;

    font-weight: 700;

    color: #1558e8;
}

.password-recovery-header p {
    margin: 0;

    font-size: 12px;

    line-height: 1.7;

    color: #6b7280;
}


/* BODY */

.password-recovery-body {
    padding: 10px 45px 30px;
}


/* FIELD */

.password-field {
    margin-bottom: 22px;
}

.password-field label {
    display: block;

    margin-bottom: 8px;

    font-size: 13px;

    font-weight: 500;

    color: #222;
}

.password-field input {
    width: 100%;

    height: 48px;

    padding: 0 17px;

    border: 1px solid #8e929a;

    border-radius: 13px;

    background: rgba(255, 255, 255, 0.9);

    font-family: "Poppins", sans-serif;

    font-size: 13px;

    color: #222;

    outline: none;

    transition: 0.2s ease;
}

.password-field input::placeholder {
    color: #9ca3af;
}

.password-field input:focus {
    border-color: #1558e8;

    background: #fff;

    box-shadow:
        0 0 0 3px rgba(21, 88, 232, 0.10);
}

.password-input-error {
    border-color: #dc2626 !important;
}


/* ERROR */

.password-error {
    margin-top: 6px;

    font-size: 11px;

    color: #dc2626;
}


/* ACTION */

.password-action {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-top: 10px;
}

.password-back {
    font-size: 12px;

    color: #1558e8;

    text-decoration: none;
}

.password-back:hover {
    text-decoration: underline;

    color: #0f47c7;
}

.password-submit {
    height: 45px;

    padding: 0 25px;

    border: 0;

    border-radius: 10px;

    background: #1558e8;

    color: #fff;

    font-family: "Poppins", sans-serif;

    font-size: 12px;

    font-weight: 600;

    cursor: pointer;

    transition: 0.2s ease;
}

.password-submit:hover {
    background: #0f47c7;

    transform: translateY(-1px);

    box-shadow:
        0 7px 18px rgba(21, 88, 232, 0.25);
}


/* =========================================================
   FOOTER
   UJUNG SECTION DIBUAT TUMPUL
   ========================================================= */

.password-recovery-footer {
    min-height: 65px;

    padding: 0 30px;

    border-top: 1px solid rgba(150, 150, 150, 0.3);

    background: rgba(255, 255, 255, 0.82);

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 5px;

    font-size: 12px;

    color: #666b75;

    /* INI YANG BIKIN UJUNG BAWAH TUMPUL */
    border-radius: 0 0 24px 24px;
}

.password-recovery-footer a {
    color: #1558e8;

    text-decoration: none;

    font-weight: 500;
}

.password-recovery-footer a:hover {
    text-decoration: underline;
}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 600px) {

    .password-recovery-wrapper {
        padding: 20px 12px;
    }

    .password-recovery-card {
        width: 100%;

        border-radius: 20px;
    }

    .password-recovery-header {
        padding: 30px 25px 20px;
    }

    .password-recovery-header h1 {
        font-size: 23px;
    }

    .password-recovery-header p {
        font-size: 10px;

        line-height: 1.6;
    }

    .password-recovery-body {
        padding: 10px 25px 25px;
    }

    .password-action {
        flex-direction: column;

        align-items: stretch;

        gap: 15px;
    }

    .password-back {
        text-align: center;

        order: 2;
    }

    .password-submit {
        width: 100%;

        order: 1;
    }

    .password-recovery-footer {
        min-height: 60px;

        padding: 10px 20px;

        border-radius: 0 0 20px 20px;
    }
}