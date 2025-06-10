<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * These are exactly the same rules you would have in $request->validate([...]) 
     * inside your TaskController::store() method.
     */
    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'category_id'  => 'required|exists:categories,id',
            'priority_id'  => 'required|exists:priorities,id',
            'status_id'    => 'required|exists:statuses,id',
            'due_date'     => 'nullable|date|after_or_equal:today',
            'assignees'    => 'nullable|array',
            'assignees.*'  => 'exists:users,id',
        ];
    }
}
