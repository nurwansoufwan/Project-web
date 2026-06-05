@extends('layouts.tabler')

@section('title', 'Beranda')

@section('page-pretitle')
    <div class="page-pretitle">Ikhtisar</div>
@endsection

@section('page-title', 'Dashboard Rental')

@section('page-actions')
    <a href="{{ route('rentals.create') }}" class="btn btn-primary d-none d-sm-inline-block">
        <i class="ti ti-plus me-2"></i> Transaksi Sewa Baru
    </a>
@endsection

@section('content')
    <!-- Alerts Section -->
    @if($lowStockItems->count() > 0)
        <div class="alert premium-alert premium-alert-warning alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <span class="alert-icon-pulse">
                        <i class="ti ti-alert-triangle fs-2"></i>
                    </span>
                </div>
                <div>
                    <h4 class="alert-title fw-bold text-danger mb-1">Perhatian: Stok Alat Camping Menipis!</h4>
                    <div class="text-secondary">
                        Terdapat <strong>{{ $lowStockItems->count() }}</strong> alat camping dengan persediaan kritis (kurang dari atau sama dengan 3 unit):
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach($lowStockItems as $item)
                                <li>{{ $item->name }} - Kategori: <strong class="text-secondary">{{ $item->category }}</strong> (Sisa: <strong class="text-danger fw-bold">{{ $item->stock }} unit</strong>)</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                        Terdapat <strong>{{ $overdueRentalsList->count() }}</strong> transaksi aktif yang melewati batas pengembalian dan terhitung denda berjalan:
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach($overdueRentalsList as $rent)
                                <li>
                                    Kode: <a href="{{ route('rentals.show', $rent->id) }}" class="fw-bold text-danger text-decoration-underline">{{ $rent->transaction_code }}</a> 
                                    - Pelanggan: <strong>{{ $rent->customer->name }}</strong> (Jatuh tempo: <span class="text-danger fw-semibold">{{ $rent->return_date->format('d/m/Y') }}</span>)
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="row row-cards mb-4">
        <!-- Revenue Card -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-green text-white avatar">
                                <i class="ti ti-currency-dollar fs-2"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </div>
                            <div class="text-secondary">
                                Total Pendapatan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Rentals Card -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-blue text-white avatar">
                                <i class="ti ti-receipt fs-2"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ $activeRentals }} Aktif
                            </div>
                            <div class="text-secondary">
                                Sewa Sedang Jalan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Card -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="{{ $overdueRentals > 0 ? 'bg-danger text-white' : 'bg-yellow text-white' }} avatar">
                                <i class="ti ti-alert-triangle fs-2"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ $overdueRentals }} Terlambat
                            </div>
                            <div class="text-secondary">
                                Belum Dikembalikan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Equipment Card -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-purple text-white avatar">
                                <i class="ti ti-tent fs-2"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ $totalEquipment }} Barang
                            </div>
                            <div class="text-secondary">
                                Total Alat Camping
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row row-cards">
        <!-- Recent Transactions -->
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaksi Terbaru</h3>
                    <div class="card-actions">
                        <a href="{{ route('rentals.index') }}" class="btn btn-outline-secondary btn-sm">Lihat Semua</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Kode Sewa</th>
                                <th>Pelanggan</th>
                                <th>Tgl Sewa</th>
                                <th>Tgl Kembali</th>
                                <th>Total</th>
                                <th>Status Sewa</th>
                                <th>Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $rent)
                                <tr>
                                    <td><span class="text-secondary">{{ $rent->transaction_code }}</span></td>
                                    <td>{{ $rent->customer->name }}</td>
                                    <td>{{ $rent->rental_date->format('d/m/Y') }}</td>
                                    <td>{{ $rent->return_date->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($rent->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($rent->status === 'completed')
                                            <span class="badge bg-success-lt">Selesai</span>
                                        @elseif($rent->status === 'active')
                                            <span class="badge bg-blue-lt">Di-sewa</span>
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
                                    <td colspan="8" class="text-center text-secondary py-4">Belum ada transaksi sewa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Info / Tips -->
        <div class="col-12 col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Panduan Ringkas</h3>
                </div>
                <div class="card-body">
                    <ul class="steps steps-vertical">
                        <li class="step-item">
                            <div class="h4 m-0">1. Input Master Data</div>
                            <div class="text-secondary">Pastikan data alat camping & pelanggan sudah terisi di menu **Master Data**.</div>
                        </li>
                        <li class="step-item">
                            <div class="h4 m-0">2. Buat Transaksi Baru</div>
                            <div class="text-secondary">Pilih pelanggan, tentukan tanggal sewa, tambahkan barang yang ingin disewa beserta jumlahnya.</div>
                        </li>
                        <li class="step-item">
                            <div class="h4 m-0">3. Validasi Stok Otomatis</div>
                            <div class="text-secondary">Sistem akan memvalidasi stok alat sebelum menyetujui transaksi sewa baru.</div>
                        </li>
                        <li class="step-item">
                            <div class="h4 m-0">4. Pembayaran & Pengembalian</div>
                            <div class="text-secondary">Tandai pembayaran lunas dan lakukan proses pengembalian alat ketika penyewaan selesai.</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
