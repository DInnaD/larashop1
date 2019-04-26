<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'  =>  'required',
            'email' =>  'required|email|unique:users',
            'password'  =>  'required',
        ];
    }
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name'  =>  'User Name',
            'email' =>  'User Email',
            'password'  =>  'User Password',
        ];
    }
    /*
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name'  =>  'The :attribute value is required:input is not between 1:min - 32:max.',
            'email' =>  'The :attribute value is required and unique:input is not between 1:min - 32:max.',
            'password'  =>  'The :attribute value is required:input is not between 1:min - 8:max.',
        ];
    }
}
