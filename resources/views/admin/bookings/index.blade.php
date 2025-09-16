@extends('layouts.admin')

@section('title', 'Kelola Booking')
@section('page-title', 'Kelola Booking')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-calendar-check"></i> Daftar Booking
            </h6>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Search & Quick Filters -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search"
                        placeholder="Cari kode booking atau nama customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="studio_id">
                        <option value="">Semua Studio</option>
                        @foreach ($studios as $studio)
                            <option value="{{ $studio->id }}" {{ request('studio_id') == $studio->id ? 'selected' : '' }}>
                                {{ $studio->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Booking Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Booking</th>
                            <th>Customer</th>
                            <th>Studio</th>
                            <th>Tanggal & Jam</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $booking->booking_code }}</div>
                                    <small class="text-muted">{{ $booking->duration }} jam</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $booking->user->name }}</div>
                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $booking->studio->name }}</div>
                                    <small class="text-muted">{{ $booking->studio->location }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $booking->booking_date->format('d M Y') }}</div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="fw-bold text-success">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </div>
                                    @if ($booking->dp_amount > 0)
                                        <small class="text-muted">
                                            DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @switch($booking->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @break

                                        @case('paid')
                                            <span class="badge bg-info">Dibayar</span>
                                        @break

                                        @case('confirmed')
                                            <span class="badge bg-primary">Dikonfirmasi</span>
                                        @break

                                        @case('completed')
                                            <span class="badge bg-success">Selesai</span>
                                        @break

                                        @case('cancelled')
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @break
                                    @endswitch
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $booking->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.bookings.show', $booking) }}"
                                            class="btn btn-outline-primary" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($booking->status === 'paid')
                                            <button class="btn btn-outline-success"
                                                onclick="markCompleted({{ $booking->id }})" title="Selesaikan">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif

                                        @if (in_array($booking->status, ['pending', 'paid']))
                                            <button class="btn btn-outline-danger"
                                                onclick="cancelBooking({{ $booking->id }})" title="Batalkan">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                        <div>Tidak ada data booking yang ditemukan</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($bookings->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter Advanced</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="GET">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="paid">Dibayar</option>
                                        <option value="confirmed">Dikonfirmasi</option>
                                        <option value="completed">Selesai</option>
                                        <option value="cancelled">Dibatalkan</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Studio</label>
                                    <select class="form-select" name="studio_id">
                                        <option value="">Semua Studio</option>
                                        @foreach ($studios as $studio)
                                            <option value="{{ $studio->id }}">{{ $studio->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Complete Booking Modal -->
        <div class="modal fade" id="completeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Selesaikan Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="completeForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Pelunasan (Opsional)</label>
                                <input type="number" class="form-control" name="remaining_payment"
                                    placeholder="Masukkan jumlah pelunasan jika ada">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select class="form-select" name="payment_method">
                                    <option value="cash">Tunai</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Selesaikan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cancel Booking Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Batalkan Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="cancelForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Apakah Anda yakin ingin membatalkan booking ini?
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alasan Pembatalan <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="cancel_reason" rows="3" required
                                    placeholder="Masukkan alasan pembatalan..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            function markCompleted(bookingId) {
                const form = document.getElementById('completeForm');
                form.action = `/admin/bookings/${bookingId}/complete`;
                new bootstrap.Modal(document.getElementById('completeModal')).show();
            }

            function cancelBooking(bookingId) {
                const form = document.getElementById('cancelForm');
                form.action = `/admin/bookings/${bookingId}/cancel`;
                new bootstrap.Modal(document.getElementById('cancelModal')).show();
            }
        </script>
    @endpush
