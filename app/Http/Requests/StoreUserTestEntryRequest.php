<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\TestPanel;
use App\Models\Marker;

class StoreUserTestEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // A logged-in user can submit their own test entries.
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'test_panel_id' => [
                'required',
                'integer',
                Rule::exists(TestPanel::class, 'id'), // Ensure test_panel_id exists in the test_panels table
            ],
            'test_date' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:today', // Cannot submit tests from the future
            ],
            'marker_values' => [
                'required',
                'array',
                'min:1', // Must have at least one marker value
            ],
            'marker_values.*.marker_id' => [
                'required',
                'integer',
                Rule::exists(Marker::class, 'id'), // Ensure marker_id exists in the markers table
            ],
            'marker_values.*.value' => [
                'required',
                'numeric',
                'min:0', // Marker values should generally be non-negative
                'max:10000', // Prevent unusually large or malicious values
            ],
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'test_panel_id.required' => 'A test panel is required.',
            'test_panel_id.exists' => 'The selected test panel is invalid.',
            'test_date.required' => 'The test date is required.',
            'test_date.date_format' => 'The test date must be in YYYY-MM-DD format.',
            'test_date.before_or_equal' => 'The test date cannot be in the future.',
            'marker_values.required' => 'At least one marker value is required.',
            'marker_values.array' => 'Marker values must be an array.',
            'marker_values.min' => 'Please provide at least one marker value.',
            'marker_values.*.marker_id.required' => 'Each marker value must specify a marker ID.',
            'marker_values.*.marker_id.exists' => 'One or more provided marker IDs are invalid.',
            'marker_values.*.value.required' => 'Each marker value must have a result.',
            'marker_values.*.value.numeric' => 'Marker results must be numbers.',
            'marker_values.*.value.min' => 'Marker results cannot be negative.',
            'marker_values.*.value.max' => 'Marker results are unusually high. Please check.',
        ];
    }

    /**
     * Optionally prepare the data for validation.
     * Use this if you need to manipulate input before validation,
     * e.g., setting `user_id` or `test_panel_id` from route params.
     */
    protected function prepareForValidation(): void
    {
        // For 'user_id', we'll rely on `auth()->user()->id` in the controller store method.
        // No need to set it here from the request.
    }
}