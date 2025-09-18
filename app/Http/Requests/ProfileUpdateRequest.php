<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id)
            ],
        ];

        // Add password validation rules only for super_admin
        if (auth()->user()->role === 'super_admin') {
            $rules['current_password'] = ['nullable', 'current_password'];
            $rules['password'] = ['nullable', 'confirmed', Rules\Password::defaults()];
            $rules['password_confirmation'] = ['nullable'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Ism maydoni talab qilinadi.',
            'name.max' => 'Ism 255 ta belgidan oshmasligi kerak.',
            'email.required' => 'Email maydoni talab qilinadi.',
            'email.email' => 'Email manzili noto\'g\'ri formatda.',
            'email.unique' => 'Bu email manzili allaqachon ishlatilmoqda.',
            'current_password.current_password' => 'Joriy parol noto\'g\'ri.',
            'password.min' => 'Parol kamida 8 ta belgidan iborat bo\'lishi kerak.',
            'password.confirmed' => 'Parol tasdiqlash mos kelmaydi.',
        ];
    }
}
