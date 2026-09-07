<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'plan' => $this->plan ? new PlanResource($this->plan) : null,
            "id" => $this->id,
            "start_date" => Carbon::parse($this->start_date)->format('Y-m-d'),
            "end_date" => Carbon::parse($this->end_date)->format('Y-m-d'),
            "status" => $this->status,
            "renew" => $this->renew,
        ];
    }
}
