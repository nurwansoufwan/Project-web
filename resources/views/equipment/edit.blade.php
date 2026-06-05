@extends('layouts.tabler')

@section('title', 'Edit Alat Camping')

@section('page-pretitle')
    <div class="page-pretitle">Alat Camping</div>
@endsection

@section('page-title', 'Edit Alat Camping')

@section('content')
    <div class="row row-cards">
        <div class="col-lg-8 mx-auto">
            <form action="{{ route('equipment.update', $equipment->id) }}" method="POST" enctype="multipart/form-data" class="card">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h3 class="card-title">Form Edit Alat</h3>
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
                        <input type="text" name="name" class="form-control" value="{{ old('name', $equipment->name) }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Kategori</label>
                            <select name="category" class="form-select" required>
                                <option value="Tenda" {{ old('category', $equipment->category) == 'Tenda' ? 'selected' : '' }}>Tenda</option>
                                <option value="Tas Carrier" {{ old('category', $equipment->category) == 'Tas Carrier' ? 'selected' : '' }}>Tas Carrier</option>
                                <option value="Sleeping Bag" {{ old('category', $equipment->category) == 'Sleeping Bag' ? 'selected' : '' }}>Sleeping Bag</option>
                                <option value="Alat Masak" {{ old('category', $equipment->category) == 'Alat Masak' ? 'selected' : '' }}>Alat Masak</option>
                                <option value="Lampu" {{ old('category', $equipment->category) == 'Lampu' ? 'selected' : '' }}>Lampu</option>
                                <option value="Lain-lain" {{ old('category', $equipment->category) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Stok</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $equipment->stock) }}" min="0" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Tarif Sewa per Hari (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="rental_price_per_day" class="form-control" value="{{ old('rental_price_per_day', $equipment->rental_price_per_day) }}" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Alat (Pilih baru untuk mengganti)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            @if($equipment->image_path)
                                <div class="mt-2 d-flex align-items-center">
                                    <span class="avatar avatar-md me-2" style="background-image: url({{ asset($equipment->image_path) }})"></span>
                                    <span class="text-secondary small">Gambar saat ini</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Spesifikasi</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description', $equipment->description) }}</textarea>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('equipment.index') }}" class="btn btn-link link-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
