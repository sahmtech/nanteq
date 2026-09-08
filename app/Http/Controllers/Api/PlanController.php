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
            $isSpecialist = (bool) auth()->user()?->hasRole('specialist');

            $plans = Plan::query()
                ->select('id', 'name', 'period', 'periodicity_type', 'price', 'is_for_specialists')
                ->where('is_for_specialists', $isSpecialist)
                ->get();

            return $this->withSuccess(new PlanCollection($plans));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }
}
