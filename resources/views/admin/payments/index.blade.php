@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-studio-100">
        <!-- Header -->
        <div class="p-6 border-b border-studio-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-studio-900 flex items-center">
                        <i class="fas fa-credit-card mr-3 text-accent-500"></i>
                        Daftar Pembayaran
                    </h2>
                    <p class="text-studio-600 mt-1">Kelola dan verifikasi pembayaran studio</p>
                </div>
                <!-- Stats Badge -->
                <div class="flex items-center space-x-4">
                    <div class="bg-warning-100 text-warning-800 px-4 py-2 rounded-xl font-semibold">
                        <i class="fas fa-clock mr-2"></i>
                        {{ $payments->where('status', 'pending')->count() }} Pending
                    </div>
                    <div class="bg-success-100 text-success-800 px-4 py-2 rounded-xl font-semibold">
                        <i class="fas fa-check mr-2"></i>
                        {{ $payments->where('status', 'verified')->count() }} Verified
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="p-6 border-b border-studio-100 bg-studio-50">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-studio-700 mb-2">Cari Pembayaran</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-studio-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Kode booking atau nama customer..."
                            class="w-full pl-10 pr-4 py-3 bg-white border border-studio-200 rounded-xl text-studio-900 placeholder-studio-500 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-studio-700 mb-2">Status</label>
                    <select name="status"
                        class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent transition-all duration-200">
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

                <!-- Payment Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-studio-700 mb-2">Tipe Pembayaran</label>
                    <select name="payment_type"
                        class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent transition-all duration-200">
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

                <!-- Search Button -->
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-accent-500 hover:bg-accent-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Payments Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-studio-50 border-b border-studio-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Kode Booking
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Studio
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Tipe
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Bukti
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Upload
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-studio-100">
                    @forelse($payments as $payment)
                        <tr
                            class="{{ $payment->status == 'pending' ? 'bg-warning-50 hover:bg-warning-100' : 'hover:bg-studio-50' }} transition-colors duration-200">
                            <!-- Kode Booking -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if ($payment->status == 'pending')
                                        <div class="w-2 h-2 bg-warning-500 rounded-full animate-pulse"></div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-studio-900">{{ $payment->booking->booking_code }}</div>
                                        <div class="text-sm text-studio-500">
                                            {{ $payment->booking->booking_date->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Customer -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-accent-500 to-accent-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($payment->booking->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-studio-900">{{ $payment->booking->user->name }}
                                        </div>
                                        <div class="text-sm text-studio-500">{{ $payment->booking->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Studio -->
                            <td class="px-6 py-4">
                                <div class="font-semibold text-studio-900">{{ $payment->booking->studio->name }}</div>
                                <div class="text-sm text-studio-500">{{ $payment->booking->studio->location }}</div>
                            </td>

                            <!-- Jumlah -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-success-600 text-lg">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </div>
                            </td>

                            <!-- Tipe -->
                            <td class="px-6 py-4">
                                @switch($payment->payment_type)
                                    @case('dp')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-coins mr-1"></i>
                                            DP
                                        </span>
                                    @break

                                    @case('full')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                            <i class="fas fa-money-bill-wave mr-1"></i>
                                            Full Payment
                                        </span>
                                    @break

                                    @case('remaining')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-accent-100 text-accent-800">
                                            <i class="fas fa-hand-holding-usd mr-1"></i>
                                            Pelunasan
                                        </span>
                                    @break
                                @endswitch
                            </td>

                            <!-- Bukti -->
                            <td class="px-6 py-4">
                                @if ($payment->payment_proof)
                                    <button onclick="showPaymentProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                                        class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-2 px-4 rounded-lg transition-all duration-200 flex items-center space-x-2">
                                        <i class="fas fa-image"></i>
                                        <span>Lihat Bukti</span>
                                    </button>
                                @else
                                    <span class="text-studio-400 italic">Tidak ada bukti</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                @switch($payment->status)
                                    @case('pending')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-warning-100 text-warning-800 animate-pulse">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pending Verifikasi
                                        </span>
                                    @break

                                    @case('verified')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Terverifikasi
                                        </span>
                                    @break

                                    @case('rejected')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Ditolak
                                        </span>
                                    @break
                                @endswitch
                            </td>

                            <!-- Upload Time -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-studio-500">
                                    {{ $payment->created_at->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-studio-400">
                                    {{ $payment->created_at->format('H:i') }}
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                @if ($payment->status == 'pending')
                                    <div class="flex items-center space-x-2">
                                        <!-- Verify Button -->
                                        <button onclick="verifyPayment({{ $payment->id }})"
                                            class="bg-success-500 hover:bg-success-600 text-white p-2 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                            title="Verifikasi Pembayaran">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <!-- Reject Button -->
                                        <button onclick="rejectPayment({{ $payment->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                            title="Tolak Pembayaran">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="text-xs text-studio-500">
                                        @if ($payment->verifiedBy)
                                            <div class="bg-studio-100 rounded-lg p-2">
                                                <div class="font-medium">Oleh: {{ $payment->verifiedBy->name }}</div>
                                                <div>{{ $payment->verified_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-credit-card text-6xl text-studio-300 mb-4"></i>
                                        <h3 class="text-lg font-semibold text-studio-900 mb-2">Tidak ada pembayaran ditemukan
                                        </h3>
                                        <p class="text-studio-500">Coba ubah filter atau kata kunci pencarian</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($payments->hasPages())
                <div class="px-6 py-4 border-t border-studio-100">
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Payment Proof Modal -->
        <div id="proofModal" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl max-h-[90vh] overflow-hidden">
                <div class="p-6 border-b border-studio-100 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-studio-900">Bukti Pembayaran</h3>
                    <button onclick="closeProofModal()"
                        class="text-studio-500 hover:text-studio-700 p-2 rounded-lg hover:bg-studio-100 transition-all">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6 text-center">
                    <img id="proofImage" src="" alt="Bukti Pembayaran"
                        class="max-w-full max-h-96 rounded-xl shadow-lg mx-auto">
                    <div class="mt-4">
                        <button onclick="downloadProof()"
                            class="bg-accent-500 hover:bg-accent-600 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200">
                            <i class="fas fa-download mr-2"></i>
                            Download Bukti
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verify Payment Modal -->
        <div id="verifyModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b border-studio-100">
                    <h3 class="text-xl font-bold text-studio-900 flex items-center">
                        <i class="fas fa-check-circle text-success-500 mr-3"></i>
                        Verifikasi Pembayaran
                    </h3>
                </div>

                <form id="verifyForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="p-6 space-y-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                            <div class="text-blue-800">
                                <p class="font-medium mb-1">Pastikan bukti pembayaran valid</p>
                                <p class="text-sm">Periksa kembali jumlah, tanggal, dan kesesuaian data sebelum memverifikasi.
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-studio-700 mb-2">Catatan Admin (Opsional)</label>
                            <textarea name="admin_notes" rows="3" placeholder="Tambahkan catatan verifikasi jika diperlukan..."
                                class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 placeholder-studio-500 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent resize-none"></textarea>
                        </div>
                    </div>

                    <div class="p-6 border-t border-studio-100 flex space-x-4">
                        <button type="button" onclick="closeVerifyModal()"
                            class="flex-1 bg-studio-200 hover:bg-studio-300 text-studio-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-success-500 hover:bg-success-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                            <i class="fas fa-check"></i>
                            <span>Verifikasi Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reject Payment Modal -->
        <div id="rejectModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b border-studio-100">
                    <h3 class="text-xl font-bold text-studio-900 flex items-center">
                        <i class="fas fa-times-circle text-red-500 mr-3"></i>
                        Tolak Pembayaran
                    </h3>
                </div>

                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="p-6 space-y-6">
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start space-x-3">
                            <i class="fas fa-exclamation-triangle text-red-600 mt-1"></i>
                            <div class="text-red-800">
                                <p class="font-medium mb-1">Penolakan pembayaran</p>
                                <p class="text-sm">Pastikan ada alasan yang jelas dan valid untuk menolak pembayaran ini.</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-studio-700 mb-2">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="admin_notes" rows="3" required
                                placeholder="Jelaskan alasan penolakan pembayaran (wajib diisi)..."
                                class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 placeholder-studio-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                        </div>
                    </div>

                    <div class="p-6 border-t border-studio-100 flex space-x-4">
                        <button type="button" onclick="closeRejectModal()"
                            class="flex-1 bg-studio-200 hover:bg-studio-300 text-studio-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Tolak Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            let currentProofUrl = '';

            function showPaymentProof(imageUrl) {
                currentProofUrl = imageUrl;
                document.getElementById('proofImage').src = imageUrl;
                document.getElementById('proofModal').classList.remove('hidden');
            }

            function closeProofModal() {
                document.getElementById('proofModal').classList.add('hidden');
            }

            function downloadProof() {
                if (currentProofUrl) {
                    const link = document.createElement('a');
                    link.href = currentProofUrl;
                    link.download = 'bukti-pembayaran.jpg';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }

            function verifyPayment(paymentId) {
                const form = document.getElementById('verifyForm');
                form.action = `/admin/payments/${paymentId}/verify`;
                document.getElementById('verifyModal').classList.remove('hidden');
            }

            function closeVerifyModal() {
                document.getElementById('verifyModal').classList.add('hidden');
            }

            function rejectPayment(paymentId) {
                const form = document.getElementById('rejectForm');
                form.action = `/admin/payments/${paymentId}/reject`;
                document.getElementById('rejectModal').classList.remove('hidden');
            }

            function closeRejectModal() {
                document.getElementById('rejectModal').classList.add('hidden');
            }

            // Close modals when clicking outside
            document.addEventListener('click', function(event) {
                const modals = ['proofModal', 'verifyModal', 'rejectModal'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (event.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            });

            // Auto refresh pending payments every 30 seconds
            @if (request('status') == 'pending' || !request('status'))
                let refreshInterval = setInterval(function() {
                    if (!document.querySelector('#verifyModal:not(.hidden)') &&
                        !document.querySelector('#rejectModal:not(.hidden)') &&
                        !document.querySelector('#proofModal:not(.hidden)')) {
                        location.reload();
                    }
                }, 30000);

                // Clear interval when page is hidden
                document.addEventListener('visibilitychange', function() {
                    if (document.hidden) {
                        clearInterval(refreshInterval);
                    } else {
                        refreshInterval = setInterval(function() {
                            if (!document.querySelector('#verifyModal:not(.hidden)') &&
                                !document.querySelector('#rejectModal:not(.hidden)') &&
                                !document.querySelector('#proofModal:not(.hidden)')) {
                                location.reload();
                            }
                        }, 30000);
                    }
                });
            @endif
        </script>
    @endpush
