<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDesignRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'code' => 'max:255|unique:products,code',
            'description' => 'required|string',
            'category' => 'required|exists:categories,id',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main-image' => 'required|integer',
            'is_showcase' => 'required|boolean',
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
            'name.required' => 'Trường tên không được để trống.',
            'name.string' => 'Trường tên phải là chuỗi.',
            'name.max' => 'Trường tên không được vượt quá 255 ký tự.',
            'price.required' => 'Trường giá không được để trống.',
            'price.string' => 'Trường giá phải là chuỗi.',
            'price.max' => 'Trường giá không được vượt quá 255 ký tự.',
            'code.max' => 'Trường mã không được vượt quá 255 ký tự.',
            'code.unique' => 'Mã đã tồn tại.',
            'description.required' => 'Trường mô tả không được để trống.',
            'description.string' => 'Trường mô tả phải là chuỗi.',
            'category.required' => 'Trường danh mục không được để trống.',
            'category.exists' => 'Danh mục đã chọn không tồn tại.',
            'tags.required' => 'Trường thẻ không được để trống.',
            'tags.*.exists' => 'Thẻ đã chọn không tồn tại.',
            'images.*.required' => 'Trường hình ảnh không được để trống.',
            'images.*.image' => 'Trường hình ảnh phải là hình ảnh.',
            'images.*.mimes' => 'Trường hình ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'images.*.max' => 'Dung lượng hình ảnh không được vượt quá 2MB.',
            'main-image.required' => 'Trường hình ảnh chính không được để trống.',
            'main-image.integer' => 'Trường hình ảnh chính phải là số nguyên.',
            'is_showcase.required' => 'Trường hiển thị không được để trống.',
            'is_showcase.boolean' => 'Trường hiển thị phải là boolean.',
        ];
    }
}
