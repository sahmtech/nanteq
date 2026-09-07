<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SoundProgressResource;
use App\Models\SoundProgress;

class SoundProgressController extends BaseController
{
    public function index(int $sound_id)
    {
        try {

            $rating = SoundProgress::where('trainee_id', auth()->user()->id)->where('sound_id', $sound_id)->first();
            if (!$rating) {
                return $this->withError('هذا الصوت غير موجود', 404);
            }
            $lists = new SoundProgressResource($rating);

            return $this->withSuccess($lists);
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }
}
