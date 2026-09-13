<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SoundCollection;
use App\Models\Level;
use App\Models\Sound;
use App\Traits\CheckSubscriptionTrait;
use Illuminate\Http\Request;

class SoundController extends BaseController
{
    use CheckSubscriptionTrait;

    public function index(Request $request, ?int $level_id = null)
    {
        try {
            $levelId = $level_id ?: $request->integer('level_id') ?: null;

            if (! $levelId) {
                return $this->withSuccess([]);
            }

            $level = Level::query()->with('letter')->find($levelId);

            if (! $level) {
                return $this->withError(__('api.not found'), 404);
            }

            $this->checkSubscription($level->letter);

            $sounds = Sound::query()
                ->with(['letter', 'soundProgress'])
                ->where('level_id', $levelId)
                ->get();

            return $this->withSuccess(new SoundCollection($sounds));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }
}
