<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email' => [
                'email',
                'nullable',
                Rule::unique('users', 'email')->ignore(auth()->id()),
                'max:250',
            ],
            'name' => [
                'required',
                'string',
                'min:8',
                'max:250',
            ],
            'old_password' => 'required',
            'password' =>  [
                'nullable',
                'confirmed',
                'string',
                'min:8',
                'max:250',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z\d.@])[^@]{8,}$/u'
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false, 'errors' => $validator->errors(),                                   ], Response::HTTP_BAD_REQUEST));
    }
}
