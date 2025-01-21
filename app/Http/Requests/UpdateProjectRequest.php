<?php

namespace App\Http\Requests;

use App\Rules\UniqueProject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->hasAny(['owner_id']) && !$this->user()->hasRole('admin')){
            throw new HttpResponseException(response()->json([
                'error' => 'Unauthorized',
            ], 403));
            
        }
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
            'name' => ['max:255', new UniqueProject(auth()->id())],
            'description' => 'max:16777215',
            'num_chars' => 'integer|min:1',
            'owner_id' => 'exists:users,id',
            'start_date' => 'date|after:today'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if ($this->toArray() === []) {
            throw new HttpResponseException(response()->json([
                'message' => 'No content',
            ], 402));
        };

        throw new HttpResponseException(response()->json([
            'message' => 'Validation Failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
