@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-credit-card"></i> Daftar Pembayaran
            </h6>
        </div>

        <div class="card-body">
            <!-- Search & Filters -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search"
                        placeholder="Cari kode booking atau nama customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending Verifikasi
                        </option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>
                            Terverifikasi
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                            Ditolak
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="payment_type">
                        <option value="">Semua Tipe</option>
                        <option value="dp" {{ request('payment_type') == 'dp' ? 'selected' : '' }}>
                            DP (Down Payment)
                        </option>
                        <option value="full" {{ request('payment_type') == 'full' ? 'selected' : '' }}>
                            Pembayaran Penuh
                        </option>
                        <option value="remaining" {{ request('payment_type') == 'remaining' ? 'selected' : '' }}>
                            Pelunasan
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>

            <!-- Payments Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Booking</th>
                            <th>Customer</th>
                            <th>Studio</th>
                            <th>Jumlah</th>
                            <th>Tipe</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="{{ $payment->status == 'pending' ? 'table-warning' : '' }}">
                                <td>
                                    <div class="fw-bold">{{ $payment->booking->booking_code }}</div>
                                    <small class="text-muted">
                                        {{ $payment->booking->booking_date->format('d M Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $payment->booking->user->name }}</div>
                                    <small class="text-muted">{{ $payment->booking->user->email }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $payment->booking->studio->name }}</div>
                                    <small class="text-muted">{{ $payment->booking->studio->location }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-success">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    @switch($payment->payment_type)
                                        @case('dp')
                                            <span class="badge bg-info">DP</span>
                                        @break

                                        @case('full')
                                            <span class="badge bg-primary">Full</span>
                                        @break

                                        @case('remaining')
                                            <span class="badge bg-secondary">Pelunasan</span>
                                        @break
                                    @endswitch
                                </td>
                                <td>
                                    @if ($payment->payment_proof)
                                        <button class="btn btn-outline-primary btn-sm"
                                            onclick="showPaymentProof('{{ asset('storage/' . $payment->payment_proof) }}')">
                                            <i class="fas fa-image"></i> Lihat
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($payment->status)
                                        @case('pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @break

                                        @case('verified')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Terverifikasi
                                            </span>
                                        @break

                                        @case('rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times"></i> Ditolak
                                            </span>
                                        @break
                                    @endswitch
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $payment->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    @if ($payment->status == 'pending')
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-success" onclick="verifyPayment({{ $payment->id }})"
                                                title="Verifikasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger" onclick="rejectPayment({{ $payment->id }})"
                                                title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @else
                                        <small class="text-muted">
                                            @if ($payment->verifiedBy)
                                                Oleh: {{ $payment->verifiedBy->name }}<br>
                                                {{ $payment->verified_at->format('d/m/Y H:i') }}
                                            @endif
                                        </small>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fas fa-credit-card fa-3x mb-3"></i>
                                        <div>Tidak ada data pembayaran yang ditemukan</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($payments->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $payments->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Proof Modal -->
        <div class="modal fade" id="proofModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bukti Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="proofImage" src="" alt="Bukti Pembayaran" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>

        <!-- Verify Payment Modal -->
        <div class="modal fade" id="verifyModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Verifikasi Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="verifyForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Pastikan bukti pembayaran sudah sesuai sebelum melakukan verifikasi.
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan Admin (Opsional)</label>
                                <textarea class="form-control" name="admin_notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Verifikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Payment Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="rejectForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Pastikan ada alasan yang jelas untuk menolak pembayaran ini.
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="admin_notes" rows="3" required
                                    placeholder="Masukkan alasan penolakan pembayaran..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            function showPaymentProof(imageUrl) {
                document.getElementById('proofImage').src = imageUrl;
                new bootstrap.Modal(document.getElementById('proofModal')).show();
            }

            function verifyPayment(paymentId) {
                const form = document.getElementById('verifyForm');
                form.action = `/admin/payments/${paymentId}/verify`;
                new bootstrap.Modal(document.getElementById('verifyModal')).show();
            }

            function rejectPayment(paymentId) {
                const form = document.getElementById('rejectForm');
                form.action = `/admin/payments/${paymentId}/reject`;
                new bootstrap.Modal(document.getElementById('rejectModal')).show();
            }

            // Auto refresh pending payments every 30 seconds
            @if (request('status') == 'pending' || !request('status'))
                setInterval(function() {
                    if (!document.querySelector('.modal.show')) {
                        location.reload();
                    }
                }, 30000);
            @endif
        </script>
    @endpush
