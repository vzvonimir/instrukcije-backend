<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:services,id,deleted_at,NULL',
            'price' => 'required|max:1000',
            'description' => 'required|string',
            'subject_id' => 'required|integer|exists:subjects,id',
            'category_id' => 'required|integer|exists:categories,id',
        ];
    }
}
