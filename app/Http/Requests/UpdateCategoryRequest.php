<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'parent_id' => 'sometimes|integer',
            'is_show' => 'sometimes|integer',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048'
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
            'name.string' => 'Tên danh mục phải là chuỗi',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
            'parent_id.integer' => 'Danh mục cha phải là số nguyên',
            'is_show.integer' => 'Trạng thái hiển thị phải là số nguyên',
            'image.image' => 'Ảnh phải là ảnh',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg',
            'image.max' => 'Ảnh không được vượt quá 2MB'
        ];
    }
}
