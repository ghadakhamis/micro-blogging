<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'email'    => 'required|email:rfc,dns|unique:users,email',
            'username' => 'required|regex:/^\S*$/u|max:250',
            'password' => ['required', 'min:8', Password::min(8)->mixedCase()->numbers()->symbols()],
            'image'    => 'nullable|file|mimes:png,jpg|max:'.config('app.user_image_size'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.regex' => trans('validation.no_spaces', ['attribute' => trans('validation.attributes.username')]),
        ];
    }
}
