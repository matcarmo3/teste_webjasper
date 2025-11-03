<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' => 'É necessário informar os produtos do pedido.',
            'products.*.id.exists' => 'Um ou mais produtos não foram encontrados.',
            'products.*.quantity.min' => 'A quantidade deve ser pelo menos 1.',
        ];
    }
}
