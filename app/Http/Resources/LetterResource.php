<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LetterResource extends JsonResource
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
            'letter' => $this->letter,
            'is_demo' => $this->is_demo,
            'image' => $this->image ? get_media_url($this->image) : null,
            'total_levels_count' => $this->levels()->count(),
            'completed_levels_count' => $this->LevelsProgresses()->where('trainee_id', auth()->user()->id)->where('status', 'completed')->count() ?? 0,
        ];
    }
}
