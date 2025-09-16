<?php
// app/Http/Controllers/Admin/PaymentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\BookingService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.studio', 'verifiedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment type
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // Search by booking code or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('booking', function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        $userQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->latest()->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Request $request, Payment $payment)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        try {
            $this->bookingService->verifyPayment(
                $payment->id,
                'verified',
                $request->admin_notes
            );

            return back()->with('success', 'Pembayaran berhasil diverifikasi!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        try {
            $this->bookingService->verifyPayment(
                $payment->id,
                'rejected',
                $request->admin_notes
            );

            return back()->with('success', 'Pembayaran berhasil ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }
}
