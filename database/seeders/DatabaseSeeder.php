<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Studio;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Studio',
            'email' => 'admin@studiobooking.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin Street No. 123, Bandung',
            'is_active' => true
        ]);

        // Create Sample Customers
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081234567891',
                'address' => 'Jl. Customer No. 1, Bandung',
                'is_active' => true
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081234567892',
                'address' => 'Jl. Customer No. 2, Bandung',
                'is_active' => true
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081234567893',
                'address' => 'Jl. Customer No. 3, Bandung',
                'is_active' => true
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081234567894',
                'address' => 'Jl. Customer No. 4, Bandung',
                'is_active' => true
            ]
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }

        // Create Sample Studios
        $studios = [
            [
                'name' => 'Studio Premium A',
                'description' => 'Studio musik premium dengan fasilitas lengkap dan kualitas suara terbaik. Cocok untuk recording professional dan band practice.',
                'location' => 'Dago, Bandung',
                'price_per_hour' => 150000,
                'facilities' => [
                    'Drum Set',
                    'Guitar Amplifier',
                    'Bass Amplifier',
                    'Keyboard',
                    'Microphone',
                    'Audio Interface',
                    'Monitor Speaker',
                    'Air Conditioner',
                    'WiFi'
                ],
                'status' => 'active',
                'capacity' => 6
            ],
            [
                'name' => 'Studio Basic B',
                'description' => 'Studio musik dengan fasilitas standar yang cocok untuk latihan band dan recording demo.',
                'location' => 'Pasteur, Bandung',
                'price_per_hour' => 100000,
                'facilities' => [
                    'Drum Set',
                    'Guitar Amplifier',
                    'Bass Amplifier',
                    'Microphone',
                    'Monitor Speaker',
                    'Air Conditioner'
                ],
                'status' => 'active',
                'capacity' => 4
            ],
            [
                'name' => 'Studio Acoustic C',
                'description' => 'Studio khusus untuk musik acoustic dan vocal recording dengan treatment acoustic yang baik.',
                'location' => 'Setiabudi, Bandung',
                'price_per_hour' => 120000,
                'facilities' => [
                    'Acoustic Guitar',
                    'Piano',
                    'Condenser Microphone',
                    'Audio Interface',
                    'Studio Monitor',
                    'Acoustic Treatment',
                    'Air Conditioner',
                    'WiFi'
                ],
                'status' => 'active',
                'capacity' => 3
            ],
            [
                'name' => 'Studio Electric D',
                'description' => 'Studio dengan fokus pada musik electric dan rock. Dilengkapi dengan amplifier berkualitas tinggi.',
                'location' => 'Cihampelas, Bandung',
                'price_per_hour' => 130000,
                'facilities' => [
                    'Premium Drum Set',
                    'Marshall Amplifier',
                    'Fender Amplifier',
                    'Electric Guitar',
                    'Bass Guitar',
                    'Effects Pedal',
                    'Monitor Speaker',
                    'Air Conditioner'
                ],
                'status' => 'active',
                'capacity' => 5
            ],
            [
                'name' => 'Studio Compact E',
                'description' => 'Studio berukuran compact yang cocok untuk duo atau solo practice dengan harga terjangkau.',
                'location' => 'Buah Batu, Bandung',
                'price_per_hour' => 80000,
                'facilities' => [
                    'Drum Set',
                    'Guitar Amplifier',
                    'Bass Amplifier',
                    'Microphone',
                    'Monitor Speaker'
                ],
                'status' => 'active',
                'capacity' => 2
            ],
            [
                'name' => 'Studio VIP F',
                'description' => 'Studio VIP dengan fasilitas mewah dan perlengkapan premium untuk professional recording.',
                'location' => 'Dipatiukur, Bandung',
                'price_per_hour' => 200000,
                'facilities' => [
                    'Premium Drum Set',
                    'High-End Amplifiers',
                    'Professional Microphones',
                    'Mixing Console',
                    'Studio Monitors',
                    'Acoustic Treatment',
                    'Air Conditioner',
                    'WiFi',
                    'Lounge Area',
                    'Parking'
                ],
                'status' => 'active',
                'capacity' => 8
            ]
        ];

        foreach ($studios as $studioData) {
            Studio::create($studioData);
        }

        // Create Sample Bookings
        $users = User::where('role', 'customer')->get();
        $studios = Studio::all();

        for ($i = 0; $i < 15; $i++) {
            $user = $users->random();
            $studio = $studios->random();

            // Random date within last 30 days to next 30 days
            $bookingDate = Carbon::now()->addDays(rand(-30, 30));

            // Random start time between 09:00 - 20:00
            $startHour = rand(9, 20);
            $startTime = $bookingDate->copy()->setTime($startHour, 0, 0);

            // Random duration 1-4 hours
            $duration = rand(1, 4);
            $endTime = $startTime->copy()->addHours($duration);

            $totalPrice = $duration * $studio->price_per_hour;
            $dpAmount = $totalPrice * 0.5; // 50% DP
            $remainingAmount = $totalPrice - $dpAmount;

            // Random status
            $statuses = ['pending', 'paid', 'confirmed', 'completed', 'cancelled'];
            $status = $statuses[array_rand($statuses)];

            Booking::create([
                'user_id' => $user->id,
                'studio_id' => $studio->id,
                'booking_date' => $bookingDate->format('Y-m-d'),
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'duration' => $duration,
                'total_price' => $totalPrice,
                'dp_amount' => $dpAmount,
                'remaining_amount' => $remainingAmount,
                'status' => $status,
                'notes' => $status === 'cancelled' ? 'Dibatalkan karena perubahan jadwal' : null,
                'booking_code' => 'SB' . date('Ymd') . sprintf('%04d', $i + 1),
                'booked_at' => Carbon::now()->subMinutes(rand(1, 43200)) // Random time in last 30 days
            ]);
        }

        echo "✅ Database seeded successfully!\n";
        echo "👤 Admin Login: admin@studiobooking.com / admin123\n";
        echo "👥 Customer Login: john@example.com / password\n";
        echo "🏢 Created " . Studio::count() . " studios\n";
        echo "📅 Created " . Booking::count() . " bookings\n";
    }
}
