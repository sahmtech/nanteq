<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SoundCollection;
use App\Models\Level;
use App\Models\Sound;
use App\Traits\CheckSubscriptionTrait;
use Illuminate\Http\Request;

class SoundController extends BaseController
{
    use CheckSubscriptionTrait;
    public function index(int $level_id)
    {
        try {

            $sounds = Sound::where('level_id', $level_id)->get();
            $level = Level::find($level_id);

            $this->checkSubscription($level->letter);

            return $this->withSuccess(new SoundCollection($sounds));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }
}
