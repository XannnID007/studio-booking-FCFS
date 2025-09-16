<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Studio;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_studios' => Studio::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'today_bookings' => Booking::whereDate('booking_date', $today)->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'this_month_revenue' => Booking::where('status', 'completed')
                ->whereDate('created_at', '>=', $thisMonth)
                ->sum('total_price'),
        ];

        $recentBookings = Booking::with(['user', 'studio'])
            ->latest()
            ->take(10)
            ->get();

        $pendingPayments = Payment::with(['booking.user', 'booking.studio'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'pendingPayments'));
    }
}
