<?php

namespace App\Http\Resources;

use App\Models\SoundProgress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LevelCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
         return $this->collection->transform(
            fn ($level) => [
                'id' => $level->id,
                'name' => $level->name,
                'completed_sounds_to_success' => $level->completed_sounds_to_success,
                'letter' => new LetterResource($level->letter),
                'progress' => $this->buildLevelProgress($level),
                'locked' => $this->checkIfLocked($level),
                'sort_order' => $level->sort_order,
            ]
        )->toArray();
    }

    private function checkIfLocked($currentLevel)
    {


        if (auth()->user()?->hasUnrestrictedAccess()) {
            return false;
        }

       if (optional($currentLevel->letter)->is_demo == 1) {
        return false;
    }
        $traineeId = auth()->id();

        $previousLevels = \App\Models\Level::query()
            ->with('sounds:id,level_id')
            ->where('letter_id', $currentLevel->letter_id)
            ->where('sort_order', '<', $currentLevel->sort_order)
            ->get();

        foreach ($previousLevels as $prevLevel) {
            $completedSoundsCount = SoundProgress::where('trainee_id', $traineeId)
                ->whereIn('sound_id', $prevLevel->sounds->pluck('id'))
                ->where('status', 'completed')
                ->count();

             if ($completedSoundsCount < $prevLevel->completed_sounds_to_success) {
                return true; 
            }
        }

        return false; 
    }

    private function buildLevelProgress($level)
    {
        $traineeId = auth()->id();
        $sounds = $level->relationLoaded('sounds')
            ? $level->sounds->pluck('id')
            : $level->sounds()->pluck('id');
        $soundsCount = $sounds->count();

        if ($soundsCount === 0) {
            return $this->emptyProgress();
        }

        $completedSounds = SoundProgress::whereIn('sound_id', $sounds)
            ->where('trainee_id', $traineeId)
            ->where('status', 'completed');

        $completedCount = $completedSounds->count();
        $progress = round(($completedCount / $soundsCount) * 100);

        return [
            'progress' => $progress,
            'sounds_count' => $soundsCount,
            'completed_sounds_count' => $completedCount,
            'last_completed_sound_date' => $completedSounds->latest('updated_at')->value('updated_at'),
            'status' => $completedCount >= $level->completed_sounds_to_success ? 1 : 0,
        ];
    }

    private function emptyProgress()
    {
        return [
            'progress' => 0,
            'sounds_count' => 0,
            'completed_sounds_count' => 0,
            'last_completed_sound_date' => null,
            'status' => 0,
        ];
    }
}