<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderInvoice;
use App\Models\OrderMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function create(): View
    {
        return view('orders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'city' => ['required', 'string', 'max:255'],
            'budget' => ['required', 'string', 'max:100'],
            'movein_date' => ['nullable', 'date'],
            'near' => ['nullable', 'string', 'max:255'],
            'distance' => ['nullable', 'string', 'max:100'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string'],
            'gender' => ['nullable', 'string', 'max:100'],
            'priority' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1500'],
            'service_extras' => ['nullable', 'array'],
            'service_extras.*' => ['string'],
            'pickup' => ['nullable', 'boolean'],
            'pickup_point' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        $order = Order::create([
            'order_code' => Order::generateCode(),
            'user_id' => $user->id,
            'city' => $validated['city'],
            'budget' => $validated['budget'],
            'movein_date' => $validated['movein_date'] ?? null,
            'near' => $validated['near'] ?? null,
            'distance' => $validated['distance'] ?? null,
            'features' => $validated['features'] ?? [],
            'gender' => $validated['gender'] ?? null,
            'priority' => $validated['priority'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'service_extras' => $validated['service_extras'] ?? [],
            'pickup' => !empty($validated['pickup']),
            'pickup_point' => $validated['pickup_point'] ?? null,
            'package_name' => 'Layanan TemuKost',
            'price' => 500000,
            'payment_status' => 'pending',
            'status' => 'Menunggu pembayaran',
        ]);

        return redirect()->route('order.show', $order->order_code)
            ->with('success', 'Pesanan ' . $order->order_code . ' berhasil dibuat. Silakan konfirmasi layanan.');
    }

    public function show(Order $order): View
    {
        $user = Auth::user();
        if (!$user->isStaff() && $order->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $order->load(['user', 'messages', 'invoices']);

        return view('orders.show', compact('order'));
    }

    public function simulatePayment(Order $order): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isStaff() && $order->user_id !== $user->id) {
            abort(403);
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'Kriteria ditinjau',
        ]);

        // Kirim salam pembuka otomatis
        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => null,
            'sender_role' => 'staff',
            'sender_name' => 'Tim TemuKost',
            'message' => 'Halo ' . explode(' ', $order->user->name)[0] . '! Layanan pencarian kosmu sudah aktif. Tim sedang meninjau kriteria yang kamu kirimkan.',
        ]);

        return redirect()->route('order.show', $order->order_code)
            ->with('success', 'Konfirmasi layanan berhasil dicatat. Chat konsultasi telah aktif.');
    }

    public function sendMessage(Request $request, Order $order): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isStaff() && $order->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'sender_role' => $user->isStaff() ? 'staff' : 'customer',
            'sender_name' => $user->name,
            'message' => trim($validated['message']),
        ]);

        return redirect()->route('order.show', $order->order_code)
            ->with('success', 'Pesan terkirim.');
    }

    public function approveInvoice(Order $order, OrderInvoice $invoice): RedirectResponse
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        if ($invoice->order_id !== $order->id) {
            abort(404);
        }

        $invoice->update(['approved' => true]);

        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'sender_role' => 'customer',
            'sender_name' => $user->name,
            'message' => 'Saya menyetujui penawaran tambahan: "' . $invoice->title . '".',
        ]);

        return redirect()->route('order.show', $order->order_code)
            ->with('success', 'Penawaran disetujui.');
    }

    public function payInvoice(Order $order, OrderInvoice $invoice): RedirectResponse
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        if ($invoice->order_id !== $order->id) {
            abort(404);
        }

        $invoice->update(['paid' => true, 'approved' => true]);

        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'sender_role' => 'customer',
            'sender_name' => $user->name,
            'message' => 'Pembayaran layanan tambahan "' . $invoice->title . '" berhasil disimulasikan.',
        ]);

        return redirect()->route('order.show', $order->order_code)
            ->with('success', 'Pembayaran penawaran tambahan berhasil.');
    }
}
