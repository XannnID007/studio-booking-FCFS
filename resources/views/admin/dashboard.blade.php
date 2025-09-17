{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Studio -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-studio-100 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-studio-500 uppercase tracking-wide">Total Studio</p>
                    <p class="text-3xl font-bold text-studio-900 mt-2">{{ $stats['total_studios'] }}</p>
                    <p class="text-sm text-success-600 mt-1 flex items-center">
                        <i class="fas fa-arrow-up text-xs mr-1"></i>
                        +2 studio baru
                    </p>
                </div>
                <div
                    class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Customer -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-studio-100 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-studio-500 uppercase tracking-wide">Total Customer</p>
                    <p class="text-3xl font-bold text-studio-900 mt-2">{{ $stats['total_customers'] }}</p>
                    <p class="text-sm text-success-600 mt-1 flex items-center">
                        <i class="fas fa-arrow-up text-xs mr-1"></i>
                        +15 minggu ini
                    </p>
                </div>
                <div
                    class="w-14 h-14 bg-gradient-to-br from-success-500 to-success-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Booking Hari Ini -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-studio-100 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-studio-500 uppercase tracking-wide">Booking Hari Ini</p>
                    <p class="text-3xl font-bold text-studio-900 mt-2">{{ $stats['today_bookings'] }}</p>
                    <p class="text-sm text-accent-600 mt-1 flex items-center">
                        <i class="fas fa-clock text-xs mr-1"></i>
                        3 sedang berjalan
                    </p>
                </div>
                <div
                    class="w-14 h-14 bg-gradient-to-br from-accent-500 to-accent-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-check text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Payment -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-studio-100 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-studio-500 uppercase tracking-wide">Pending Payment</p>
                    <p class="text-3xl font-bold text-studio-900 mt-2">{{ $stats['pending_payments'] }}</p>
                    <p class="text-sm text-warning-600 mt-1 flex items-center">
                        <i class="fas fa-exclamation-triangle text-xs mr-1"></i>
                        Perlu verifikasi
                    </p>
                </div>
                <div
                    class="w-14 h-14 bg-gradient-to-br from-warning-500 to-warning-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Card -->
    <div
        class="bg-gradient-to-br from-studio-900 via-studio-800 to-studio-900 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-accent-500/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-accent-400/10 rounded-full -ml-12 -mb-12"></div>

        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold mb-2 flex items-center">
                        <i class="fas fa-chart-line mr-3 text-accent-400"></i>
                        Pendapatan Bulan Ini
                    </h3>
                    <p class="text-studio-300">Total revenue dari semua transaksi</p>
                </div>
                <div
                    class="w-16 h-16 bg-accent-500/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-accent-500/30">
                    <i class="fas fa-money-bill-wave text-accent-400 text-2xl"></i>
                </div>
            </div>

            <div class="flex items-baseline space-x-3 mb-4">
                <span class="text-4xl font-bold text-accent-400">
                    Rp {{ number_format($stats['this_month_revenue'], 0, ',', '.') }}
                </span>
                <span class="bg-success-500/20 text-success-400 text-sm font-semibold px-3 py-1 rounded-full">
                    +12.5%
                </span>
            </div>

            <div class="flex flex-wrap items-center gap-6 text-sm text-studio-300">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-accent-500 rounded-full"></div>
                    <span>Booking Completed: Rp 42,300,000</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span>DP Received: Rp 3,450,000</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Bookings -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-studio-100">
                <div class="p-6 border-b border-studio-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-studio-900 flex items-center">
                            <i class="fas fa-list mr-3 text-accent-500"></i>
                            Booking Terbaru
                        </h3>
                        <a href="{{ route('admin.bookings.index') }}"
                            class="text-accent-600 hover:text-accent-700 text-sm font-semibold flex items-center space-x-1 hover:space-x-2 transition-all">
                            <span>Lihat Semua</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentBookings as $booking)
                            <div
                                class="flex items-center justify-between p-4 bg-studio-50 rounded-xl hover:bg-studio-100 transition-all duration-200 group">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                                        {{ substr($booking->studio->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-studio-900">{{ $booking->booking_code }}</div>
                                        <div class="text-sm text-studio-600">{{ $booking->user->name }}</div>
                                        <div class="text-sm text-studio-500">{{ $booking->studio->name }}</div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-sm font-medium text-studio-900">
                                        {{ $booking->booking_date->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-studio-500 mb-2">
                                        {{ $booking->start_time->format('H:i') }} -
                                        {{ $booking->end_time->format('H:i') }}
                                    </div>
                                    @if ($booking->status == 'pending')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                            Pending
                                        </span>
                                    @elseif($booking->status == 'paid')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Dibayar
                                        </span>
                                    @elseif($booking->status == 'completed')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                            Selesai
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-studio-500">
                                <i class="fas fa-inbox text-4xl mb-4 text-studio-300"></i>
                                <p class="text-lg font-medium">Belum ada booking</p>
                                <p class="text-sm">Booking baru akan muncul di sini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Pending Payments -->
        <div class="space-y-8">
            <!-- Pending Payments -->
            <div class="bg-white rounded-2xl shadow-sm border border-studio-100">
                <div class="p-6 border-b border-studio-100">
                    <h3 class="text-lg font-bold text-studio-900 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3 text-warning-500"></i>
                        Perlu Verifikasi
                    </h3>
                </div>

                <div class="p-6">
                    @forelse($pendingPayments as $payment)
                        <div class="flex items-center space-x-4 p-4 bg-warning-50 rounded-xl mb-4 last:mb-0">
                            <div class="w-10 h-10 bg-warning-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-studio-900">{{ $payment->booking->booking_code }}</div>
                                <div class="text-sm text-studio-600">{{ $payment->booking->user->name }}</div>
                                <div class="text-sm text-studio-500">{{ $payment->booking->studio->name }}</div>
                                <div class="text-sm font-semibold text-success-600 mt-1">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-studio-500">
                            <i class="fas fa-check-circle text-3xl mb-3 text-success-500"></i>
                            <p class="font-medium">Semua pembayaran terverifikasi</p>
                            <p class="text-sm">Tidak ada pembayaran yang perlu diverifikasi</p>
                        </div>
                    @endforelse

                    @if ($pendingPayments->count() > 0)
                        <div class="mt-4">
                            <a href="{{ route('admin.payments.index') }}"
                                class="w-full bg-warning-500 hover:bg-warning-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                                <i class="fas fa-eye"></i>
                                <span>Verifikasi Sekarang</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
