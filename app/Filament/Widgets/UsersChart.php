<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class UsersChart extends ChartWidget
{

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 6;

    public function getHeading(): string
    {
        return __('dashboard.users');
    }

    protected function getData(): array
    {

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.user'),
                    'data' => $this->getUsersCountInYear(),
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

    public static function canView(): bool
    {
        return auth()->user()->can('widget_UsersChart');
    }

    public function getUsersCountInYear()
    {
        $usersByMonth = [];

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::createFromDate(null, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate(null, $month, 1)->endOfMonth();
        
            $usersByMonth[] = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        }
        return $usersByMonth;
    }
}
