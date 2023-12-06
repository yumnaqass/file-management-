<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FileOperationRequest extends FormRequest
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
           'file_ids' => 'required|array' ,
           'group_id' => 'required'
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


