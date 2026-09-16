<?php

namespace App\Services;

use App\Models\AiPronunciationAttempt;
use App\Models\Letter;
use App\Models\Sound;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PronunciationReviewService
{
    public function record(Sound $sound, Response $response, array $fileMeta = [], ?array $storedUpload = null, ?int $durationMs = null): AiPronunciationAttempt
    {
        $json = $response->json() ?: [];
        $status = (string) ($json['status'] ?? '');
        $aiScore = isset($json['score']) ? (float) $json['score'] : null;
        $score = $this->scoreOutOfFive($status, $aiScore);

        $attempt = AiPronunciationAttempt::create([
            'user_id' => auth()->id(),
            'letter_id' => $sound->letter_id,
            'sound_id' => $sound->id,
            'target' => $json['target'] ?? $sound->spelled_word ?? $sound->written_word,
            'heard' => $json['heard'] ?? null,
            'ai_status' => $status ?: null,
            'ai_score' => $aiScore,
            'score' => $score,
            'technical_log' => $this->technicalLog($json, $fileMeta, $response, $durationMs),
            'audio_path' => $storedUpload['path'] ?? null,
            'audio_url' => $storedUpload['url'] ?? null,
            'request_id' => $json['request_id'] ?? null,
            'stage' => isset($json['stage']) ? (int) $json['stage'] : null,
            'model_type' => $sound->model_type,
            'duration_ms' => $durationMs,
            'http_status' => $response->status(),
            'payload' => $this->compactPayload($json),
        ]);

        $attempt->update([
            'overall_score' => $this->letterOverallScore((int) $sound->letter_id),
        ]);

        $attempt = $attempt->fresh(['letter', 'sound', 'user']);
        $this->appendReviewCsv($attempt);
        $this->notifyReviewWebhook($attempt);

        return $attempt;
    }

    /**
     * @return array{app_overall: float|null, tested_letters: int, total_letters: int, coverage_label: string}
     */
    public function appReviewStats(): array
    {
        $letterOveralls = AiPronunciationAttempt::query()
            ->join('letters', 'letters.id', '=', 'ai_pronunciation_attempts.letter_id')
            ->whereNotNull('ai_pronunciation_attempts.score')
            ->whereNotNull('ai_pronunciation_attempts.letter_id')
            ->groupBy('letters.letter')
            ->selectRaw('letters.letter as letter_key, avg(ai_pronunciation_attempts.score) as letter_overall')
            ->pluck('letter_overall');

        $tested = $letterOveralls->count();
        $total = (int) Letter::query()
            ->whereNotNull('letter')
            ->where('letter', '!=', '')
            ->distinct()
            ->count('letter');

        if ($total === 0) {
            $total = (int) Letter::query()->count();
        }

        $total = max($tested, $total > 0 ? $total : 28);

        $appOverall = $tested === 0
            ? null
            : round((float) $letterOveralls->avg(), 1);

        return [
            'app_overall' => $appOverall,
            'tested_letters' => $tested,
            'total_letters' => $total,
            'coverage_label' => $tested.' / '.$total,
        ];
    }

    public function letterOverallScore(int $letterId): int
    {
        $average = AiPronunciationAttempt::query()
            ->where('letter_id', $letterId)
            ->whereNotNull('score')
            ->avg('score');

        if ($average === null) {
            return 1;
        }

        return max(1, min(5, (int) round((float) $average)));
    }

    public function scoreOutOfFive(string $status, ?float $raw): int
    {
        if ($status === 'correct') {
            return 5;
        }

        if (in_array($status, ['unclear', 'low_quality'], true)) {
            return 2;
        }

        if ($status === 'incorrect') {
            return 2;
        }

        if ($raw !== null && $raw > 0) {
            if ($raw <= 5) {
                return max(1, min(5, (int) round($raw)));
            }

            return max(1, min(5, (int) round($raw / 20)));
        }

        return 1;
    }

    protected function technicalLog(array $json, array $fileMeta, Response $response, ?int $durationMs): string
    {
        $top3 = collect($json['top3'] ?? [])
            ->map(function ($row) {
                if (! is_array($row)) {
                    return (string) $row;
                }

                $word = $row[0] ?? '';
                $prob = isset($row[1]) ? round((float) $row[1], 4) : '';

                return trim($word.' '.$prob);
            })
            ->filter()
            ->implode(', ');

        $quality = $json['audio_quality'] ?? [];

        return implode(' | ', array_filter([
            'status='.($json['status'] ?? 'n/a'),
            'ai_score='.($json['score'] ?? 'n/a'),
            'target='.($json['target'] ?? ''),
            'heard='.($json['heard'] ?? ''),
            'top3='.$top3,
            'quality='.($quality['reason'] ?? 'n/a'),
            'duration_sec='.($quality['duration_sec'] ?? 'n/a'),
            'mime='.($fileMeta['detected_mime'] ?? $fileMeta['client_mime'] ?? 'n/a'),
            'file='.($fileMeta['filename'] ?? 'n/a'),
            'size='.($fileMeta['size_bytes'] ?? 'n/a'),
            'http='.$response->status(),
            'app_ms='.($durationMs ?? 'n/a'),
            'model_ms='.($json['elapsed_ms'] ?? 'n/a'),
            'request_id='.($json['request_id'] ?? 'n/a'),
            'feedback='.($json['feedback'] ?? ''),
        ]));
    }

    protected function compactPayload(array $json): array
    {
        unset($json['debug']);

        return $json;
    }

    protected function appendReviewCsv(AiPronunciationAttempt $attempt): void
    {
        try {
            $disk = Storage::disk('public');
            $path = 'pronunciation-reviews.csv';
            $row = [
                $attempt->created_at?->toDateTimeString(),
                $attempt->letter?->name,
                $attempt->sound?->written_word,
                $attempt->score,
                $attempt->overall_score,
                $attempt->ai_status,
                $attempt->target,
                $attempt->heard,
                $attempt->technical_log,
                $attempt->audio_url,
                $attempt->user?->name,
            ];

            if (! $disk->exists($path)) {
                $disk->put($path, $this->csvLine([
                    'created_at',
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
                ]));
            }

            $disk->append($path, $this->csvLine($row));
        } catch (\Throwable $e) {
            Log::warning('Pronunciation review CSV append failed', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    protected function notifyReviewWebhook(AiPronunciationAttempt $attempt): void
    {
        $url = config('services.ai_model.review_webhook');

        if (blank($url)) {
            return;
        }

        try {
            Http::timeout(4)
                ->acceptJson()
                ->asJson()
                ->post($url, [
                    'created_at' => $attempt->created_at?->toDateTimeString(),
                    'letter' => $attempt->letter?->name,
                    'sound' => $attempt->sound?->written_word,
                    'score' => $attempt->score,
                    'overall_score' => $attempt->overall_score,
                    'status' => $attempt->ai_status,
                    'target' => $attempt->target,
                    'heard' => $attempt->heard,
                    'technical_log' => $attempt->technical_log,
                    'audio_url' => $attempt->audio_url,
                    'user' => $attempt->user?->name,
                ]);
        } catch (\Throwable $e) {
            Log::warning('Pronunciation review webhook failed', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    protected function csvLine(array $columns): string
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $columns);
        rewind($handle);
        $line = rtrim((string) stream_get_contents($handle), "\n");
        fclose($handle);

        return $line;
    }
}
