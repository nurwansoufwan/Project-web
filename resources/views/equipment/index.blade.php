@extends('layouts.tabler')

@section('title', 'Alat Camping')

@section('page-pretitle')
    <div class="page-pretitle">Master Data</div>
@endsection

@section('page-title', 'Alat Camping')

@section('page-actions')
    <a href="{{ route('equipment.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-2"></i> Tambah Alat
    </a>
@endsection

@section('content')
    @if($lowStockItems->count() > 0)
        <div class="alert premium-alert premium-alert-warning alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <span class="alert-icon-pulse">
                        <i class="ti ti-alert-triangle fs-2"></i>
                    </span>
                </div>
                <div>
                    <h4 class="alert-title fw-bold text-danger mb-1">Perhatian: Beberapa Stok Alat Menipis!</h4>
                    <div class="text-secondary">
                        Terdapat <strong>{{ $lowStockItems->count() }}</strong> alat camping dengan persediaan kritis (kurang dari atau sama dengan 3 unit). Harap segera lakukan restok barang.
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Alat Camping</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table text-nowrap">
                <thead>
                    <tr>
                        <th class="w-1">Gambar</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Tarif per Hari</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment as $item)
                        <tr>
                            <td>
                                @if($item->image_path)
                                    <span class="avatar avatar-md" style="background-image: url({{ asset($item->image_path) }})"></span>
                                @else
                                    <span class="avatar avatar-md bg-blue-lt">
                                        <i class="ti ti-tent"></i>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-medium text-dark">{{ $item->name }}</div>
                                <div class="small text-secondary text-wrap" style="max-width: 300px;">
                                    {{ Str::limit($item->description, 60, '...') }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary-lt">{{ $item->category }}</span>
                            </td>
                            <td class="font-weight-medium">
                                Rp {{ number_format($item->rental_price_per_day, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($item->stock <= 3)
                                    <span class="text-danger font-weight-bold">{{ $item->stock }} unit</span>
                                @else
                                    {{ $item->stock }} unit
                                @endif
                            </td>
                            <td>
                                @if($item->stock == 0)
                                    <span class="badge bg-danger-lt">Habis</span>
                                @elseif($item->stock <= 3)
                                    <span class="badge bg-warning-lt">Stok Menipis</span>
                                @else
                                    <span class="badge bg-success-lt">Tersedia</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('equipment.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('equipment.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger confirm-action"
                                                data-confirm-title="Hapus Alat Camping"
                                                data-confirm-message="Apakah Anda yakin ingin menghapus alat camping '{{ $item->name }}'? Tindakan ini tidak dapat dibatalkan."
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
                            <td colspan="7" class="text-center text-secondary py-4">Belum ada alat camping.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($equipment->hasPages())
            <div class="card-footer d-flex align-items-center justify-content-between border-top py-3">
                <p class="m-0 text-secondary small">Menampilkan <strong>{{ $equipment->firstItem() }}</strong> sampai <strong>{{ $equipment->lastItem() }}</strong> dari <strong>{{ $equipment->total() }}</strong> alat camping</p>
                <div class="m-0 ms-auto">
                    {{ $equipment->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
