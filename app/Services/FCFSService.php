<?php
// app/Services/FCFSService.php
namespace App\Services;

use App\Models\Booking;
use App\Models\Studio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FCFSService
{
     /**
      * Check availability menggunakan algoritma FCFS
      */
     public function checkAvailability($studioId, $date, $startTime, $endTime)
     {
          return DB::transaction(function () use ($studioId, $date, $startTime, $endTime) {
               // Lock tabel untuk memastikan FCFS
               $studio = Studio::lockForUpdate()->find($studioId);

               if (!$studio || $studio->status !== 'active') {
                    return false;
               }

               // Cek konflik jadwal berdasarkan waktu booking (FCFS)
               $conflictingBooking = Booking::where('studio_id', $studioId)
                    ->where('booking_date', $date)
                    ->where('status', '!=', 'cancelled')
                    ->where(function ($query) use ($startTime, $endTime) {
                         $query->whereBetween('start_time', [$startTime, $endTime])
                              ->orWhereBetween('end_time', [$startTime, $endTime])
                              ->orWhere(function ($q) use ($startTime, $endTime) {
                                   $q->where('start_time', '<=', $startTime)
                                        ->where('end_time', '>=', $endTime);
                              });
                    })
                    ->orderBy('booked_at', 'asc') // FCFS order
                    ->first();

               return !$conflictingBooking;
          });
     }

     /**
      * Create booking dengan FCFS logic
      */
     public function createBooking($data)
     {
          return DB::transaction(function () use ($data) {
               // Double check availability dengan lock
               if (!$this->checkAvailability(
                    $data['studio_id'],
                    $data['booking_date'],
                    $data['start_time'],
                    $data['end_time']
               )) {
                    throw new \Exception('Studio tidak tersedia pada waktu yang dipilih. Booking lain sudah lebih dulu.');
               }

               // Create booking dengan timestamp booked_at untuk FCFS
               $booking = Booking::create([
                    'user_id' => $data['user_id'],
                    'studio_id' => $data['studio_id'],
                    'booking_date' => $data['booking_date'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'duration' => $data['duration'],
                    'total_price' => $data['total_price'],
                    'dp_amount' => $data['dp_amount'] ?? 0,
                    'remaining_amount' => $data['remaining_amount'] ?? $data['total_price'],
                    'notes' => $data['notes'] ?? null,
                    'booking_code' => Booking::generateBookingCode(),
                    'booked_at' => Carbon::now(), // Crucial untuk FCFS
                    'status' => 'pending'
               ]);

               return $booking;
          });
     }

     /**
      * Get booking queue untuk studio tertentu (FCFS order)
      */
     public function getBookingQueue($studioId, $date)
     {
          return Booking::where('studio_id', $studioId)
               ->where('booking_date', $date)
               ->where('status', '!=', 'cancelled')
               ->with(['user', 'studio'])
               ->orderBy('booked_at', 'asc') // FCFS order
               ->get();
     }
}
