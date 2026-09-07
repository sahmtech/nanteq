<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanCollection;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends BaseController
{

    public function index()
    {
        try {
            return $this->withSuccess(new PlanCollection(Plan::select('id', 'name', 'period', 'periodicity_type', 'price')->get()));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }
}
