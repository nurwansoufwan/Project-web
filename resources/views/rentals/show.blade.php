@extends('layouts.tabler')

@section('title', 'Detail Transaksi Sewa')

@section('page-pretitle')
    <div class="page-pretitle">Transaksi</div>
@endsection

@section('page-title', 'Detail Sewa ' . $rental->transaction_code)

@section('page-actions')
    <div class="d-print-none d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="ti ti-printer me-2"></i> Cetak Invoice
        </button>
        <a href="{{ route('rentals.index') }}" class="btn btn-primary">
            <i class="ti ti-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-lg-8">
            <div class="card card-lg" id="invoice-card">
                <div class="card-body">
                    <!-- Invoice Header -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <span class="avatar bg-primary text-white rounded me-2">
                                    <i class="ti ti-tent"></i>
                                </span>
                                <span class="h2 mb-0 fw-bold">CampBilaBola</span>
                            </div>
                            <address class="text-secondary small">
                                Jl. Petualang Rimba No. 101<br>
                                Kawasan Wisata Alam, Bogor<br>
                                cs@campbilabola.com | 0811-2222-3333
                            </address>
                        </div>
                        <div class="col-6 text-end">
                            <div class="text-secondary small">KODE TRANSAKSI</div>
                            <div class="h2 text-dark font-weight-bold mb-2">{{ $rental->transaction_code }}</div>
                            <div class="text-secondary small">DIBUAT PADA</div>
                            <div class="font-weight-medium mb-2">{{ $rental->created_at->format('d M Y H:i') }}</div>
                            
                            <div class="d-flex justify-content-end gap-1 mt-2">
                                <!-- Status Badge -->
                                @if($rental->status === 'completed')
                                    <span class="badge bg-success text-white">Selesai Dikembalikan</span>
                                @elseif($rental->status === 'active')
                                    <span class="badge bg-blue text-white">Sedang Disewa</span>
                                @elseif($rental->status === 'cancelled')
                                    <span class="badge bg-danger text-white">Batal</span>
                                @endif
                                
                                <!-- Payment Badge -->
                                @if($rental->payment_status === 'paid')
                                    <span class="badge bg-success-lt text-success border border-success-subtle px-2 py-1">
                                        <i class="ti ti-circle-check me-1 fs-4"></i> Lunas
                                    </span>
                                @else
                                    <span class="badge bg-warning-lt text-warning border border-warning-subtle px-2 py-1">
                                        <i class="ti ti-clock me-1 fs-4"></i> Belum Lunas
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 border-top border-bottom py-3 bg-light">
                        <!-- Customer Column -->
                        <div class="col-6">
                            <div class="text-secondary small fw-bold text-uppercase mb-2">Penyewa / Pelanggan</div>
                            <div class="font-weight-bold text-dark fs-3 mb-1">{{ $rental->customer->name }}</div>
                            <div class="text-secondary mb-1"><i class="ti ti-phone me-1"></i> {{ $rental->customer->phone }}</div>
                            @if($rental->customer->email)
                                <div class="text-secondary mb-1"><i class="ti ti-mail me-1"></i> {{ $rental->customer->email }}</div>
                            @endif
                            <div class="text-secondary small"><i class="ti ti-map-pin me-1"></i> {{ $rental->customer->address }}</div>
                        </div>
                        <!-- Date details -->
                        <div class="col-6">
                            <div class="text-secondary small fw-bold text-uppercase mb-2">Detail Waktu & Durasi</div>
                            <div class="row g-2">
                                <div class="col-6 text-secondary small">Tanggal Mulai:</div>
                                <div class="col-6 text-dark font-weight-medium text-end">{{ $rental->rental_date->format('d M Y') }}</div>
                                
                                <div class="col-6 text-secondary small">Jatuh Tempo Kembali:</div>
                                <div class="col-6 text-dark font-weight-medium text-end">{{ $rental->return_date->format('d M Y') }}</div>
                                
                                <div class="col-6 text-secondary small">Durasi Sewa:</div>
                                <div class="col-6 text-dark font-weight-medium text-end">{{ $days }} Hari</div>

                                @if($rental->actual_return_date)
                                    <div class="col-6 text-success font-weight-bold">Tanggal Kembali Aktual:</div>
                                    <div class="col-6 text-success font-weight-bold text-end">{{ $rental->actual_return_date->format('d M Y') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <table class="table table-transparent table-responsive">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 1%">#</th>
                                <th>Alat Camping</th>
                                <th class="text-center" style="width: 1%">Qty</th>
                                <th class="text-end" style="width: 15%">Tarif / Hari</th>
                                <th class="text-end" style="width: 20%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rental->details as $index => $detail)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="font-weight-medium text-dark">{{ $detail->equipment->name }}</div>
                                        <div class="text-secondary small">{{ $detail->equipment->category }}</div>
                                    </td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->price_per_day, 0, ',', '.') }}</td>
                                    <td class="text-end font-weight-medium">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <!-- Totals -->
                            <tr>
                                <td colspan="4" class="text-end font-weight-bold">Subtotal Tarif Sewa ({{ $days }} Hari):</td>
                                <td class="text-end font-weight-medium">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @if($rental->fine_amount > 0 || $estimatedFine > 0)
                                <tr>
                                    <td colspan="4" class="text-end text-danger font-weight-bold">
                                        Denda Keterlambatan:
                                        @if($rental->status === 'active' && $lateDays > 0)
                                            <span class="text-secondary small">(Estimasi terlambat {{ $lateDays }} hari)</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-danger font-weight-bold">
                                        Rp {{ number_format($rental->fine_amount ?: $estimatedFine, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="bg-light">
                                <td colspan="4" class="text-end text-uppercase font-weight-bold fs-3 text-dark">Total Biaya:</td>
                                <td class="text-end font-weight-bold fs-3 text-primary">
                                    Rp {{ number_format($rental->total_price + ($rental->fine_amount ?: $estimatedFine), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 text-secondary small border-top pt-3">
                        <strong>Catatan Ketentuan Sewa:</strong>
                        <ol class="ps-3 mt-1 mb-0">
                            <li>Alat camping wajib dijaga kondisinya seperti saat diserahterimakan. Kerusakan/hilang menjadi tanggung jawab penyewa.</li>
                            <li>Keterlambatan pengembalian dikenakan denda flat sebesar <strong>Rp 15.000 per hari per unit barang</strong>.</li>
                            <li>Pembayaran sewa dapat dilakukan secara tunai atau transfer bank saat pengambilan barang.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Aksi Transaksi -->
        <div class="col-lg-4 d-print-none">
            <!-- Payment Action -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Status Pembayaran</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        Status Saat Ini: 
                        @if($rental->payment_status === 'paid')
                            <span class="badge bg-success-lt text-success border border-success-subtle px-2 py-1">
                                <i class="ti ti-circle-check me-1 fs-4"></i> LUNAS
                            </span>
                        @else
                            <span class="badge bg-warning-lt text-warning border border-warning-subtle px-2 py-1">
                                <i class="ti ti-clock me-1 fs-4"></i> BELUM LUNAS
                            </span>
                        @endif
                    </div>
                    
                    @if($rental->payment_status === 'unpaid')
                        <p class="text-secondary small">Klik tombol di bawah jika pelanggan sudah melunasi total biaya rental alat camping ini.</p>
                        <form action="{{ route('rentals.pay', $rental->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 confirm-action" 
                                    data-confirm-title="Konfirmasi Pembayaran Lunas" 
                                    data-confirm-message="Apakah Anda yakin ingin mengonfirmasi bahwa transaksi ini telah LUNAS?" 
                                    data-confirm-button-text="Ya, Lunas" 
                                    data-cancel-button-text="Batal">
                                <i class="ti ti-check me-2"></i> Konfirmasi Bayar Lunas
                            </button>
                        </form>
                    @else
                        <div class="text-success d-flex align-items-center">
                            <i class="ti ti-circle-check fs-2 me-2"></i> Pembayaran lunas terkonfirmasi.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Return Action -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengembalian Alat</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        Status Transaksi:
                        @if($rental->status === 'completed')
                            <span class="badge bg-success">Selesai Dikembalikan</span>
                        @elseif($rental->status === 'active')
                            <span class="badge bg-blue">Sedang Disewa</span>
                        @else
                            <span class="badge bg-secondary">{{ $rental->status }}</span>
                        @endif
                    </div>

                    @if($rental->status === 'active')
                        <!-- Show Warning if Late -->
                        @if(now()->toDateString() > $rental->return_date->toDateString())
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-title"><i class="ti ti-alert-triangle me-2"></i>Terlambat Mengembalikan!</h4>
                                <div class="text-secondary small">
                                    Batas waktu: {{ $rental->return_date->format('d/m/Y') }} (Terlambat {{ $lateDays }} hari). 
                                    Estimasi denda saat ini adalah Rp {{ number_format($estimatedFine, 0, ',', '.') }}.
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info small" role="alert">
                                Batas pengembalian adalah tanggal <strong>{{ $rental->return_date->format('d/m/Y') }}</strong>. Alat camping masih dalam masa peminjaman.
                            </div>
                        @endif

                        <form action="{{ route('rentals.return', $rental->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label required">Tanggal Pengembalian Aktual</label>
                                <input type="date" name="actual_return_date" class="form-control" value="{{ now()->toDateString() }}" required>
                            </div>
                            <p class="text-secondary small">Tentukan tanggal pengembalian di atas. Sistem akan secara otomatis menghitung denda jika melebihi batas jatuh tempo.</p>
                            <button type="submit" class="btn btn-primary w-100 confirm-action" 
                                    data-confirm-title="Pengembalian Alat Camping" 
                                    data-confirm-message="Apakah Anda yakin ingin memproses pengembalian alat camping dengan tanggal tersebut?" 
                                    data-confirm-button-text="Ya, Kembalikan" 
                                    data-cancel-button-text="Batal">
                                <i class="ti ti-arrow-back-up me-2"></i> Proses Pengembalian Alat
                            </button>
                        </form>
                    @else
                        <div class="text-success d-flex align-items-center mb-2">
                            <i class="ti ti-circle-check fs-2 me-2"></i> Alat camping sudah dikembalikan.
                        </div>
                        @if($rental->actual_return_date)
                            <div class="text-secondary small">
                                Dikembalikan pada: {{ $rental->actual_return_date->format('d/m/Y') }}<br>
                                Denda keterlambatan: <strong>Rp {{ number_format($rental->fine_amount, 0, ',', '.') }}</strong>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
