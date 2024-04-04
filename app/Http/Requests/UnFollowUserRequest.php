<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Validator;

class UnFollowUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

 
    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                /** @var User $authUser */
                $authUser = Auth::user();
                $notFollow = !$authUser->followings()->where('users.id', $this->user->id)->exists();
                if ($notFollow) {
                    $validator->errors()->add('following_id', trans('errors.not_follow'));
                }
            }
        ];
    }
}
