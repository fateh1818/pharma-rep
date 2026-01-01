<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sale;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
        $title = 'dashboard';
        $total_purchases = Purchase::where('expiry_date','!=',Carbon::now())->count();
        $total_categories = Category::count();
        $total_suppliers = Supplier::count();
        $total_sales = Sale::count();
        
        $pieChart = app()->chartjs
                ->name('pieChart')
                ->type('pie')
                ->size(['width' => 400, 'height' => 200])
                ->labels(['Total Purchases', 'Total Suppliers','Total Sales'])
                ->datasets([
                    [
                        'backgroundColor' => ['#FF6384', '#36A2EB','#7bb13c'],
                        'hoverBackgroundColor' => ['#FF6384', '#36A2EB','#7bb13c'],
                        'data' => [$total_purchases, $total_suppliers,$total_sales]
                    ]
                ])
                ->options([]);
        
        $total_expired_products = Purchase::whereDate('expiry_date', '=', Carbon::now())->count();
        $latest_sales = Sale::whereDate('created_at','=',Carbon::now())->get();
        $today_sales = Sale::whereDate('created_at','=',Carbon::now())->sum('total_price');

        // Calculate Today's Profit
        $today_profit = Sale::whereDate('created_at', '=', Carbon::now())
            ->with(['product.purchase'])
            ->get()
            ->sum(function($sale) {
                $cost_price = $sale->product->purchase->cost_price ?? 0;
                $revenue = $sale->total_price;
                // Assuming total_price is for the whole quantity. Cost price is usually per unit.
                // We need to double check if total_price is unit * qty or just total. 
                // Sale migration says: quantity, total_price. Standard logic: total_price = unit_price * quantity.
                // So Total Cost = cost_price * quantity.
                $total_cost = $cost_price * $sale->quantity;
                return $revenue - $total_cost;
            });

        return view('admin.dashboard',compact(
            'title','pieChart','total_expired_products',
            'latest_sales','today_sales','total_categories', 'today_profit'
        ));
    }
}
