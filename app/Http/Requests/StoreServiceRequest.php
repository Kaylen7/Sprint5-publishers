<?php

namespace App\Http\Requests;

use Closure;
use App\Rules\UniqueServiceType;
use App\Rules\ValidServiceLanguage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreServiceRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|text|max:16777215',
            'type' => [
                'required',
                'in:proofreading,translating',
                new UniqueServiceType(auth()->user(), $this->get('user_id', 0))
            ],
            'languages' => [
                'required',
                new ValidServiceLanguage($this->get('type', ''))
            ],
            'user_id' => [
                'nullable',
                function(string $attribute, mixed $value, Closure $fail){
                    if(!auth()->user()->hasRole('admin') && $value){
                        throw new HttpResponseException(response()->json([
                            'message' => 'Unauthorized'
                        ], 403));
                    }
                }
            ]
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
