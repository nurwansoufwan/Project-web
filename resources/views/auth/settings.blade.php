@extends('layouts.tabler')

@section('title', 'Pengaturan Akun')

@section('page-pretitle')
    <div class="page-pretitle">Pengaturan</div>
@endsection

@section('page-title', 'Pengaturan Akun')

@section('content')
    <div class="row row-cards">
        <div class="col-lg-6 mx-auto">
            <form action="{{ route('settings.password.update') }}" method="POST" class="card">
                @csrf
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-settings-automation me-2 text-primary"></i> Ganti Password</h3>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-start border-success" role="alert" style="border-left-width: 5px !important;">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="ti ti-circle-check fs-1 text-success"></i>
                                </div>
                                <div>
                                    <h4 class="alert-title fw-bold text-dark">Berhasil!</h4>
                                    <div class="text-secondary small">{{ session('success') }}</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show border-start border-danger" role="alert" style="border-left-width: 5px !important;">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="ti ti-alert-circle fs-1 text-danger"></i>
                                </div>
                                <div>
                                    <h4 class="alert-title fw-bold text-dark">Terjadi Kesalahan!</h4>
                                    <div class="text-secondary small">
                                        <ul class="mb-0 ps-2">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label required">Password Saat Ini</label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Masukkan password saat ini" required>
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" onclick="toggleField('current_password', event)">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Password Baru</label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" onclick="toggleField('password', event)">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Konfirmasi Password Baru</label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Masukkan kembali password baru" required>
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" onclick="toggleField('password_confirmation', event)">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-link link-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-device-floppy me-2"></i> Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function toggleField(fieldId, event) {
        event.preventDefault();
        const field = document.getElementById(fieldId);
        const icon = event.currentTarget.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('ti-eye');
            icon.classList.add('ti-eye-off');
        } else {
            field.type = 'password';
            icon.classList.remove('ti-eye-off');
            icon.classList.add('ti-eye');
        }
    }
</script>
@endsection
