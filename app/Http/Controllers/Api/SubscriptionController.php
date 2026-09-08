<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SubscriptionResource;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends BaseController
{
    public function subscribe(Request $request)
    {
        try {

            $validated = $request->validate([
                'plan_id' => ['required', 'integer', 'exists:plans,id'],
                'coupon' => ['nullable', 'exists:table,column']
            ]);
            $user = auth()->user();
            if ($user->subscription && $user->subscription->status === 'active') {
                return $this->withError(__('api.already_subscribed'), 403);
            }

            $plan = Plan::find($validated['plan_id']);
            $isSpecialist = $user->hasRole('specialist');

            if ((bool) $plan->is_for_specialists !== $isSpecialist) {
                return $this->withError(__('api.plan_not_available'), 403);
            }

            $endDate = Carbon::now();
            if ($plan->periodicity_type === 'month') {
                $endDate = $endDate->addMonths($plan->period);
            } elseif ($plan->periodicity_type === 'year') {
                $endDate = $endDate->addYears($plan->period);
            } else {
                $endDate = $endDate->addDays($plan->period);
            }

            $subscription = $user->subscription()->create([
                'plan_id' => (int) $validated['plan_id'],
                'start_date' => Carbon::now(),
                'end_date' => $endDate,
                'status' => 'active',
            ]);

            return $this->withSuccess([
                'subscription' => new SubscriptionResource($subscription),
            ], __('api.subscribed_successfully'));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }

    public function currentSubscription()
    {
        try {

            $user = auth()->user();
            if ($user->subscription && $user->subscription->status === 'active') {
                return new SubscriptionResource($user->subscription);
            } else {
                return $this->withSuccess(message: __('api.no_subscription'));
            }
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }
}
