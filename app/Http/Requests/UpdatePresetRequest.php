<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePresetRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'color' => ['required', 'hex_color'],
            'tags' => ['required', 'array'],
            'tags.*.name' => ['required', 'string', 'max:12'],
            'tags.*.color' => ['required', 'hex_color'],
            'start.headline' => ['required', 'string', 'max:100'],
            'start.title' => ['required', 'string', 'max:100'],
            'break.headline' => ['required', 'string', 'max:100'],
            'break.title' => ['required', 'string', 'max:100'],
            'end.headline' => ['required', 'string', 'max:100'],
            'end.title' => ['required', 'string', 'max:100'],
            'end.description' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // name
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not be greater than 100 characters.',
            // color
            'color.required' => 'The color is required.',
            'color.hex_color' => 'The color must be a valid hexadecimal color.',
            // tags
            'tags.required' => 'The tags are required.',
            'tags.array' => 'The tags must be an array.',
            // tags.*
            'tags.*.name.required' => 'The tag name is required.',
            'tags.*.name.max' => 'The tag name must not be greater than 12 characters.',
            'tags.*.color.required' => 'The tag color is required.',
            'tags.*.color.hex_color' => 'The tag color must be a valid hexadecimal color.',
            // start.headline
            'start.headline.required' => 'The headline is required.',
            'start.headline.string' => 'The headline must be a string.',
            'start.headline.max' => 'The headline must not be greater than 100 characters.',
            // start.title
            'start.title.required' => 'The title is required.',
            'start.title.string' => 'The title must be a string.',
            'start.title.max' => 'The title must not be greater than 100 characters.',
            // break.headline
            'break.headline.required' => 'The headline is required.',
            'break.headline.string' => 'The headline must be a string.',
            'break.headline.max' => 'The headline must not be greater than 100 characters.',
            // break.title
            'break.title.required' => 'The title is required.',
            'break.title.string' => 'The title must be a string.',
            'break.title.max' => 'The title must not be greater than 100 characters.',
            // end.headline
            'end.headline.required' => 'The headline is required.',
            'end.headline.string' => 'The headline must be a string.',
            'end.headline.max' => 'The headline must not be greater than 100 characters.',
            // end.title
            'end.title.required' => 'The title is required.',
            'end.title.string' => 'The title must be a string.',
            'end.title.max' => 'The title must not be greater than 100 characters.',
            // end.description
            'end.description.required' => 'The description is required.',
            'end.description.string' => 'The description must be a string.',
            'end.description.max' => 'The description must not be greater than 255 characters.',
        ];
    }
}
