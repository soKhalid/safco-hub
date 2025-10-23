<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $allowedImageTypes = config('media.allowed_image_types', 'jpeg,jpg,png,gif,webp');
        $allowedVideoTypes = config('media.allowed_video_types', 'mp4,webm,mov');
        $allowedDocTypes = config('media.allowed_document_types', 'pdf,doc,docx,ppt,pptx');
        $maxFileSize = config('media.max_file_size', 10240); // Default 10MB in KB

        $allowedExtensions = $allowedImageTypes . ',' . $allowedVideoTypes . ',' . $allowedDocTypes;

        return [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'files' => 'required|array|min:1|max:10',
            'files.*' => [
                'required',
                'file',
                'max:' . $maxFileSize,
                'mimes:' . $allowedExtensions,
            ],
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category does not exist.',
            'title.required' => 'Please enter a title for the media.',
            'title.max' => 'The title cannot exceed 255 characters.',
            'files.required' => 'Please select at least one file to upload.',
            'files.*.max' => 'Each file must not exceed ' . config('media.max_file_size', 10240) . 'KB.',
            'files.*.mimes' => 'Invalid file type. Please upload valid image, video, or document files.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'files.*' => 'file',
        ];
    }
}
