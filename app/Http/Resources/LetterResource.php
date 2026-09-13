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
            'is_demo' => (bool) ($this->is_demo || auth()->user()?->hasUnrestrictedAccess()),
            'image' => $this->image ? get_media_url($this->image) : null,
            'total_levels_count' => $this->levels_count ?? $this->levels()->count(),
            'completed_levels_count' => $this->completed_levels_count ?? $this->LevelsProgresses()->where('trainee_id', auth()->id())->where('status', 'completed')->count(),
        ];
    }
}
