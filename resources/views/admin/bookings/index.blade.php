@extends('layouts.admin')

@section('title', 'Kelola Booking')
@section('page-title', 'Kelola Booking')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-studio-100">
        <!-- Header -->
        <div class="p-6 border-b border-studio-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-studio-900 flex items-center">
                        <i class="fas fa-calendar-check mr-3 text-accent-500"></i>
                        Daftar Booking
                    </h2>
                    <p class="text-studio-600 mt-1">Kelola semua booking studio musik</p>
                </div>
                <button onclick="openFilterModal()"
                    class="bg-studio-100 hover:bg-studio-200 text-studio-700 font-medium py-2 px-4 rounded-xl transition-all duration-200 flex items-center space-x-2">
                    <i class="fas fa-filter"></i>
                    <span>Filter</span>
                </button>
            </div>
        </div>

        <!-- Search & Quick Filters -->
        <div class="p-6 border-b border-studio-100 bg-studio-50">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-studio-700 mb-2">Cari Booking</label>
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
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>

                <!-- Studio Filter -->
                <div>
                    <label class="block text-sm font-medium text-studio-700 mb-2">Studio</label>
                    <select name="studio_id"
                        class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Studio</option>
                        @foreach ($studios as $studio)
                            <option value="{{ $studio->id }}" {{ request('studio_id') == $studio->id ? 'selected' : '' }}>
                                {{ $studio->name }}
                            </option>
                        @endforeach
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

                <!-- Reset Button -->
                <div class="flex items-end">
                    <a href="{{ route('admin.bookings.index') }}"
                        class="w-full bg-studio-200 hover:bg-studio-300 text-studio-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-times"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
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
                            Tanggal & Jam
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Total Harga
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Dibuat
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-studio-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-studio-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-studio-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="font-bold text-studio-900">{{ $booking->booking_code }}</div>
                                <div class="text-sm text-studio-500">{{ $booking->duration }} jam</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-studio-900">{{ $booking->user->name }}</div>
                                <div class="text-sm text-studio-500">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-studio-900">{{ $booking->studio->name }}</div>
                                <div class="text-sm text-studio-500">{{ $booking->studio->location }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-studio-900">{{ $booking->booking_date->format('d M Y') }}
                                </div>
                                <div class="text-sm text-studio-500">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-success-600">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </div>
                                @if ($booking->dp_amount > 0)
                                    <div class="text-sm text-studio-500">
                                        DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @switch($booking->status)
                                    @case('pending')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pending
                                        </span>
                                    @break

                                    @case('paid')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-credit-card mr-1"></i>
                                            Dibayar
                                        </span>
                                    @break

                                    @case('confirmed')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-accent-100 text-accent-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Dikonfirmasi
                                        </span>
                                    @break

                                    @case('completed')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Selesai
                                        </span>
                                    @break

                                    @case('cancelled')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Dibatalkan
                                        </span>
                                    @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-studio-500">
                                    {{ $booking->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <!-- Detail Button -->
                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                        class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if ($booking->status === 'paid')
                                        <!-- Complete Button -->
                                        <button onclick="markCompleted({{ $booking->id }})"
                                            class="p-2 text-success-600 hover:text-success-700 hover:bg-success-50 rounded-lg transition-all duration-200"
                                            title="Selesaikan">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif

                                    @if (in_array($booking->status, ['pending', 'paid']))
                                        <!-- Cancel Button -->
                                        <button onclick="cancelBooking({{ $booking->id }})"
                                            class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200"
                                            title="Batalkan">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-6xl text-studio-300 mb-4"></i>
                                        <h3 class="text-lg font-semibold text-studio-900 mb-2">Tidak ada booking ditemukan</h3>
                                        <p class="text-studio-500">Coba ubah filter atau kata kunci pencarian</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($bookings->hasPages())
                <div class="px-6 py-4 border-t border-studio-100">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Filter Modal -->
        <div id="filterModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b border-studio-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-studio-900">Filter Advanced</h3>
                        <button onclick="closeFilterModal()" class="text-studio-500 hover:text-studio-700">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <form method="GET">
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-studio-700 mb-2">Tanggal Mulai</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-studio-700 mb-2">Tanggal Akhir</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-studio-700 mb-2">Status</label>
                            <select name="status"
                                class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Dibayar</option>
                                <option value="confirmed">Dikonfirmasi</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-studio-700 mb-2">Studio</label>
                            <select name="studio_id"
                                class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent">
                                <option value="">Semua Studio</option>
                                @foreach ($studios as $studio)
                                    <option value="{{ $studio->id }}">{{ $studio->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="p-6 border-t border-studio-100 flex space-x-4">
                        <button type="button" onclick="closeFilterModal()"
                            class="flex-1 bg-studio-200 hover:bg-studio-300 text-studio-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                            Tutup
                        </button>
                        <button type="submit"
                            class="flex-1 bg-accent-500 hover:bg-accent-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Complete Booking Modal -->
        <div id="completeModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b border-studio-100">
                    <h3 class="text-xl font-bold text-studio-900">Selesaikan Booking</h3>
                </div>

                <form id="completeForm" method="POST">
                    @csrf
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-studio-700 mb-2">Pelunasan (Opsional)</label>
                            <input type="number" name="remaining_payment" placeholder="Masukkan jumlah pelunasan jika ada"
                                class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 placeholder-studio-500 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-studio-700 mb-2">Metode Pembayaran</label>
                            <select name="payment_method"
                                class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent">
                                <option value="cash">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                    </div>

                    <div class="p-6 border-t border-studio-100 flex space-x-4">
                        <button type="button" onclick="closeCompleteModal()"
                            class="flex-1 bg-studio-200 hover:bg-studio-300 text-studio-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-success-500 hover:bg-success-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                            <i class="fas fa-check mr-2"></i>
                            Selesaikan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cancel Booking Modal -->
        <div id="cancelModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b border-studio-100">
                    <h3 class="text-xl font-bold text-studio-900">Batalkan Booking</h3>
                </div>

                <form id="cancelForm" method="POST">
                    @csrf
                    <div class="p-6 space-y-6">
                        <div class="bg-warning-50 border border-warning-200 rounded-xl p-4 flex items-center space-x-3">
                            <i class="fas fa-exclamation-triangle text-warning-600"></i>
                            <span class="text-warning-800">Apakah Anda yakin ingin membatalkan booking ini?</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-studio-700 mb-2">
                                Alasan Pembatalan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="cancel_reason" rows="3" required placeholder="Masukkan alasan pembatalan..."
                                class="w-full py-3 px-4 bg-white border border-studio-200 rounded-xl text-studio-900 placeholder-studio-500 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent resize-none"></textarea>
                        </div>
                    </div>

                    <div class="p-6 border-t border-studio-100 flex space-x-4">
                        <button type="button" onclick="closeCancelModal()"
                            class="flex-1 bg-studio-200 hover:bg-studio-300 text-studio-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Ya, Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            function openFilterModal() {
                document.getElementById('filterModal').classList.remove('hidden');
            }

            function closeFilterModal() {
                document.getElementById('filterModal').classList.add('hidden');
            }

            function markCompleted(bookingId) {
                const form = document.getElementById('completeForm');
                form.action = `/admin/bookings/${bookingId}/complete`;
                document.getElementById('completeModal').classList.remove('hidden');
            }

            function closeCompleteModal() {
                document.getElementById('completeModal').classList.add('hidden');
            }

            function cancelBooking(bookingId) {
                const form = document.getElementById('cancelForm');
                form.action = `/admin/bookings/${bookingId}/cancel`;
                document.getElementById('cancelModal').classList.remove('hidden');
            }

            function closeCancelModal() {
                document.getElementById('cancelModal').classList.add('hidden');
            }

            // Close modals when clicking outside
            document.addEventListener('click', function(event) {
                const modals = ['filterModal', 'completeModal', 'cancelModal'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (event.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            });
        </script>
    @endpush
