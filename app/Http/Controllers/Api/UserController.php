<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\Age;
use App\Models\User;
use App\Traits\ImageTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    use ImageTrait;

    public function completeProfile(Request $request)
    {
        $validated = $request->validate([
            'age' => ['required', 'integer', 'exists:ages,id'],
            'profile_picture' => ['nullable', 'image'],
            'gender' => ['required', 'in:male,female'],
            'name' => ['required', 'string']
        ]);
        try {
            $user = auth()->user();

            $age_record = Age::find($validated['age']);
            $year_of_birth = Carbon::now()->format('Y') - $age_record->age;

            if ($request->has('profile_picture')) {
                if ($user->profile_picture) {
                    $this->deleteImage($user->profile_picture);
                }
                $image_path = $this->storeImage($validated['profile_picture'], 'images/profile_pictures');
            }

            $user->update([
                'year_of_birth' => $year_of_birth,
                'age_group_id' => $age_record->age_group_id,
                'profile_picture' => $image_path ?? null,
                'gender' => $validated['gender'],
                'name' => $validated['name'],
                'profile_completion_status' => 'completed',
            ]);
            return $this->withSuccess(message: __('api.operation_done_successfully'));
        } catch (\Throwable $e) {
            return $this->withError($e->getMessage(), 500);
        }
    }


    public function userDetails()
    {
        try {
            $user = auth()->user();
            return $this->withSuccess(new UserResource($user));
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
}
