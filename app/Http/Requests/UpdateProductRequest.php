<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category' => 'sometimes|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Nome não pode exceder 255 caracteres.',
            'price.numeric' => 'Preço deve ser um número.',
            'price.min' => 'Preço não pode ser negativo.',
            'stock.integer' => 'Estoque deve ser um número inteiro.',
            'stock.min' => 'Estoque não pode ser negativo.',
            'category.max' => 'Categoria não pode exceder 255 caracteres.',
        ];
    }
}
