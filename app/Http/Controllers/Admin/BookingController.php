<?php
// app/Http/Controllers/Admin/BookingController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Studio;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $query = Booking::with(['user', 'studio', 'payments']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by studio
        if ($request->filled('studio_id')) {
            $query->where('studio_id', $request->studio_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }

        // Search by booking code or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        $userQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->latest()->paginate(15);
        $studios = Studio::active()->orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'studios'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'studio', 'payments.verifiedBy']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,confirmed,completed,cancelled',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $booking->status;
        $booking->update([
            'status' => $request->status,
            'notes' => $request->admin_notes ?
                ($booking->notes ? $booking->notes . "\n\nAdmin: " . $request->admin_notes : "Admin: " . $request->admin_notes)
                : $booking->notes
        ]);

        // Log status change
        activity()
            ->performedOn($booking)
            ->causedBy(auth()->user())
            ->log("Status booking diubah dari {$oldStatus} ke {$request->status}");

        return back()->with('success', 'Status booking berhasil diupdate!');
    }

    public function markCompleted(Booking $booking, Request $request)
    {
        $request->validate([
            'remaining_payment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,qris'
        ]);

        try {
            // If there's remaining payment, record it
            if ($request->remaining_payment && $request->remaining_payment > 0) {
                $booking->payments()->create([
                    'amount' => $request->remaining_payment,
                    'payment_type' => 'remaining',
                    'payment_method' => $request->payment_method ?? 'cash',
                    'status' => 'verified',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'admin_notes' => 'Pelunasan di tempat'
                ]);
            }

            $booking->update(['status' => 'completed']);

            return back()->with('success', 'Booking berhasil diselesaikan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyelesaikan booking: ' . $e->getMessage());
        }
    }

    public function cancel(Booking $booking, Request $request)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500'
        ]);

        try {
            $this->bookingService->cancelBooking($booking->id, $request->cancel_reason);

            return back()->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }
}
