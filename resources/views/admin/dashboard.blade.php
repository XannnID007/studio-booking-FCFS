@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        <!-- Stats Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-building fa-2x text-primary"></i>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Studio</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_studios'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Customer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_customers'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-calendar-check fa-2x text-info"></i>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Booking Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_bookings'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Payment</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_payments'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-line"></i> Pendapatan Bulan Ini
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-money-bill-wave fa-3x text-success"></i>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Pendapatan</div>
                            <div class="h4 mb-0 font-weight-bold text-success">
                                Rp {{ number_format($stats['this_month_revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings & Pending Payments -->
    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-list"></i> Booking Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode Booking</th>
                                    <th>Customer</th>
                                    <th>Studio</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $booking)
                                    <tr>
                                        <td>
                                            <small class="font-weight-bold">{{ $booking->booking_code }}</small>
                                        </td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->studio->name }}</td>
                                        <td>
                                            <small>
                                                {{ $booking->booking_date->format('d/m/Y') }}<br>
                                                {{ $booking->start_time->format('H:i') }} -
                                                {{ $booking->end_time->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if ($booking->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($booking->status == 'paid')
                                                <span class="badge bg-info">Dibayar</span>
                                            @elseif($booking->status == 'completed')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($booking->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox"></i> Belum ada booking
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($recentBookings->count() > 0)
                        <div class="text-end">
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary btn-sm">
                                Lihat Semua <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle"></i> Perlu Verifikasi
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($pendingPayments as $payment)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="me-3">
                                <i class="fas fa-credit-card fa-lg text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold text-sm">{{ $payment->booking->booking_code }}</div>
                                <div class="text-xs text-muted">{{ $payment->booking->user->name }}</div>
                                <div class="text-xs text-muted">{{ $payment->booking->studio->name }}</div>
                                <div class="text-sm font-weight-bold text-success">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <div>Semua pembayaran sudah diverifikasi</div>
                        </div>
                    @endforelse

                    @if ($pendingPayments->count() > 0)
                        <div class="text-end">
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-warning btn-sm">
                                Verifikasi <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt"></i> Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.studios.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus"></i><br>
                                <small>Tambah Studio</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-info w-100">
                                <i class="fas fa-calendar"></i><br>
                                <small>Kelola Booking</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-warning w-100">
                                <i class="fas fa-credit-card"></i><br>
                                <small>Verifikasi Payment</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-success w-100">
                                <i class="fas fa-chart-bar"></i><br>
                                <small>Lihat Laporan</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .border-start {
            border-left: 4px solid !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .text-xs {
            font-size: 0.75rem;
        }
    </style>
@endpush
