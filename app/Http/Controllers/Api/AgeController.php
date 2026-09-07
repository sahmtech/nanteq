<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgeCollection;
use App\Models\Age;


class AgeController extends BaseController
{
    public function index()
    {
        try {
            return $this->withSuccess(new AgeCollection(Age::select('id', 'age')->get()));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }
}
