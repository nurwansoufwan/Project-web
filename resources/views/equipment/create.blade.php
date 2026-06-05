@extends('layouts.tabler')

@section('title', 'Tambah Alat Camping')

@section('page-pretitle')
    <div class="page-pretitle">Alat Camping</div>
@endsection

@section('page-title', 'Tambah Alat Camping')

@section('content')
    <div class="row row-cards">
        <div class="col-lg-8 mx-auto">
            <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data" class="card">
                @csrf
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Alat</h3>
                </div>
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 ps-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label required">Nama Alat Camping</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Tenda Dome 4 Orang" value="{{ old('name') }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Kategori</label>
                            <select name="category" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <option value="Tenda" {{ old('category') == 'Tenda' ? 'selected' : '' }}>Tenda</option>
                                <option value="Tas Carrier" {{ old('category') == 'Tas Carrier' ? 'selected' : '' }}>Tas Carrier</option>
                                <option value="Sleeping Bag" {{ old('category') == 'Sleeping Bag' ? 'selected' : '' }}>Sleeping Bag</option>
                                <option value="Alat Masak" {{ old('category') == 'Alat Masak' ? 'selected' : '' }}>Alat Masak</option>
                                <option value="Lampu" {{ old('category') == 'Lampu' ? 'selected' : '' }}>Lampu</option>
                                <option value="Lain-lain" {{ old('category') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Stok Awal</label>
                            <input type="number" name="stock" class="form-control" placeholder="Contoh: 10" value="{{ old('stock', 0) }}" min="0" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Tarif Sewa per Hari (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="rental_price_per_day" class="form-control" placeholder="Contoh: 40000" value="{{ old('rental_price_per_day') }}" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Alat (Opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <span class="form-hint">Format: jpg, jpeg, png. Maksimal 2MB.</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Spesifikasi</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="Tuliskan spesifikasi lengkap alat camping di sini...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('equipment.index') }}" class="btn btn-link link-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
