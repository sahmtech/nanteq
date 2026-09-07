<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LetterCollection;
use App\Http\Resources\LetterProgressResource;
use App\Models\Letter;
use App\Models\LevelProgress;
use App\Models\SoundProgress;

class LetterController extends BaseController
{
    public function index()
    {
        try {
            $user_age_group = auth()->user()->ageGroup?->id;

            $letters = Letter::active()
                ->orderByRaw("age_group_id = ? DESC, id ASC", [$user_age_group])
                ->orderBy('id')
                ->get();

            return $this->withSuccess(new LetterCollection($letters));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }

    public function lettersProgresses()
    {
        try {

            $inProgressLetters = Letter::with('LevelsProgresses')->whereHas('LevelsProgresses', function ($query) {
                $query->where('trainee_id', auth()->user()->id);
            })->get();
            return LetterProgressResource::collection($inProgressLetters);
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }
}
