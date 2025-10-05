<?php

namespace SkPro\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use SkPro\Exception\ApiException;

abstract class ApiRequest extends FormRequest implements ApiRequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ApiException([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ]
        );
    }
}
