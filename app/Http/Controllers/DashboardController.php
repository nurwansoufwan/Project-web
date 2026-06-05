<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Customer;
use App\Models\Rental;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEquipment = Equipment::count();
        $totalCustomers = Customer::count();
        $activeRentals = Rental::where('status', 'active')->count();
        
        $totalRevenue = Rental::where('payment_status', 'paid')->sum('total_price') + Rental::sum('fine_amount');
        
        // Count for pending rentals
        $pendingRentals = Rental::where('status', 'pending')->count();
        
        // Recent 5 transactions
        $recentTransactions = Rental::with('customer')
            ->latest()
            ->take(5)
            ->get();
            
        // Overdue rentals count (status is active, return_date is past today, actual_return_date is null)
        $overdueRentals = Rental::where('status', 'active')
            ->where('return_date', '<', now()->toDateString())
            ->count();

        // Get actual low stock equipment (stock <= 3)
        $lowStockItems = Equipment::where('stock', '<=', 3)->get();

        // Get actual overdue rentals with customer
        $overdueRentalsList = Rental::with('customer')
            ->where('status', 'active')
            ->where('return_date', '<', now()->toDateString())
            ->get();

        return view('dashboard', compact(
            'totalEquipment',
            'totalCustomers',
            'activeRentals',
            'pendingRentals',
            'totalRevenue',
            'recentTransactions',
            'overdueRentals',
            'lowStockItems',
            'overdueRentalsList'
        ));
    }
}
