<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nome é um campo obrigatório.',
            'name.max' => 'Nome não pode exceder 255 caracteres.',
            'price.required' => 'Preço é um campo obrigatório.',
            'price.numeric' => 'Preço deve ser um número.',
            'price.min' => 'Preço não pode ser negativo.',
            'stock.required' => 'Estoque é um campo obrigatório.',
            'stock.integer' => 'Estoque deve ser um número inteiro.',
            'stock.min' => 'Estoque não pode ser negativo.',
            'category.required' => 'Categoria é um campo obrigatório.',
            'category.max' => 'Categoria não pode exceder 255 caracteres.',
        ];
    }
}
