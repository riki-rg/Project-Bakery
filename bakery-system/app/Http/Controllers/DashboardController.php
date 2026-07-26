<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Attendance;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::where('is_active', true)->count();
        $totalEmployees = User::where('role', 'employee')->where('is_active', true)->count();
        
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('current_stock', '<=', 'low_stock_threshold')
            ->with('category')
            ->get();
        
        $todayAttendance = Attendance::whereDate('attendance_date', today())
            ->with('user')
            ->get();
        
        $recentStockMovements = StockMovement::with(['product', 'user'])
            ->orderBy('occurred_at', 'desc')
            ->take(10)
            ->get();

        // Analytics: Stock Movement Chart Data (7 days)
        $stockChartData = $this->getStockMovementChartData();
        
        // Analytics: Stock Trends (IN/OUT/Adjustment) for last 7 days
        $stockTrends = $this->getStockTrends();
        
        // Analytics: Top Moving Products (last 7 days)
        $topMovingProducts = $this->getTopMovingProducts();
        
        // Analytics: Slow Moving Products (last 7 days)
        $slowMovingProducts = $this->getSlowMovingProducts();

        return view('dashboard', compact(
            'totalProducts',
            'totalEmployees',
            'lowStockProducts',
            'todayAttendance',
            'recentStockMovements',
            'stockChartData',
            'stockTrends',
            'topMovingProducts',
            'slowMovingProducts'
        ));
    }
    
    /**
     * Get stock movement chart data for last 7 days
     */
    private function getStockMovementChartData()
    {
        $days = 7;
        $data = [
            'labels' => [],
            'stockIn' => [],
            'stockOut' => [],
        ];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $data['labels'][] = $date->format('d M');
            
            // Stock IN
            $stockIn = StockMovement::whereDate('occurred_at', $date)
                ->where('movement_type', 'in')
                ->sum('quantity');
            $data['stockIn'][] = $stockIn;
            
            // Stock OUT
            $stockOut = StockMovement::whereDate('occurred_at', $date)
                ->where('movement_type', 'out')
                ->sum('quantity');
            $data['stockOut'][] = abs($stockOut);
        }
        
        return $data;
    }
    
    /**
     * Get stock trends for last 7 days vs previous 7 days
     */
    private function getStockTrends()
    {
        $currentWeekStart = Carbon::today()->subDays(6);
        $previousWeekStart = Carbon::today()->subDays(13);
        $previousWeekEnd = Carbon::today()->subDays(7);
        
        // Current week (last 7 days)
        $currentStockIn = StockMovement::where('occurred_at', '>=', $currentWeekStart)
            ->where('movement_type', 'in')
            ->sum('quantity');
            
        $currentStockOut = StockMovement::where('occurred_at', '>=', $currentWeekStart)
            ->where('movement_type', 'out')
            ->sum('quantity');
            
        $currentAdjustments = StockMovement::where('occurred_at', '>=', $currentWeekStart)
            ->where('movement_type', 'adjustment')
            ->count();
        
        // Previous week
        $previousStockIn = StockMovement::whereBetween('occurred_at', [$previousWeekStart, $previousWeekEnd])
            ->where('movement_type', 'in')
            ->sum('quantity');
            
        $previousStockOut = StockMovement::whereBetween('occurred_at', [$previousWeekStart, $previousWeekEnd])
            ->where('movement_type', 'out')
            ->sum('quantity');
            
        $previousAdjustments = StockMovement::whereBetween('occurred_at', [$previousWeekStart, $previousWeekEnd])
            ->where('movement_type', 'adjustment')
            ->count();
        
        // Calculate percentage change
        return [
            'stockIn' => [
                'value' => $currentStockIn,
                'change' => $previousStockIn > 0 ? round((($currentStockIn - $previousStockIn) / $previousStockIn) * 100, 1) : 0,
                'trend' => $currentStockIn >= $previousStockIn ? 'up' : 'down'
            ],
            'stockOut' => [
                'value' => abs($currentStockOut),
                'change' => $previousStockOut > 0 ? round((($currentStockOut - $previousStockOut) / $previousStockOut) * 100, 1) : 0,
                'trend' => abs($currentStockOut) >= abs($previousStockOut) ? 'up' : 'down'
            ],
            'adjustments' => [
                'value' => $currentAdjustments,
                'change' => $previousAdjustments > 0 ? round((($currentAdjustments - $previousAdjustments) / $previousAdjustments) * 100, 1) : 0,
                'trend' => $currentAdjustments >= $previousAdjustments ? 'up' : 'down'
            ]
        ];
    }
    
    /**
     * Get top moving products (last 7 days)
     */
    private function getTopMovingProducts()
    {
        $sevenDaysAgo = Carbon::today()->subDays(6);
        
        return StockMovement::select('product_id', DB::raw('COUNT(*) as movement_count'))
            ->where('occurred_at', '>=', $sevenDaysAgo)
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('movement_count', 'desc')
            ->take(5)
            ->get();
    }
    
    /**
     * Get slow moving products (last 7 days)
     */
    private function getSlowMovingProducts()
    {
        $sevenDaysAgo = Carbon::today()->subDays(6);
        
        // Get products with movements
        $productsWithMovements = StockMovement::select('product_id', DB::raw('COUNT(*) as movement_count'))
            ->where('occurred_at', '>=', $sevenDaysAgo)
            ->groupBy('product_id')
            ->pluck('movement_count', 'product_id');
        
        // Get all active products
        $allProducts = Product::where('is_active', true)
            ->where('current_stock', '>', 0)
            ->get();
        
        // Find products with low movements or no movements
        $slowMoving = $allProducts->map(function ($product) use ($productsWithMovements) {
            $movementCount = $productsWithMovements->get($product->id, 0);
            return [
                'product' => $product,
                'movement_count' => $movementCount
            ];
        })
        ->filter(function ($item) {
            return $item['movement_count'] <= 3; // Products with 3 or fewer movements
        })
        ->sortBy('movement_count')
        ->take(5)
        ->values();
        
        return $slowMoving;
    }
}
