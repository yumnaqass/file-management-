<?php

namespace App\Http\Requests;

use App\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddFileRequest extends FormRequest
{
    use GeneralTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'path' => 'required|file',
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
