<?php

namespace App\Http\Requests\Business\AssociatedProduct;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssociatedProductRequest extends FormRequest {

    public function rules(): array {
        return [
            'business_id' => ['required'],
            'category_id' => ['nullable'],
            'product_id' => ['required'],
        ];
    }

    public function authorize(): bool {
        return $this->business->isOwnedBy(auth()->user());
    }
}