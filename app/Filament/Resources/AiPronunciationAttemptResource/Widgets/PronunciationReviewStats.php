<?php

namespace App\Filament\Resources\AiPronunciationAttemptResource\Widgets;

use App\Filament\Resources\AiPronunciationAttemptResource;
use App\Services\PronunciationReviewService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PronunciationReviewStats extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = app(PronunciationReviewService::class)->appReviewStats();
        $overall = $stats['app_overall'] === null ? '—' : $stats['app_overall'].' / 5';

        return [
            Stat::make(__('dashboard.pronunciation_app_overall'), $overall)
                ->description(__('dashboard.pronunciation_app_overall_hint'))
                ->color('success'),
            Stat::make(__('dashboard.pronunciation_coverage'), $stats['coverage_label'])
                ->description(__('dashboard.pronunciation_coverage_hint'))
                ->color('info'),
        ];
    }

    public static function canView(): bool
    {
        return AiPronunciationAttemptResource::canViewAny();
    }
}
