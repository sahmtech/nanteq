<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'completed_sounds_to_success' => $this->completed_sounds_to_success,
            'progress' => $this->letterProgress ? new LevelProgressResource($this->letterProgress) : [
                'progress' => 0,
                'sounds_count' => $this->sounds()->count(),
                'completed_sounds_count' => 0,
                'last_completed_sound_date' => null,
                'status' => 0,
            ],
            'sounds' => $this->sounds ? new SoundCollection($this->sounds) : [],
        ];
    }
}
