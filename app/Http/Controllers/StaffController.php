<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderInvoice;
use App\Models\OrderMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        if (!$user->isStaff()) {
            abort(403, 'Akses khusus staf internal TemuKost.');
        }

        $query = Order::with(['user', 'messages', 'invoices'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);
        $selectedOrder = null;

        if ($request->filled('select')) {
            $selectedOrder = Order::with(['user', 'messages', 'invoices'])->where('order_code', $request->select)->first();
        } elseif ($orders->count() > 0) {
            $selectedOrder = $orders->first();
        }

        return view('staff.index', compact('orders', 'selectedOrder'));
    }

    public function show(Order $order): View
    {
        $user = Auth::user();
        if (!$user->isStaff()) {
            abort(403);
        }

        $order->load(['user', 'messages', 'invoices']);
        return view('staff.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isStaff()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:Kriteria ditinjau,Pencarian berjalan,Pilihan dibagikan,Selesai,Dibatalkan'],
        ]);

        $order->update(['status' => $validated['status']]);

        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'sender_role' => 'staff',
            'sender_name' => 'Tim TemuKost',
            'message' => 'Status pencarian diperbarui menjadi: "' . $validated['status'] . '". Kami terus mengabari update terbarunya di sini.',
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function createInvoice(Request $request, Order $order): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isStaff()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:10000'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $invoice = OrderInvoice::create([
            'order_id' => $order->id,
            'invoice_code' => OrderInvoice::generateCode(),
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'note' => $validated['note'] ?? null,
            'approved' => false,
            'paid' => false,
        ]);

        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'sender_role' => 'staff',
            'sender_name' => 'Tim TemuKost',
            'message' => 'Kami menerbitkan penawaran layanan tambahan: "' . $invoice->title . '" seharga Rp ' . number_format($invoice->amount, 0, ',', '.') . '. Detail tersedia di rincian pesananmu.',
        ]);

        return redirect()->back()->with('success', 'Invoice penawaran tambahan berhasil diterbitkan.');
    }
}
