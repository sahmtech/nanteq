<?php

namespace App\Traits;

use App\Exceptions\GeneralException;


trait CheckSubscriptionTrait
{
    public function checkSubscription($letter)
    {
        if (! $letter->is_demo) {
            if (auth()->user()?->subscription?->status == 'inactive') {
                throw new GeneralException(__('api.unsubscribed_message'), 402);
            }
        }
    }
}
