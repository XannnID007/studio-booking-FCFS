@extends('layouts.customer')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Bookings -->
        <div class="bg-white rounded-2xl p-6 border border-studio-200 shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-studio-500 text-sm font-medium">Total Bookings</p>
                    <h3 class="text-2xl font-bold text-studio-900 mt-1">{{ $stats['total_bookings'] }}</h3>
                </div>
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-clock text-accent-500 mr-1"></i>
                <span class="text-accent-600 font-medium">Active:</span>
                <span class="text-studio-500 ml-1">{{ $stats['active_bookings'] }} booking</span>
            </div>
        </div>

        <!-- Completed Bookings -->
        <div
            class="bg-white rounded-2xl p-6 border border-studio-200 shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-studio-500 text-sm font-medium">Completed</p>
                    <h3 class="text-2xl font-bold text-studio-900 mt-1">{{ $stats['completed_bookings'] }}</h3>
                </div>
                <div
                    class="w-12 h-12 bg-gradient-to-br from-success-500 to-success-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-trophy text-success-500 mr-1"></i>
                <span class="text-success-600 font-medium">Sessions done</span>
            </div>
        </div>

        <!-- Total Spent -->
        <div
            class="bg-white rounded-2xl p-6 border border-studio-200 shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-studio-500 text-sm font-medium">Total Spent</p>
                    <h3 class="text-2xl font-bold text-studio-900 mt-1">Rp
                        {{ number_format($stats['total_spent'], 0, ',', '.') }}</h3>
                </div>
                <div
                    class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-chart-line text-accent-500 mr-1"></i>
                <span class="text-accent-600 font-medium">This month</span>
            </div>
        </div>

        <!-- Pending Payments -->
        <div
            class="bg-white rounded-2xl p-6 border border-studio-200 shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-studio-500 text-sm font-medium">Pending Payments</p>
                    <h3 class="text-2xl font-bold text-studio-900 mt-1">{{ $pendingPayments->count() }}</h3>
                </div>
                <div
                    class="w-12 h-12 bg-gradient-to-br from-warning-500 to-warning-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-exclamation-triangle text-warning-500 mr-1"></i>
                <span class="text-warning-600 font-medium">Need verification</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Upcoming Bookings -->
        <div class="bg-white rounded-2xl border border-studio-200 shadow-sm">
            <div class="p-6 border-b border-studio-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-studio-900">Upcoming Bookings</h2>
                    <a href="{{ route('customer.bookings.index') }}"
                        class="text-accent-600 hover:text-accent-700 text-sm font-medium transition-colors">
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if ($upcomingBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach ($upcomingBookings as $booking)
                            <div
                                class="flex items-center p-4 bg-studio-50 rounded-xl border border-studio-100 hover:border-accent-300 transition-colors">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-music text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-studio-900">{{ $booking->studio->name }}</h4>
                                    <p class="text-sm text-studio-500">
                                        {{ $booking->booking_date->format('d M Y') }} •
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($booking->status === 'confirmed') bg-success-100 text-success-800
                                        @elseif($booking->status === 'paid') bg-blue-100 text-blue-800
                                        @else bg-warning-100 text-warning-800 @endif">
                                        {{ $booking->status_label }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-studio-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-alt text-studio-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-studio-900 mb-2">No Upcoming Bookings</h3>
                        <p class="text-studio-500 mb-4">Start booking your favorite studio now!</p>
                        <a href="{{ route('customer.studios.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-accent-600 text-white rounded-lg hover:bg-accent-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Browse Studios
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Popular Studios -->
        <div class="bg-white rounded-2xl border border-studio-200 shadow-sm">
            <div class="p-6 border-b border-studio-200">
                <h2 class="text-xl font-bold text-studio-900">Popular Studios</h2>
                <p class="text-sm text-studio-500 mt-1">Most booked this month</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach ($popularStudios as $studio)
                        <div
                            class="flex items-center p-4 bg-studio-50 rounded-xl border border-studio-100 hover:border-accent-300 transition-colors group">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-studio-600 to-studio-700 rounded-xl flex items-center justify-center mr-4">
                                @if ($studio->image)
                                    <img src="{{ Storage::url($studio->image) }}" alt="{{ $studio->name }}"
                                        class="w-full h-full object-cover rounded-xl">
                                @else
                                    <i class="fas fa-building text-white text-lg"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-studio-900 group-hover:text-accent-600 transition-colors">
                                    {{ $studio->name }}
                                </h4>
                                <p class="text-sm text-studio-500">{{ $studio->location }}</p>
                                <p class="text-sm text-accent-600 font-medium">
                                    Rp {{ number_format($studio->price_per_hour, 0, ',', '.') }}/hour
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-studio-500">{{ $studio->bookings_count }} bookings</span>
                                <br>
                                <a href="{{ route('customer.studios.show', $studio) }}"
                                    class="text-accent-600 hover:text-accent-700 text-sm font-medium">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-2xl border border-studio-200 shadow-sm">
        <div class="p-6 border-b border-studio-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-studio-900">Recent Bookings</h2>
                <a href="{{ route('customer.bookings.index') }}"
                    class="text-accent-600 hover:text-accent-700 text-sm font-medium transition-colors">
                    View All
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-studio-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-studio-500 uppercase tracking-wider">Studio
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-studio-500 uppercase tracking-wider">Date &
                            Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-studio-500 uppercase tracking-wider">
                            Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-studio-500 uppercase tracking-wider">Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-studio-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-studio-500 uppercase tracking-wider">Action
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-studio-200">
                    @forelse($recentBookings as $booking)
                        <tr class="hover:bg-studio-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-studio-600 to-studio-700 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-music text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-studio-900">{{ $booking->studio->name }}
                                        </div>
                                        <div class="text-sm text-studio-500">{{ $booking->studio->location }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-studio-900">{{ $booking->booking_date->format('d M Y') }}</div>
                                <div class="text-sm text-studio-500">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-studio-900">{{ $booking->duration }} hours</td>
                            <td class="px-6 py-4 text-sm font-medium text-studio-900">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($booking->status === 'completed') bg-success-100 text-success-800
                                    @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($booking->status === 'paid') bg-indigo-100 text-indigo-800
                                    @elseif($booking->status === 'pending') bg-warning-100 text-warning-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $booking->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('customer.bookings.show', $booking) }}"
                                    class="text-accent-600 hover:text-accent-900 text-sm font-medium transition-colors">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div
                                    class="w-16 h-16 bg-studio-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-calendar-alt text-studio-400 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-studio-900 mb-2">No Bookings Yet</h3>
                                <p class="text-studio-500 mb-4">Start your music journey by booking a studio!</p>
                                <a href="{{ route('customer.studios.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-accent-600 text-white rounded-lg hover:bg-accent-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Browse Studios
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($pendingPayments->count() > 0)
        <!-- Pending Payments Alert -->
        <div class="mt-8 bg-gradient-to-r from-warning-50 to-yellow-50 border-l-4 border-warning-400 p-6 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-warning-400 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-warning-800">Payment Verification Pending</h3>
                    <div class="mt-2 text-sm text-warning-700">
                        <p>You have {{ $pendingPayments->count() }} payment(s) waiting for admin verification.</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            @foreach ($pendingPayments as $booking)
                                <li>{{ $booking->studio->name }} - {{ $booking->booking_date->format('d M Y') }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('customer.bookings.index') }}"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-warning-800 bg-warning-200 hover:bg-warning-300 transition-colors">
                            Check Status
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
