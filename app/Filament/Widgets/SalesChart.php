<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Weekly Sales Overview';
    protected static ?string $pollingInterval = null;
    protected static bool $isLazy = false;
    protected static ?int $sort = 1;
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'animation' => [
                'duration' => 2000,
                'easing' => 'easeOutBounce',
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "₱" + value.toLocaleString(); }',
                    ],
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $sales = $this->getSalesData();

        return [
            'datasets' => [
                [
                    'label' => 'Daily Sales (₱)',
                    'data' => $sales->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(245, 158, 11, 0.6)', // Amber color with opacity
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(245, 158, 11, 0.9)',
                        'rgba(217, 119, 6, 0.9)',  // Darker amber
                        'rgba(180, 83, 9, 0.9)',   // Even darker
                        'rgba(146, 64, 14, 0.9)',  // Darkest amber
                    ],
                    'borderColor' => 'rgba(245, 158, 11, 1)',
                    'borderWidth' => 1,
                    // Add animation effects
                    'hoverBackgroundColor' => 'rgba(245, 158, 11, 1)',
                    'borderRadius' => 8,
                    'maxBarThickness' => 50,
                ],
            ],
            'labels' => $sales->pluck('date_label')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function getSalesData()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $sales = DB::table('sales')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $date = Carbon::parse($item->date);

                return [
                    'date' => $date->format('Y-m-d'),
                    'date_label' => $date->format('D, M j'), // e.g. "Mon, Jan 1"
                    'total' => (float) $item->total,
                ];
            });

        $result = collect();
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $saleForDay = $sales->firstWhere('date', $dateStr);

            if ($saleForDay) {
                $result->push($saleForDay);
            } else {
                $result->push([
                    'date' => $dateStr,
                    'date_label' => $currentDate->format('D, M j'),
                    'total' => 0,
                ]);
            }

            $currentDate->addDay();
        }

        return $result;
    }

    public function getColumnSpan(): int|string|array
    {
        return 1; 
    }
}
