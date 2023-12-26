<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestFlate extends FormRequest
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
        return [
            'flate_no' => 'required|numeric|min:1|max:1000',
            'owner_name' => 'required|min:3|max:100',
            'op_balance' => 'nullable|numeric',
            'maintenance_area' => 'required|numeric|min:1|max:10000',
            'builtup_area' => 'nullable|numeric|max:10000',
            'superbuiltup_area' => 'nullable|numeric|max:10000',
      
        ];
    }
}
