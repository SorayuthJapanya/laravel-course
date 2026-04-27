<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GeneratePromptRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'file',
                'image',
                'mimes:png,jpg,jpeg,gif,webp',
                'max:10240', // 10MB
                'dimensions:min_width=100,min_height=100,max_width=10000,max_height=10000'
            ]
        ];
    }

    public function messages()
    {
        return [
            'image.required'    => 'An image is required.',
            'image.file'        => 'The uploaded file must be a valid file.',
            'image.image'       => 'The file must be an image.',
            'image.mimes'       => 'Only PNG, JPG, JPEG, GIF, and WebP images are allowed.',
            'image.max'         => 'The image must not exceed 10MB.',
            'image.dimensions'  => 'The image dimensions must be between 100x100 and 10000x10000 pixels.',
        ];
    }
}
