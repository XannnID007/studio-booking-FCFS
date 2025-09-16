<?php
// app/Http/Controllers/Admin/ReportController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Studio;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default date range (current month)
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Build query
        $query = Booking::with(['user', 'studio', 'payments'])
            ->whereBetween('booking_date', [$startDate, $endDate]);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by studio
        if ($request->filled('studio_id')) {
            $query->where('studio_id', $request->studio_id);
        }

        $bookings = $query->latest()->paginate(20);

        // Calculate statistics
        $stats = [
            'total_bookings' => $query->count(),
            'total_revenue' => $query->where('status', 'completed')->sum('total_price'),
            'completed_bookings' => $query->where('status', 'completed')->count(),
            'cancelled_bookings' => $query->where('status', 'cancelled')->count(),
        ];

        // Revenue by studio
        $revenueByStudio = Booking::with('studio')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('studio_id, SUM(total_price) as revenue, COUNT(*) as booking_count')
            ->groupBy('studio_id')
            ->get();

        $studios = Studio::active()->orderBy('name')->get();

        return view('admin.reports.index', compact(
            'bookings',
            'stats',
            'revenueByStudio',
            'studios',
            'startDate',
            'endDate'
        ));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        return Excel::download(
            new \App\Exports\BookingReportExport($startDate, $endDate, $request->all()),
            'laporan-booking-' . $startDate . '-to-' . $endDate . '.xlsx'
        );
    }

    public function exportPDF(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $query = Booking::with(['user', 'studio', 'payments'])
            ->whereBetween('booking_date', [$startDate, $endDate]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('studio_id')) {
            $query->where('studio_id', $request->studio_id);
        }

        $bookings = $query->latest()->get();

        $stats = [
            'total_bookings' => $bookings->count(),
            'total_revenue' => $bookings->where('status', 'completed')->sum('total_price'),
            'completed_bookings' => $bookings->where('status', 'completed')->count(),
            'cancelled_bookings' => $bookings->where('status', 'cancelled')->count(),
        ];

        $pdf = PDF::loadView('admin.reports.pdf', compact(
            'bookings',
            'stats',
            'startDate',
            'endDate'
        ));

        return $pdf->download('laporan-booking-' . $startDate . '-to-' . $endDate . '.pdf');
    }
}
