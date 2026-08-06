<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveStudentAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSiswa();
    }

    public function rules(): array
    {
        return [
            'question_id' => 'required|exists:questions,id',
            'selected_option' => 'nullable|in:a,b,c,d,e',
            'is_flagged' => 'boolean',
        ];
    }
}
