<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class register extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {   
        
        if($this->id){
        $rules = [
            'name' => 'required|min:3|max:16',
            'email' => 'required|email|unique:users,email,'.$this->id,
            'gender' => 'required',
            'role' => 'required',
        ];
        }else{        
            $rules = [
                'name' => 'required|min:3|max:16',
                'email' => 'required|email|unique:users',
                'gender' => 'required',
                'role' => 'required',
                'password' => 'required|min:8|max:16|same:confirm_password',
                'confirm_password' => 'required|min:8|max:16',
            ];
        }
        return $rules;
    }
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json(['errors' => $validator->errors()], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
