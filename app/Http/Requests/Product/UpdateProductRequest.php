<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->id === $this->route('product')->seller_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'sometimes'],
            'description' => ['string', 'nullable', 'sometimes'],
            'price' => ['required', 'integer', 'min:0', 'sometimes'],
            'discount_percent' => ['integer', 'between:1,100', 'nullable', 'sometimes'],
            'stock' => ['integer', 'required', 'min:0', 'sometimes'],
            'image' => ['string', 'nullable', 'sometimes'],
            'brand_id' => ['integer', 'required', 'exists:brands,id', 'sometimes'],
            'category_id' => ['integer', 'required', 'exists:categories,id', 'sometimes'],
            'color_ids' => ['array', 'required', 'min:1', 'sometimes'],
            'color_ids.*' => ['integer', 'distinct', 'exists:colors,id', 'sometimes']
        ];
    }
}
