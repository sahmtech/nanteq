<?php

namespace App\Http\Resources;

use App\Models\Level;
use App\Models\LevelProgress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SoundCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(
            fn($sound) => [
                'id' => $sound->id,
                'written_word' => $sound->written_word,
                'attempts_to_success' => $sound->attempts_to_success,
                'success_rate' => $sound->success_rate,
                'letter' => new LetterResource($sound->letter),
                'sound_progress' => new SoundProgressResource($sound->soundProgress),
                'type' => $sound->type,
                'model_type' => $sound->model_type,
                'media' => ['audio' => $sound->audio ? get_media_url($sound->audio) : null, 'xray_video' => $sound->xray_video ? get_media_url($sound->xray_video) : null, 'natural_video' => $sound->natural_video ? get_media_url($sound->natural_video) : null, 'picture' => $sound->picture ? get_media_url($sound->picture) : null]
            ]
        )->toArray();
    }

    private function isLocked($level)
    {
        $previous_level_id = Level::where('id', $level->letterProgress->previous_level_id)->first()?->id;
        if (!$previous_level_id) {
            return false;
        }
        if (LevelProgress::where('level_id', $previous_level_id)->where('trainee_id', auth()->user()->id)->first()?->status == 'completed') {
            return false;
        }
        return true;
    }
}
