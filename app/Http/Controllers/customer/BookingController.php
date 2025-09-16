<?php
// app/Http/Controllers/Customer/BookingController.php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Studio;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $bookings = Booking::with(['studio', 'payments'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function create(Studio $studio)
    {
        return view('customer.bookings.create', compact('studio'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $data = $request->all();
            $data['user_id'] = Auth::id();

            $booking = $this->bookingService->createBooking($data);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat!',
                'booking' => $booking->load(['studio', 'user'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show(Booking $booking)
    {
        // Ensure user can only see their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['studio', 'payments']);
        return view('customer.bookings.show', compact('booking'));
    }

    public function uploadPayment(Request $request, Booking $booking)
    {
        // Ensure user can only upload payment for their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB
            'payment_type' => 'required|in:dp,remaining'
        ]);

        try {
            $payment = $this->bookingService->uploadPaymentProof(
                $booking->id,
                $request->file('payment_proof'),
                $request->payment_type
            );

            return back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah bukti pembayaran: ' . $e->getMessage());
        }
    }

    public function cancel(Booking $booking, Request $request)
    {
        // Ensure user can only cancel their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        try {
            $this->bookingService->cancelBooking($booking->id, $request->reason);
            return back()->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }

    public function getQRCode(Booking $booking)
    {
        // Ensure user can only see QR for their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Generate QR Code for payment
        $qrData = [
            'booking_code' => $booking->booking_code,
            'amount' => $booking->dp_amount,
            'studio' => $booking->studio->name,
            'date' => $booking->booking_date->format('d/m/Y'),
            'time' => $booking->start_time . ' - ' . $booking->end_time
        ];

        return response()->json([
            'qr_data' => json_encode($qrData),
            'amount' => $booking->dp_amount,
            'booking_code' => $booking->booking_code
        ]);
    }
}
