<?php

namespace App\Http\Requests\Business\Category;

use Illuminate\Foundation\Http\FormRequest;

class CreateBusinessCategoryRequest extends FormRequest {
    public function rules(): array {
        return [
            'parent_id' => ['nullable'],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }

    public function attributes(): array {
        return [
            'parent_id' => __('Parent Category'),
            'name' => __('Name'),
            'description' => __('Description'),
        ];
    }

    public function authorize(): bool {
        return $this->business->isOwnedBy(auth()->user());
    }
}
