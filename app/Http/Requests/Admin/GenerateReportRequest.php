<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GenerateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth handled by admin middleware
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'gender' => 'nullable|integer|in:1,2',
            'marital' => 'nullable|integer|in:1,2',
            'baptized' => 'nullable|integer|in:1,2',
            'faith' => 'nullable|integer|in:1,2,3,4',
            'age' => 'nullable|integer|min:1|max:150',
            'skills' => 'nullable|string|max:255',
            'ministries' => 'nullable|string|max:2000',
        ];
    }
}