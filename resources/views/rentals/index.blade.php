@extends('layouts.tabler')

@section('title', 'Daftar Transaksi Sewa')

@section('page-pretitle')
    <div class="page-pretitle">Transaksi</div>
@endsection

@section('page-title', 'Daftar Transaksi Sewa')

@section('page-actions')
    <a href="{{ route('rentals.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-2"></i> Buat Transaksi Sewa
    </a>
@endsection

@section('content')
    @if($overdueRentalsList->count() > 0)
        <div class="alert premium-alert premium-alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <span class="alert-icon-pulse">
                        <i class="ti ti-alert-circle fs-2"></i>
                    </span>
                </div>
                <div>
                    <h4 class="alert-title fw-bold text-danger mb-1">Perhatian: Transaksi Terlambat & Dikenakan Denda!</h4>
                    <div class="text-secondary">
                        Terdapat <strong>{{ $overdueRentalsList->count() }}</strong> transaksi aktif yang terlambat mengembalikan barang sewaan dan dikenakan denda keterlambatan berjalan.
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('rentals.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari Kode / Pelanggan</label>
                    <input type="text" name="search" class="form-control" placeholder="Contoh: TR-... atau Budi" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status Sewa</label>
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Di-sewa</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status Pembayaran</label>
                    <select name="payment_status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="ti ti-filter me-2"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>Kode Sewa</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Sewa</th>
                        <th>Tanggal Kembali</th>
                        <th>Tgl Pengembalian Aktual</th>
                        <th>Total Tarif</th>
                        <th>Denda</th>
                        <th>Status Sewa</th>
                        <th>Status Bayar</th>
                        <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentals as $rent)
                        <tr>
                            <td>
                                <span class="font-weight-medium text-dark">{{ $rent->transaction_code }}</span>
                            </td>
                            <td>{{ $rent->customer->name }}</td>
                            <td>{{ $rent->rental_date->format('d/m/Y') }}</td>
                            <td>{{ $rent->return_date->format('d/m/Y') }}</td>
                            <td>
                                @if($rent->actual_return_date)
                                    <span class="text-success">{{ $rent->actual_return_date->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-warning small">-</span>
                                @endif
                            </td>
                            <td class="font-weight-medium">Rp {{ number_format($rent->total_price, 0, ',', '.') }}</td>
                            <td class="text-danger font-weight-medium">
                                @if($rent->fine_amount > 0)
                                    Rp {{ number_format($rent->fine_amount, 0, ',', '.') }}
                                @elseif($rent->status === 'active' && now()->toDateString() > $rent->return_date->toDateString())
                                    @php
                                        $lateDays = (int)abs(now()->startOfDay()->diffInDays($rent->return_date->startOfDay(), false));
                                        $totalQty = $rent->details->sum('quantity');
                                        $estFine = $lateDays * $totalQty * 15000;
                                    @endphp
                                    <span class="text-danger fw-bold" title="Terlambat {{ $lateDays }} hari (Estimasi Denda)">
                                        Rp {{ number_format($estFine, 0, ',', '.') }} <i class="ti ti-alert-triangle text-danger"></i>
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($rent->status === 'completed')
                                    <span class="badge bg-success-lt">Selesai</span>
                                @elseif($rent->status === 'active')
                                    @if(now()->toDateString() > $rent->return_date->toDateString())
                                        <span class="badge bg-danger text-danger-fg"><i class="ti ti-alert-triangle me-1"></i>Terlambat</span>
                                    @else
                                        <span class="badge bg-blue-lt">Di-sewa</span>
                                    @endif
                                @elseif($rent->status === 'cancelled')
                                    <span class="badge bg-red-lt">Batal</span>
                                @else
                                    <span class="badge bg-warning-lt">Tertunda</span>
                                @endif
                            </td>
                            <td>
                                @if($rent->payment_status === 'paid')
                                    <span class="badge bg-success-lt text-success border border-success-subtle px-2 py-1">
                                        <i class="ti ti-circle-check me-1 fs-4"></i> Lunas
                                    </span>
                                @else
                                    <span class="badge bg-warning-lt text-warning border border-warning-subtle px-2 py-1">
                                        <i class="ti ti-clock me-1 fs-4"></i> Belum Lunas
                                    </span>
                                @endif
                             </td>
                             <td>
                                 <a href="{{ route('rentals.show', $rent->id) }}" class="btn btn-sm btn-outline-primary fw-medium px-2 py-1">
                                     <i class="ti ti-eye me-1 fs-4"></i> Detail
                                 </a>
                             </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-secondary py-4">Tidak ada data transaksi sewa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rentals->hasPages())
            <div class="card-footer d-flex align-items-center justify-content-between border-top py-3">
                <p class="m-0 text-secondary small">Menampilkan <strong>{{ $rentals->firstItem() }}</strong> sampai <strong>{{ $rentals->lastItem() }}</strong> dari <strong>{{ $rentals->total() }}</strong> transaksi</p>
                <div class="m-0 ms-auto">
                    {{ $rentals->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
