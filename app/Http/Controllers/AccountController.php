<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $orders = $user->orders()->with(['messages', 'invoices'])->latest()->get();

        return view('account.index', compact('user', 'orders'));
    }
}
