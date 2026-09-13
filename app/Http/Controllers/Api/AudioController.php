<?php

namespace App\Http\Controllers\Api;

use App\Models\Sound;
use App\Services\AudioService;
use App\Traits\CheckSubscriptionTrait;
use Illuminate\Http\Request;

class AudioController extends BaseController
{
    use CheckSubscriptionTrait;

    public function __construct(protected AudioService $audioService)
    {
    }

    public function assignSound(Request $request)
    {
        try {
            $validated = $request->validate([
                'audio' => ['required', 'file', 'mimes:mp4,mp3,wav,aac,m4a'],
                'sound_id' => ['required', 'integer', 'exists:sounds,id'],
            ]);

            // $this->checkSubscription(Sound::find($validated['sound_id'])->letter);

            // return $this->audioService->handle($validated);
            return $this->withSuccess($this->audioService->handle($validated));

        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }
}
