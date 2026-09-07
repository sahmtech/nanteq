<?php

namespace App\Http\Resources;

use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $last_progress = Letter::where('id', $this->lastProgress?->letter_id)->with('LevelsProgresses')->whereHas('LevelsProgresses', function($query) {
            $query->where('trainee_id', auth()->user()->id);
        })->latest()->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_picture' => get_media_url($this->profile_picture),
            'gender' => $this->gender,
            'age_group' => $this->ageGroup?->name,
            'year_of_birth' => $this->year_of_birth,
            'profile_completion_status' => $this->profile_completion_status,
            'phone_code' => $this->phone_code,
            'phone_number' => $this->phone_number,
            'last_progress' =>  new LetterProgressResource($last_progress),
            'has_subscription' => $this->subscription && $this->subscription->status === 'active' ? true : false,
        ];
    }
}
