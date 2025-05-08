<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlySalesChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Sales Growth';

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    // Set sort order to ensure side by side layout
    protected static ?int $sort = 2;

    // Set a default filter
    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'top',
            ],
            'tooltip' => [
                'enabled' => true,
                'mode' => 'index',
                'intersect' => false,
            ],
        ],
        'animation' => [
            'duration' => 2000,
            'easing' => 'easeInOutQuart',
        ],
        'elements' => [
            'line' => [
                'tension' => 0.3,
            ],
            'point' => [
                'radius' => 4,
                'hoverRadius' => 6,
            ],
        ],
        'scales' => [
            'y' => [
                'grid' => [
                    'display' => true,
                    'drawBorder' => false,
                ],
                'ticks' => [
                    'callback' => 'function(value) { return "₱" + value.toLocaleString(); }',
                ],
                'beginAtZero' => true,
            ],
            'x' => [
                'grid' => [
                    'display' => false,
                    'drawBorder' => false,
                ],
            ],
        ],
    ];

    // Define chart colors
    protected function getColors(): array
    {
        return [
            'Sales This Year' => 'rgb(245, 158, 11)', // Amber
            'Sales Last Year' => 'rgb(156, 163, 175)', // Gray
        ];
    }

    protected function getData(): array
    {
        // Get monthly sales data
        $monthlyData = $this->getMonthlyData();

        return [
            'datasets' => [
                [
                    'label' => 'Sales This Year',
                    'data' => $monthlyData['thisYear'],
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => false,
                    'tension' => 0.3,
                    'pointBackgroundColor' => 'rgb(245, 158, 11)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgb(245, 158, 11)',
                    'pointBorderWidth' => 2,
                    'pointHoverBorderWidth' => 3,
                ],
                [
                    'label' => 'Sales Last Year',
                    'data' => $monthlyData['lastYear'],
                    'borderColor' => 'rgb(156, 163, 175)',
                    'backgroundColor' => 'rgba(156, 163, 175, 0.1)',
                    'fill' => false,
                    'tension' => 0.3,
                    'pointBackgroundColor' => 'rgb(156, 163, 175)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgb(156, 163, 175)',
                    'pointBorderWidth' => 2,
                    'pointHoverBorderWidth' => 3,
                ],
            ],
            'labels' => $monthlyData['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // Generate dummy data for demonstration
    private function getMonthlyData(): array
    {
        $now = Carbon::now();
        $startOfYear = Carbon::now()->startOfYear();
        $endOfNow = Carbon::now()->endOfMonth();

        // Get actual sales data
        $monthlySales = DB::table('sales')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total')
            )
            ->where('created_at', '>=', $startOfYear->copy()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Prepare data arrays
        $months = [];
        $thisYearData = array_fill(0, 6, 0); // First 6 months of this year
        $lastYearData = array_fill(0, 6, 0); // First 6 months of last year

        // Get month names for the first 6 months
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::create(null, $i + 1, 1)->format('M');
            $months[] = $month;
        }

        // Fill in the actual data
        foreach ($monthlySales as $sale) {
            $monthIndex = $sale->month - 1;

            // Only include the first 6 months
            if ($monthIndex >= 6) {
                continue;
            }

            if ($sale->year == $now->year) {
                $thisYearData[$monthIndex] = round($sale->total, 2);
            } elseif ($sale->year == $now->year - 1) {
                $lastYearData[$monthIndex] = round($sale->total, 2);
            }
        }

        // Fill with sample data if no real data exists
        if (array_sum($thisYearData) == 0 && array_sum($lastYearData) == 0) {
            $thisYearData = [8500, 11200, 9800, 15600, 16200, 14200];
            $lastYearData = [7800, 8600, 9100, 12500, 14000, 13300];
        }

        return [
            'labels' => $months,
            'thisYear' => $thisYearData,
            'lastYear' => $lastYearData,
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return 1; // Take up a single column to display side by side
    }
}
