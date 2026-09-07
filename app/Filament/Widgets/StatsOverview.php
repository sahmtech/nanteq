<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 12;

    public function getHeading(): string
    {
        return __('dashboard.states');
    }


    protected function getStats(): array
    {
        return [
            // Stat::make(__('dashboard.The number of users'), User::count())
            //     ->icon('heroicon-o-users')
            //     ->color('success'),
            // Stat::make(__('dashboard.The number of trainees'), Trainee::count())
            //     ->icon('heroicon-o-users')
            //     ->color('success'),
            Stat::make(__('dashboard.users_count'), User::count())
                ->icon('heroicon-o-users')
                ->description(__('dashboard.total_users'))
                ->color('success'),
            Stat::make(__('dashboard.subscriptions_count'), Subscription::where('status', 'active')->count())
                ->icon('heroicon-o-credit-card')
                ->description(__('dashboard.total_subscriptions'))
                ->color('success'),
            Stat::make(__('dashboard.new_users'), User::whereBetween('created_at', [now()->subWeeks(4), now()])->count())
                ->icon('heroicon-o-users')
                ->description(__('dashboard.within_month'))
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->chart($this->getModelCountBetweenWeeks(User::class, 4))
                ->color('primary'),
            Stat::make(__('dashboard.new_subscriptions'), Subscription::where('status', 'active')->whereBetween('start_date', [now()->subWeeks(4), now()])->count())
                ->icon('heroicon-o-credit-card')
                ->description(__('dashboard.within_month'))
                ->descriptionIcon('heroicon-o-credit-card', IconPosition::Before)
                ->chart($this->getModelCountBetweenWeeks(Subscription::class, 4))
                ->color('primary'),
            // Stat::make(__('dashboard.new_tranees'), Trainee::whereBetween('created_at', [now()->subWeeks(4), now()])->count())
            //     ->icon('heroicon-o-users')
            //     ->description(__('dashboard.within_month'))
            //     ->descriptionIcon('heroicon-o-users', IconPosition::Before)
            //     ->chart($this->getModelCountBetweenWeeks(Trainee::class, 4))
            //     ->color('primary'),
        ];
    }

    public function getModelCountBetweenWeeks($model, $weeks)
    {
        $modelByWeek = [];

        $now = now();
        for ($i = $weeks; $i > 0; $i--) {

            $startOfWeek = $now->copy()->subWeeks($i);
            $endOfWeek = $now->copy()->subWeeks($i - 1);

            $modelByWeek[] = $model::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        }
        return $modelByWeek;
    }

    public static function canView(): bool
    {
        return auth()->user()->can('widget_StatsOverview');
    }

}
