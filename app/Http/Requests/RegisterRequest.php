<?php

namespace App\Http\Requests;

use App\Traits\GeneralTrait;
use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    use GeneralTrait;
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
            'name' => 'required|string',
            'email' => 'required|string|max:100|unique:users',
            'password' => 'required|min:8',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            throw new HttpResponseException($this->returnValidationError($code, $validator));
        }
    }

}
