<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'price' => ['required', 'integer', 'min:0'],
            'discount_percent' => ['integer', 'between:1,100', 'nullable'],
            'stock' => ['integer', 'required', 'min:0'],
            'image' => ['string', 'nullable'],
            'brand_id' => ['integer', 'required', 'exists:brands,id'],
            'category_id' => ['integer', 'required', 'exists:categories,id'],
            'color_ids' => ['array', 'required', 'min:1'],
            'color_ids.*' => ['integer', 'distinct', 'exists:colors,id']
        ];
    }
}
