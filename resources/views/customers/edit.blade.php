@extends('layouts.tabler')

@section('title', 'Edit Pelanggan')

@section('page-pretitle')
    <div class="page-pretitle">Pelanggan</div>
@endsection

@section('page-title', 'Edit Pelanggan')

@section('content')
    <div class="row row-cards">
        <div class="col-lg-8 mx-auto">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="card">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h3 class="card-title">Form Edit Pelanggan</h3>
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
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Alamat Email (Opsional)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Alamat Lengkap</label>
                        <textarea name="address" rows="4" class="form-control" required>{{ old('address', $customer->address) }}</textarea>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('customers.index') }}" class="btn btn-link link-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
