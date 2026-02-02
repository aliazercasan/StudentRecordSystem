<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'student_id' => 'required|unique:students|alpha_num|min:5|max:20',
            'full_name' => 'required|string|regex:/^[a-zA-Z\s]+$/|min:2|max:100',
            'course' => 'required|string|regex:/^[a-zA-Z\s]+$/|min:2|max:100',
            'year_level' => 'required|integer|between:1,6',
            'contact_number' => 'nullable|numeric|digits_between:10,15',
            'address' => 'nullable|string|min:5|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,gif|max:2048'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'The student ID is required.',
            'student_id.unique' => 'This student ID is already in use.',
            'student_id.alpha_num' => 'The student ID must contain only letters and numbers.',
            'student_id.min' => 'The student ID must be at least 5 characters.',
            'student_id.max' => 'The student ID must not exceed 20 characters.',
            'full_name.required' => 'The full name is required.',
            'full_name.regex' => 'The full name must contain only letters and spaces.',
            'full_name.min' => 'The full name must be at least 2 characters.',
            'full_name.max' => 'The full name must not exceed 100 characters.',
            'course.required' => 'The course is required.',
            'course.regex' => 'The course must contain only letters and spaces.',
            'course.min' => 'The course must be at least 2 characters.',
            'course.max' => 'The course must not exceed 100 characters.',
            'year_level.required' => 'The year level is required.',
            'year_level.integer' => 'The year level must be a number.',
            'year_level.between' => 'The year level must be between 1 and 6.',
            'contact_number.numeric' => 'The contact number must contain only numbers.',
            'contact_number.digits_between' => 'The contact number must be between 10 and 15 digits.',
            'address.min' => 'The address must be at least 5 characters.',
            'address.max' => 'The address must not exceed 255 characters.',
            'photo.image' => 'The file must be an image.',
            'photo.mimes' => 'The photo must be a JPEG, PNG, or GIF file.',
            'photo.max' => 'The photo must not exceed 2MB.',
        ];
    }
}
