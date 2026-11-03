<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderInvoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemukostWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_loads_successfully(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('temukost');
        $response->assertSee('Layanan Pencarian Kos Personal');
    }

    public function test_quick_login_works_for_customer(): void
    {
        $customer = User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@email.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('quick-login'), ['role' => 'customer']);
        $response->assertRedirect(route('account.index'));
        $this->assertAuthenticatedAs($customer);
    }

    public function test_customer_can_create_order(): void
    {
        $customer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($customer);

        $payload = [
            'city' => 'Margonda Depok',
            'budget' => 'Rp1,5–2 juta',
            'near' => 'Kampus UI Depok',
            'distance' => 'Maksimal 1 km',
            'features' => ['AC', 'Wi-Fi', 'Kamar mandi dalam'],
            'gender' => 'Kos putra',
            'priority' => 'Lokasi paling dekat tujuan',
            'notes' => 'Tolong prioritaskan yang ada parkir motor aman.',
            'pickup' => '1',
            'pickup_point' => 'Stasiun Pondok Cina',
        ];

        $response = $this->post(route('order.store'), $payload);

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'city' => 'Margonda Depok',
            'pickup' => 1,
        ]);

        $order = Order::where('user_id', $customer->id)->first();
        $response->assertRedirect(route('order.show', $order->order_code));
    }

    public function test_customer_can_simulate_payment_and_send_chat(): void
    {
        $customer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $order = Order::create([
            'order_code' => 'TK-TEST1',
            'user_id' => $customer->id,
            'city' => 'Margonda',
            'budget' => 'Rp1,5-2 juta',
            'payment_status' => 'pending',
            'status' => 'Menunggu pembayaran',
        ]);

        $this->actingAs($customer);

        // Simulate payment
        $payResponse = $this->post(route('order.pay', $order->order_code));
        $payResponse->assertRedirect(route('order.show', $order->order_code));

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('Kriteria ditinjau', $order->status);

        // Send chat message
        $chatResponse = $this->post(route('order.chat', $order->order_code), [
            'message' => 'Halo tim, apakah area pocin dekat stasiun ada kamar kosong?',
        ]);
        $chatResponse->assertRedirect(route('order.show', $order->order_code));

        $this->assertDatabaseHas('order_messages', [
            'order_id' => $order->id,
            'sender_role' => 'customer',
            'message' => 'Halo tim, apakah area pocin dekat stasiun ada kamar kosong?',
        ]);
    }

    public function test_customer_cannot_view_other_customers_order(): void
    {
        $userA = User::create([
            'name' => 'User A',
            'email' => 'a@example.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $userB = User::create([
            'name' => 'User B',
            'email' => 'b@example.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $orderA = Order::create([
            'order_code' => 'TK-USRA1',
            'user_id' => $userA->id,
            'city' => 'Jakarta',
            'budget' => 'Rp2-3 juta',
        ]);

        $this->actingAs($userB);

        $response = $this->get(route('order.show', $orderA->order_code));
        $response->assertStatus(403);
    }

    public function test_staff_can_update_status_and_create_invoice(): void
    {
        $staff = User::create([
            'name' => 'Staf TemuKost',
            'email' => 'staff@temukost.com',
            'role' => 'staff',
            'password' => bcrypt('password'),
        ]);

        $customer = User::create([
            'name' => 'User C',
            'email' => 'c@example.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $order = Order::create([
            'order_code' => 'TK-USRC1',
            'user_id' => $customer->id,
            'city' => 'Depok',
            'budget' => 'Rp1-1,5 juta',
            'payment_status' => 'paid',
            'status' => 'Kriteria ditinjau',
        ]);

        $this->actingAs($staff);

        // Update status
        $statusResponse = $this->post(route('staff.status', $order->order_code), [
            'status' => 'Pencarian berjalan',
        ]);
        $statusResponse->assertStatus(302);

        $order->refresh();
        $this->assertEquals('Pencarian berjalan', $order->status);

        // Create invoice
        $invoiceResponse = $this->post(route('staff.invoice', $order->order_code), [
            'title' => 'Survei Khusus 3 Titik Tambahan',
            'amount' => 50000,
            'note' => 'Termasuk video call live.',
        ]);
        $invoiceResponse->assertStatus(302);

        $this->assertDatabaseHas('order_invoices', [
            'order_id' => $order->id,
            'title' => 'Survei Khusus 3 Titik Tambahan',
            'amount' => 50000,
            'approved' => 0,
        ]);
    }
}
