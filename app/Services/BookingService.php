<?php
// app/Services/BookingService.php
namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\FCFSService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BookingService
{
     protected $fcfsService;

     public function __construct(FCFSService $fcfsService)
     {
          $this->fcfsService = $fcfsService;
     }

     public function createBooking($data)
     {
          // Calculate duration and price
          $startTime = Carbon::parse($data['start_time']);
          $endTime = Carbon::parse($data['end_time']);
          $duration = $endTime->diffInHours($startTime);

          $studio = \App\Models\Studio::find($data['studio_id']);
          $totalPrice = $duration * $studio->price_per_hour;

          // Set DP (minimal 50%)
          $dpAmount = $totalPrice * 0.5;
          $remainingAmount = $totalPrice - $dpAmount;

          $bookingData = array_merge($data, [
               'duration' => $duration,
               'total_price' => $totalPrice,
               'dp_amount' => $dpAmount,
               'remaining_amount' => $remainingAmount,
          ]);

          return $this->fcfsService->createBooking($bookingData);
     }

     public function uploadPaymentProof($bookingId, $file, $paymentType = 'dp')
     {
          $booking = Booking::findOrFail($bookingId);

          // Store payment proof
          $path = $file->store('payment-proofs', 'public');

          // Create payment record
          $payment = Payment::create([
               'booking_id' => $bookingId,
               'amount' => $paymentType === 'dp' ? $booking->dp_amount : $booking->remaining_amount,
               'payment_type' => $paymentType,
               'payment_method' => 'qris', // or 'transfer'
               'payment_proof' => $path,
               'status' => 'pending'
          ]);

          return $payment;
     }

     public function verifyPayment($paymentId, $status, $adminNotes = null)
     {
          $payment = Payment::findOrFail($paymentId);
          $payment->update([
               'status' => $status,
               'admin_notes' => $adminNotes,
               'verified_by' => auth()->id(),
               'verified_at' => Carbon::now()
          ]);

          // Update booking status
          if ($status === 'verified') {
               $booking = $payment->booking;
               if ($payment->payment_type === 'dp') {
                    $booking->update(['status' => 'paid']);
               } else {
                    $booking->update(['status' => 'completed']);
               }
          }

          return $payment;
     }

     public function cancelBooking($bookingId, $reason = null)
     {
          $booking = Booking::findOrFail($bookingId);
          $booking->update([
               'status' => 'cancelled',
               'notes' => $reason
          ]);

          return $booking;
     }

     public function getAvailableSlots($studioId, $date)
     {
          $studio = \App\Models\Studio::find($studioId);
          $bookedSlots = Booking::where('studio_id', $studioId)
               ->where('booking_date', $date)
               ->where('status', '!=', 'cancelled')
               ->get(['start_time', 'end_time']);

          // Generate available slots (8:00 - 22:00)
          $availableSlots = [];
          $startHour = 8;
          $endHour = 22;

          for ($hour = $startHour; $hour < $endHour; $hour++) {
               $slotStart = sprintf('%02d:00:00', $hour);
               $slotEnd = sprintf('%02d:00:00', $hour + 1);

               $isBooked = $bookedSlots->contains(function ($booking) use ($slotStart, $slotEnd) {
                    return ($booking->start_time <= $slotStart && $booking->end_time > $slotStart) ||
                         ($booking->start_time < $slotEnd && $booking->end_time >= $slotEnd) ||
                         ($booking->start_time >= $slotStart && $booking->end_time <= $slotEnd);
               });

               if (!$isBooked) {
                    $availableSlots[] = [
                         'start_time' => $slotStart,
                         'end_time' => $slotEnd,
                         'slot_label' => sprintf('%02d:00 - %02d:00', $hour, $hour + 1)
                    ];
               }
          }

          return $availableSlots;
     }
}
