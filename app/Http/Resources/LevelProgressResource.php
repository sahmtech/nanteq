<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LevelProgressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'progress' => $this->progress,
            'sounds_count' => $this->sounds_count,
            'completed_sounds_count' => $this->completed_sounds_count,
            'last_completed_sound_date' => $this->last_completed_sound_date,
            'status' => $this->status === 'completed' ? 1 : 0,
        ];
    }
}
