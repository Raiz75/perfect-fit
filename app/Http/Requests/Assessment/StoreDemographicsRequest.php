<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemographicsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contact' => ['required', 'string', 'max:10'],
            'gender' => ['required', 'in:1,2'],
            'age' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'in:1,2'],
            'baptized' => ['required', 'in:1,2'],
            'timeInFaith' => ['required', 'in:1,2,3,4'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'contact.required' => 'Contact number is required.',
            'contact.max' => 'Contact number must not exceed 10 digits.',
            'gender.required' => 'Please select your gender.',
            'gender.in' => 'Invalid gender selection.',
            'age.required' => 'Age is required.',
            'age.integer' => 'Age must be a number.',
            'age.min' => 'Age must be at least 1.',
            'age.max' => 'Age must not exceed 100.',
            'status.required' => 'Please select your status.',
            'baptized.required' => 'Please select your baptism status.',
            'timeInFaith.required' => 'Please select your time in faith.',
        ];
    }
}
