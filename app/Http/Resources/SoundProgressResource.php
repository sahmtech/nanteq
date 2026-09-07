<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SoundProgressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success_attempts' => $this->success_attempts,
            'failure_attempts' => $this->failure_attempts,
            'records' => collect($this->records)->map(function ($record) {
                $record['file_path'] = $record['file_path'] ? get_media_url($record['file_path']) : null;
                return $record;
            }),
            'result' => $this->result,
            'status' => $this->status === 'completed' ? 1 : 0,
        ];
    }
}
