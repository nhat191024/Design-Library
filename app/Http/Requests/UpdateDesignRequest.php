<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignRequest extends FormRequest
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
            'price' => 'sometimes|integer',
            'code' => 'sometimes|string|max:255|unique:products,code',
            'description' => 'sometimes|string',
            'category' => 'sometimes|exists:categories,id',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main-image' => 'sometimes|integer',
            'is_showcase' => 'sometimes|boolean',
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
            'name.string' => 'Trường tên phải là chuỗi.',
            'name.max' => 'Trường tên không được vượt quá 255 ký tự.',
            'price.integer' => 'Trường giá phải là số nguyên.',
            'code.string' => 'Trường mã phải là chuỗi.',
            'code.max' => 'Trường mã không được vượt quá 255 ký tự.',
            'code.unique' => 'Mã đã tồn tại.',
            'description.string' => 'Trường mô tả phải là chuỗi.',
            'category.exists' => 'Danh mục đã chọn không tồn tại.',
            'tags.*.exists' => 'Thẻ đã chọn không tồn tại.',
            'images.*.image' => 'Trường hình ảnh phải là hình ảnh.',
            'images.*.mimes' => 'Trường hình ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'images.*.max' => 'Dung lượng hình ảnh không được vượt quá 2MB.',
            'main-image.integer' => 'Trường hình ảnh chính phải là số nguyên.',
            'is_showcase.boolean' => 'Trường hiển thị phải là boolean.',
        ];
    }
}
