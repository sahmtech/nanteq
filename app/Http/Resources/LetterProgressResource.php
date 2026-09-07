<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LetterProgressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $letter_progress = $this->levels()->count() > 0 ? 
        round(($this->LevelsProgresses()->where('trainee_id', auth()->user()->id)->where('status', 'completed')->count() / $this->levels()->count()) * 100) : 0;
    
        return [
            'letter' => new LetterResource($this),
            'letter_progress' => $letter_progress,
            'status' => $letter_progress == 100 ? __('api.completed') : __('api.not_completed'),
        ];
    }
}
