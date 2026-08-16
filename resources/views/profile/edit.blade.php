<x-app-layout>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            Pengaturan Akun - Profil
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-4">
        <!-- Account page navigation-->
        <nav class="nav nav-tabs card-header-tabs mb-4">
            <a class="nav-link active" href="{{ route('profile.edit') }}">Profil</a>
            {{-- Tab lain bisa ditambahkan di sini jika perlu di masa depan --}}
        </nav>
        
        <div class="row">
            <div class="col-xl-4">
                <!-- Profile picture card-->
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Foto Profil</div>
                    <div class="card-body text-center">
                        <!-- Profile picture image-->
                        <img class="img-account-profile rounded-circle mb-2" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0061f2&color=fff&size=128" alt="" />
                        <!-- Profile picture help block-->
                        <div class="small font-italic text-muted mb-4">Identitas akun Anda</div>
                        <!-- Profile status -->
                        <div class="badge bg-{{ Auth::user()->status === 'aktif' ? 'success' : 'danger' }}-soft text-{{ Auth::user()->status === 'aktif' ? 'success' : 'danger' }}">Akun {{ ucfirst(Auth::user()->status) }}</div>
                        <div class="badge bg-primary-soft text-primary mt-2">{{ ucfirst(Auth::user()->tipe) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <!-- Account details card-->
                <div class="card mb-4">
                    <div class="card-header">Detail Akun</div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Password change card-->
                <div class="card mb-4">
                    <div class="card-header">Ubah Password</div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete account card-->
                <div class="card border-start-lg border-start-danger">
                    <div class="card-header text-danger">Hapus Akun</div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
