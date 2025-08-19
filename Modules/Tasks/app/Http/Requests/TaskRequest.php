<?php

namespace Modules\Tasks\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'what' => 'required|string|max:65535',
            'source' => 'required|string|max:255',
            'action' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'category_ii' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'comments' => 'nullable|string|max:65535',
            'status' => 'required|in:pending,in_progress,completed,cancelled,on_hold',
            'is_recurring' => 'boolean',
            'recurring_type' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'what.required' => 'The task description is required.',
            'what.max' => 'The task description may not be greater than 65535 characters.',
            'source.required' => 'The task source is required.',
            'source.max' => 'The source may not be greater than 255 characters.',
            'action.required' => 'The action is required.',
            'action.max' => 'The action may not be greater than 255 characters.',
            'type.required' => 'The task type is required.',
            'type.max' => 'The type may not be greater than 255 characters.',
            'category.required' => 'The category is required.',
            'category.max' => 'The category may not be greater than 255 characters.',
            'category_ii.max' => 'The secondary category may not be greater than 255 characters.',
            'priority.required' => 'The priority is required.',
            'priority.in' => 'The selected priority is invalid. Must be one of: low, medium, high, urgent.',
            'comments.max' => 'The comments may not be greater than 65535 characters.',
            'status.required' => 'The status is required.',
            'status.in' => 'The selected status is invalid. Must be one of: pending, in_progress, completed, cancelled, on_hold.',
            'is_recurring.boolean' => 'The recurring field must be true or false.',
            'recurring_type.max' => 'The recurring type may not be greater than 255 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'what' => 'task description',
            'category_ii' => 'secondary category',
            'is_recurring' => 'recurring',
            'recurring_type' => 'recurring type',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // If is_recurring is true, recurring_type should be provided
            if ($this->boolean('is_recurring') && empty($this->recurring_type)) {
                $validator->errors()->add('recurring_type', 'The recurring type is required when task is set as recurring.');
            }
        });
    }
}