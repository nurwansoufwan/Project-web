@extends('layouts.tabler')

@section('title', 'Buat Transaksi Sewa Baru')

@section('page-pretitle')
    <div class="page-pretitle">Transaksi</div>
@endsection

@section('page-title', 'Sewa Baru')

@section('content')
    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('rentals.store') }}" method="POST" class="card" id="rental-form">
                @csrf
                <div class="card-header">
                    <h3 class="card-title">Form Input Persewaan</h3>
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

                    <div class="row mb-4">
                        <!-- Customer Selection -->
                        <div class="col-md-4">
                            <label class="form-label required">Pilih Pelanggan</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Pelanggan --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Start -->
                        <div class="col-md-4">
                            <label class="form-label required">Tanggal Mulai Sewa</label>
                            <input type="date" name="rental_date" id="rental_date" class="form-control" 
                                   value="{{ old('rental_date', now()->toDateString()) }}" 
                                   min="{{ now()->toDateString() }}" required>
                        </div>

                        <!-- Date End -->
                        <div class="col-md-4">
                            <label class="form-label required">Tanggal Selesai Sewa</label>
                            <input type="date" name="return_date" id="return_date" class="form-control" 
                                   value="{{ old('return_date', now()->addDay()->toDateString()) }}" 
                                   min="{{ now()->toDateString() }}" required>
                        </div>
                    </div>

                    <div class="hr-text">Daftar Barang Sewaan</div>

                    <div class="table-responsive">
                        <table class="table table-vcenter card-table" id="items-table">
                            <thead>
                                <tr>
                                    <th>Alat Camping</th>
                                    <th style="width: 15%;">Stok Tersedia</th>
                                    <th style="width: 18%;">Tarif per Hari</th>
                                    <th style="width: 15%;">Jumlah</th>
                                    <th style="width: 20%;">Subtotal</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody id="items-container">
                                <!-- Dynamic rows go here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-info" onclick="addItemRow()">
                            <i class="ti ti-plus me-1"></i> Tambah Barang
                        </button>
                    </div>

                    <!-- Estimation Section -->
                    <div class="card mt-4 bg-light">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <div class="text-secondary small">Durasi Sewa</div>
                                    <div class="h2 mb-0" id="rental-duration-display">1 Hari</div>
                                </div>
                                <div class="col-md-8 text-end">
                                    <div class="text-secondary small">Estimasi Total Biaya</div>
                                    <div class="h1 mb-0 text-primary" id="total-price-display">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('rentals.index') }}" class="btn btn-link link-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        <i class="ti ti-device-floppy me-2"></i> Proses Transaksi Sewa
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Inject equipment data for JS calculations and stock checks
        const equipments = @json($equipments);
        
        let rowIndex = 0;

        document.addEventListener('DOMContentLoaded', function() {
            // Initial row
            addItemRow();

            // Date change event listeners to recalculate duration
            document.getElementById('rental_date').addEventListener('change', function() {
                // Ensure return_date is not before rental_date
                const returnDateInput = document.getElementById('return_date');
                if (returnDateInput.value < this.value) {
                    returnDateInput.value = this.value;
                }
                returnDateInput.min = this.value;
                calculateTotals();
            });

            document.getElementById('return_date').addEventListener('change', calculateTotals);
        });

        function addItemRow() {
            const container = document.getElementById('items-container');
            const rowId = rowIndex++;

            let optionsHtml = '<option value="" disabled selected>-- Pilih Alat --</option>';
            equipments.forEach(item => {
                optionsHtml += `<option value="${item.id}">${item.name}</option>`;
            });

            const rowHtml = `
                <tr id="row-${rowId}" class="item-row">
                    <td>
                        <select name="items[${rowId}][equipment_id]" class="form-select equipment-select" onchange="onEquipmentChange(this, ${rowId})" required>
                            ${optionsHtml}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control bg-light stock-display" readonly value="-">
                    </td>
                    <td>
                        <input type="text" class="form-control bg-light price-display" readonly value="Rp 0">
                    </td>
                    <td>
                        <input type="number" name="items[${rowId}][quantity]" class="form-control quantity-input" min="1" value="1" oninput="calculateRowSubtotal(${rowId})" required disabled>
                    </td>
                    <td>
                        <input type="text" class="form-control bg-light subtotal-display fw-bold" readonly value="Rp 0">
                    </td>
                    <td>
                        <button type="button" class="btn btn-icon btn-ghost-danger btn-sm" onclick="removeItemRow(${rowId})">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            container.insertAdjacentHTML('beforeend', rowHtml);
            calculateTotals();
        }

        function removeItemRow(id) {
            const rows = document.querySelectorAll('.item-row');
            // Ensure at least 1 row remains
            if (rows.length <= 1) {
                alert('Transaksi sewa harus menyertakan minimal 1 barang camping.');
                return;
            }
            const row = document.getElementById(`row-${id}`);
            row.remove();
            calculateTotals();
        }

        function onEquipmentChange(selectElem, rowId) {
            const row = document.getElementById(`row-${rowId}`);
            const eqId = parseInt(selectElem.value);
            const equipment = equipments.find(e => e.id === eqId);

            const stockInput = row.querySelector('.stock-display');
            const priceInput = row.querySelector('.price-display');
            const qtyInput = row.querySelector('.quantity-input');

            if (equipment) {
                stockInput.value = `${equipment.stock} unit`;
                priceInput.value = formatRupiah(equipment.rental_price_per_day);
                
                qtyInput.max = equipment.stock;
                qtyInput.value = 1;
                qtyInput.disabled = false;
                
                // Add helper class or data to store price
                qtyInput.dataset.price = equipment.rental_price_per_day;
                qtyInput.dataset.stock = equipment.stock;
            } else {
                stockInput.value = '-';
                priceInput.value = 'Rp 0';
                qtyInput.value = 1;
                qtyInput.disabled = true;
                delete qtyInput.dataset.price;
                delete qtyInput.dataset.stock;
            }

            calculateRowSubtotal(rowId);
        }

        function calculateRowSubtotal(rowId) {
            const row = document.getElementById(`row-${rowId}`);
            const qtyInput = row.querySelector('.quantity-input');
            const subtotalDisplay = row.querySelector('.subtotal-display');
            
            const price = parseInt(qtyInput.dataset.price || 0);
            const stock = parseInt(qtyInput.dataset.stock || 0);
            let qty = parseInt(qtyInput.value || 0);

            // Client stock limit validation
            if (qty > stock) {
                alert(`Jumlah melebihi stok yang tersedia! Stok maksimum: ${stock}`);
                qtyInput.value = stock;
                qty = stock;
            }

            if (qty < 1 && !qtyInput.disabled) {
                qtyInput.value = 1;
                qty = 1;
            }

            const durationDays = getRentalDuration();
            const subtotal = qty * price * durationDays;

            subtotalDisplay.value = formatRupiah(subtotal);
            subtotalDisplay.dataset.value = subtotal;

            calculateTotals();
        }

        function getRentalDuration() {
            const startStr = document.getElementById('rental_date').value;
            const endStr = document.getElementById('return_date').value;

            if (!startStr || !endStr) return 1;

            const start = new Date(startStr);
            const end = new Date(endStr);
            
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            return Math.max(1, diffDays); // Minimal 1 hari
        }

        function calculateTotals() {
            const duration = getRentalDuration();
            document.getElementById('rental-duration-display').innerText = `${duration} Hari`;

            let grandTotal = 0;
            const rows = document.querySelectorAll('.item-row');

            rows.forEach(row => {
                const select = row.querySelector('.equipment-select');
                if (select.value) {
                    const qtyInput = row.querySelector('.quantity-input');
                    const price = parseInt(qtyInput.dataset.price || 0);
                    const qty = parseInt(qtyInput.value || 0);
                    
                    const subtotal = qty * price * duration;
                    row.querySelector('.subtotal-display').value = formatRupiah(subtotal);
                    grandTotal += subtotal;
                }
            });

            document.getElementById('total-price-display').innerText = formatRupiah(grandTotal);
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }

        // Form validation on submit
        document.getElementById('rental-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent normal submit to show custom confirmation

            const rows = document.querySelectorAll('.item-row');
            let hasSelection = false;

            for (let i = 0; i < rows.length; i++) {
                const select = rows[i].querySelector('.equipment-select');
                if (select.value) {
                    hasSelection = true;
                    break;
                }
            }

            if (!hasSelection) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih minimal satu alat camping untuk diproses.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-warning px-4'
                    },
                    buttonsStyling: false
                });
                return;
            }

            // Show custom Campfire SweetAlert2 dialog
            Swal.fire({
                html: `
                    <div class="camp-animation-wrapper">
                        <div class="campfire">
                            <div class="wood"></div>
                            <div class="flame red"></div>
                            <div class="flame orange"></div>
                            <div class="flame yellow"></div>
                            <div class="flame white"></div>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark">Proses Transaksi Sewa?</h3>
                    <p class="text-secondary mb-0">Apakah Anda yakin ingin memproses transaksi sewa alat camping ini?</p>
                `,
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-primary px-4 mx-2',
                    cancelButton: 'btn btn-link link-secondary mx-2'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form programmatically
                    this.submit();
                }
            });
        });
    </script>
@endsection
