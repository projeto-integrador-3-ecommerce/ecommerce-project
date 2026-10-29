<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'ordersCount' => Order::count(),
            'productsCount' => Product::count(),
            'openOrdersCount' => Order::whereNotIn('status', [Order::STATUS_DELIVERED, Order::STATUS_CANCELED])->count(),
            'recentOrders' => Order::with('user')->latest()->take(8)->get(),
        ]);
    }
}
