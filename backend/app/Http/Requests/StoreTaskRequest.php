<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'project_id' => 'exists:projects,id',
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'priority' => 'in:low,medium,high',
            'status' => 'in:todo,in_progress,done',
        ];
    }
}
