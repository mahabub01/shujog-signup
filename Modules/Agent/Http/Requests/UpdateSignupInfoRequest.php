<?php

namespace Modules\Agent\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSignupInfoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id'=>'required',
            'name'=>'required|max:255',
            'gender'=>'required|max:255',
            'date_of_birth'=>'required',
            'signup_reference_id'=>'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
