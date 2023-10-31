<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;

class RequestAccount extends FormRequest
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
            'name' => 'required|min:3|max:50',
            'group_id' => 'required|numeric',
           
        ];
    }
    public function withValidator($validator)
    {
        $validator->sometimes('email', 'required', function (Fluent $input) {
            return $input->get('group_id') > 2;
        });
        // $validator->sometimes('city_id', 'required', function (Fluent $input) {
        //     return $input->get('group_id') > 2;
        // });

    }
}
