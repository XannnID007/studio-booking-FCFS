<?php
// app/Http/Controllers/Customer/StudioController.php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudioController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $query = Studio::active();

        // Search by name or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price_per_hour', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_hour', '<=', $request->max_price);
        }

        // Filter by capacity
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        // Filter by facilities
        if ($request->filled('facilities')) {
            foreach ($request->facilities as $facility) {
                $query->whereJsonContains('facilities', $facility);
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price_per_hour', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price_per_hour', 'desc');
                break;
            case 'popular':
                $query->withCount(['bookings' => function ($q) {
                    $q->where('status', '!=', 'cancelled')
                        ->where('created_at', '>=', Carbon::now()->subMonth());
                }])->orderByDesc('bookings_count');
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        $studios = $query->paginate(12)->appends(request()->query());

        // Get all unique facilities for filter
        $allFacilities = Studio::active()
            ->whereNotNull('facilities')
            ->pluck('facilities')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        return view('customer.studios.index', compact('studios', 'allFacilities'));
    }

    public function show(Studio $studio)
    {
        if ($studio->status !== 'active') {
            abort(404, 'Studio tidak tersedia');
        }

        $studio->load(['bookings' => function ($query) {
            $query->where('booking_date', '>=', Carbon::today())
                ->where('status', '!=', 'cancelled')
                ->orderBy('booking_date')
                ->orderBy('start_time');
        }]);

        // Get recent reviews/bookings for this studio
        $recentBookings = $studio->bookings()
            ->with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.studios.show', compact('studio', 'recentBookings'));
    }

    public function availability(Studio $studio, Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today'
        ]);

        try {
            $availableSlots = $this->bookingService->getAvailableSlots(
                $studio->id,
                $request->date
            );

            return response()->json([
                'success' => true,
                'slots' => $availableSlots,
                'studio' => [
                    'name' => $studio->name,
                    'price_per_hour' => $studio->price_per_hour
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAvailableSlots(Studio $studio, $date)
    {
        try {
            // Validate date
            $bookingDate = Carbon::parse($date);
            if ($bookingDate->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal tidak valid'
                ], 400);
            }

            $availableSlots = $this->bookingService->getAvailableSlots(
                $studio->id,
                $date
            );

            return response()->json([
                'success' => true,
                'slots' => $availableSlots,
                'date' => $bookingDate->format('d M Y'),
                'studio' => [
                    'id' => $studio->id,
                    'name' => $studio->name,
                    'price_per_hour' => $studio->price_per_hour
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data slot: ' . $e->getMessage()
            ], 500);
        }
    }
}
