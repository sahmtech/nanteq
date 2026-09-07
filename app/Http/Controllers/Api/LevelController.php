<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LevelCollection;
use App\Http\Resources\LevelResource;
use App\Models\Letter;
use App\Models\Level;
use App\Traits\CheckSubscriptionTrait;
use Illuminate\Http\Request;

class LevelController extends BaseController
{
    use CheckSubscriptionTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(int $letter_id)
    {
        try{
            $letter = Letter::find($letter_id);
            $this->checkSubscription($letter);
            
            $levels = Level::with('letterProgress')->where('letter_id', $letter_id)->get();
            return $this->withSuccess(new LevelCollection($levels));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try {

            $level = Level::findOrFail($id);
            $this->checkSubscription($level->letter);

            return $this->withSuccess(new LevelResource($level));
        } catch (\Throwable $th) {
            return $this->withError($th->getMessage(), 500);
        }
    }
}
