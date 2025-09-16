<?php
// app/Http/Controllers/Customer/DashboardController.php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Studio;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // User statistics
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'active_bookings' => $user->bookings()
                ->whereIn('status', ['pending', 'paid', 'confirmed'])
                ->count(),
            'completed_bookings' => $user->bookings()
                ->where('status', 'completed')
                ->count(),
            'total_spent' => $user->bookings()
                ->where('status', 'completed')
                ->sum('total_price'),
        ];

        // Recent bookings
        $recentBookings = $user->bookings()
            ->with(['studio', 'payments'])
            ->latest()
            ->take(5)
            ->get();

        // Upcoming bookings
        $upcomingBookings = $user->bookings()
            ->with(['studio', 'payments'])
            ->where('booking_date', '>=', $today)
            ->whereIn('status', ['paid', 'confirmed'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(3)
            ->get();

        // Popular studios
        $popularStudios = Studio::withCount(['bookings' => function ($query) {
            $query->where('status', '!=', 'cancelled')
                ->where('created_at', '>=', Carbon::now()->subMonth());
        }])
            ->where('status', 'active')
            ->orderByDesc('bookings_count')
            ->take(4)
            ->get();

        // Pending payments
        $pendingPayments = $user->bookings()
            ->with(['studio', 'payments' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->whereHas('payments', function ($query) {
                $query->where('status', 'pending');
            })
            ->get();

        return view('customer.dashboard', compact(
            'stats',
            'recentBookings',
            'upcomingBookings',
            'popularStudios',
            'pendingPayments'
        ));
    }
}
