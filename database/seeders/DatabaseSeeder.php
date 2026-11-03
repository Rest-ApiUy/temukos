<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderInvoice;
use App\Models\OrderMessage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Staff
        $staff = User::create([
            'name' => 'Tim TemuKost',
            'email' => 'staff@temukost.com',
            'phone' => '081234567890',
            'role' => 'staff',
            'password' => Hash::make('password'),
        ]);

        // 2. Create Customer
        $customer = User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@email.com',
            'phone' => '085712345678',
            'role' => 'customer',
            'password' => Hash::make('password'),
        ]);

        // 3. Create Sample Order
        $order = Order::create([
            'order_code' => 'TK-DEMO1',
            'user_id' => $customer->id,
            'city' => 'Sekitar Kampus UI Depok / Margonda',
            'budget' => 'Rp1,5-2 juta',
            'movein_date' => now()->addWeeks(2)->toDateString(),
            'near' => 'Stasiun Pondok Cina / Fakultas Teknik',
            'distance' => 'Maksimal 1 km',
            'features' => ['AC', 'Kamar mandi dalam', 'Wi-Fi', 'Parkir motor'],
            'gender' => 'Kos putra',
            'priority' => 'Lokasi dekat tujuan',
            'notes' => 'Lebih suka lantai atas dengan sirkulasi udara baik. Ada jam malam fleksibel jika memungkinkan.',
            'service_extras' => ['Video kos', 'Share loc'],
            'pickup' => true,
            'pickup_point' => 'Stasiun Pondok Cina',
            'package_name' => 'Layanan TemuKost',
            'price' => 500000,
            'payment_status' => 'paid',
            'status' => 'Pencarian berjalan',
        ]);

        // 4. Create Messages
        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $staff->id,
            'sender_role' => 'staff',
            'sender_name' => 'Tim TemuKost',
            'message' => 'Halo Andi! Kebutuhan kosmu di area Margonda/Pocin sudah kami terima. Tim kami sedang survei 3 opsi terdekat.',
            'created_at' => now()->subHours(5),
        ]);

        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $customer->id,
            'sender_role' => 'customer',
            'sender_name' => 'Andi Pratama',
            'message' => 'Terima kasih tim! Jangan lupa tolong cek kekuatan sinyal Wi-Fi di dalam kamarnya ya.',
            'created_at' => now()->subHours(4),
        ]);

        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $staff->id,
            'sender_role' => 'staff',
            'sender_name' => 'Tim TemuKost',
            'message' => 'Siap, sudah dicatat! Kami juga sediakan opsi video walkthrough 360 untuk kamar yang paling recommended.',
            'created_at' => now()->subHours(2),
        ]);

        // 5. Create Sample Extra Invoice
        OrderInvoice::create([
            'order_id' => $order->id,
            'invoice_code' => 'ADD-SURV1',
            'title' => 'Survei Ekstra & Video Call Interaktif',
            'note' => 'Video call live di 2 lokasi kos terpilih sesuai permintaan.',
            'amount' => 75000,
            'approved' => false,
            'paid' => false,
        ]);
    }
}
