<?php

namespace App\Filament\Resources\AiPronunciationAttemptResource\Pages;

use App\Filament\Resources\AiPronunciationAttemptResource;
use App\Models\AiPronunciationAttempt;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListAiPronunciationAttempts extends ListRecords
{
    protected static string $resource = AiPronunciationAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label(__('dashboard.pronunciation_export'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => $this->exportCsv()),
        ];
    }

    protected function exportCsv(): StreamedResponse
    {
        $filename = 'pronunciation-reviews-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'letter',
                'sound',
                'score',
                'overall_score',
                'status',
                'target',
                'heard',
                'technical_log',
                'audio_url',
                'user',
                'created_at',
            ]);

            AiPronunciationAttempt::query()
                ->with(['letter', 'sound', 'user'])
                ->orderByDesc('id')
                ->chunk(200, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->letter?->name,
                            $row->sound?->written_word,
                            $row->score,
                            $row->overall_score,
                            $row->ai_status,
                            $row->target,
                            $row->heard,
                            $row->technical_log,
                            $row->audio_url,
                            $row->user?->name,
                            optional($row->created_at)?->toDateTimeString(),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
