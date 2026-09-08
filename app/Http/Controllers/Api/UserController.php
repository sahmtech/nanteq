<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CompleteProfileRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\Age;
use App\Models\User;
use App\Services\PatientSpecialistFollowService;
use App\Traits\ImageTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    use ImageTrait;

    public function completeProfile(CompleteProfileRequest $request, PatientSpecialistFollowService $followService)
    {
        try {
            $user = $request->user();
            $this->syncProfile($user, $request, markCompleted: true);
            $followService->applyFromRequest($user, $request);

            return $this->withSuccess(
                $this->profileResource($user),
                __('api.operation_done_successfully')
            );
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }

    public function updateProfile(UpdateProfileRequest $request, PatientSpecialistFollowService $followService)
    {
        try {
            $user = $request->user();
            $this->syncProfile($user, $request, markCompleted: false);
            $followService->applyFromRequest($user, $request);

            return $this->withSuccess(
                $this->profileResource($user),
                __('api.operation_done_successfully')
            );
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }

    public function userDetails()
    {
        try {
            return $this->withSuccess($this->profileResource(auth()->user()));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(User $user)
    {
        try {
            if ($user->trashed()) {
                return $this->withSuccess(message: __('api.This account has been deleted'));
            }
            $user->delete();
            return $this->withSuccess(message: __('api.This account has been deleted'));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }

    public function forceDelete(User $user)
    {
        try {

            if (!$user->trashed()) {
                $user->delete();
                return $this->withSuccess(message: __('api.This account has been deleted'));
            }
            $user->forceDelete();
            return $this->withSuccess(message: __('api.This account has been deleted for ever'));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }

    protected function syncProfile(User $user, FormRequest $request, bool $markCompleted): void
    {
        $validated = $request->validated();
        $payload = [];

        if (isset($validated['age'])) {
            $ageRecord = Age::find($validated['age']);
            $payload['year_of_birth'] = Carbon::now()->format('Y') - $ageRecord->age;
            $payload['age_group_id'] = $ageRecord->age_group_id;
        }

        if (array_key_exists('gender', $validated)) {
            $payload['gender'] = $validated['gender'];
        }

        if (array_key_exists('name', $validated)) {
            $payload['name'] = $validated['name'];
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                $this->deleteImage($user->profile_picture);
            }
            $payload['profile_picture'] = $this->storeImage($validated['profile_picture'], 'images/profile_pictures');
        }

        if ($markCompleted) {
            $payload['profile_completion_status'] = 'completed';
        }

        if ($payload !== []) {
            $user->update($payload);
        }
    }

    protected function profileResource(User $user): UserResource
    {
        $user->loadMissing(['ageGroup', 'subscription', 'followedSpecialist', 'lastProgress']);

        return new UserResource($user);
    }
}
