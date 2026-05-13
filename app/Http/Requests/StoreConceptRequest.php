<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConceptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('status')) {
            $this->merge(['status' => 'to_review']);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'explanation' => ['required', 'string'],
            'difficulty' => ['required', 'in:junior,mid,senior'],
            'status' => ['required', 'in:to_review,in_progress,mastered'],
        ];
    }
}