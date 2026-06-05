@extends('layouts.tabler')

@section('title', 'Pelanggan')

@section('page-pretitle')
    <div class="page-pretitle">Master Data</div>
@endsection

@section('page-title', 'Pelanggan')

@section('page-actions')
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-2"></i> Tambah Pelanggan
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pelanggan</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table text-nowrap">
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Email</th>
                        <th>Nomor Telepon</th>
                        <th>Alamat</th>
                        <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>
                                <div class="font-weight-medium text-dark">{{ $customer->name }}</div>
                            </td>
                            <td>
                                @if($customer->email)
                                    <span class="text-secondary">{{ $customer->email }}</span>
                                @else
                                    <span class="text-muted small">Tidak ada email</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-weight-medium">{{ $customer->phone }}</span>
                            </td>
                            <td>
                                <div class="text-secondary text-wrap" style="max-width: 350px;">
                                    {{ $customer->address }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger confirm-action"
                                                data-confirm-title="Hapus Pelanggan"
                                                data-confirm-message="Apakah Anda yakin ingin menghapus pelanggan '{{ $customer->name }}'? Tindakan ini tidak dapat dibatalkan."
                                                data-confirm-button-text="Ya, Hapus"
                                                data-cancel-button-text="Batal">
                                            <i class="ti ti-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">Belum ada pelanggan terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="card-footer d-flex align-items-center justify-content-between border-top py-3">
                <p class="m-0 text-secondary small">Menampilkan <strong>{{ $customers->firstItem() }}</strong> sampai <strong>{{ $customers->lastItem() }}</strong> dari <strong>{{ $customers->total() }}</strong> pelanggan</p>
                <div class="m-0 ms-auto">
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
