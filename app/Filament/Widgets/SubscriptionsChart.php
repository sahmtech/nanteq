<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SubscriptionsChart extends ChartWidget
{
    public function getHeading(): string
    {
        return __('dashboard.subscriptions');
    }

    protected int|string|array $columnSpan = 6;

    protected static ?int $sort = 3;

    protected function getData(): array
    {

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.subscription'),
                    'data' => $this->getSubscriptionsCountInYear(),
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getSubscriptionsCountInYear()
    {
        $subscriptionsByMonth = [];

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::createFromDate(null, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate(null, $month, 1)->endOfMonth();
        
            $subscriptionsByMonth[] = Subscription::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        }
        return $subscriptionsByMonth;
    }
}
