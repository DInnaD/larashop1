<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MagazineRequest extends FormRequest
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
            'name' =>'required|min:1|max:32',
            'lenght' => 'required|min:1|max:1024',
            'publisher'   =>  'required|min:1|max:32',
            'year' =>  'required|min:1500|max:2019',
            'number' =>  'required|min:1|max:32',
            'number_per_year' =>  'required|min:1|max:10000000',
            'dimensions'  => 'required|min:1|max:32',
            'price'   => 'required|min:1|max:1000000',
            'sub_price' => 'nullable|min:1|max:100',
            'old_price' => 'nullable|min:1|max:1000000',
            'img' =>  'nullable|image',
            'discont_global' => 'required|min:1|max:100',
            'discont_id' => 'required|min:1|max:100',
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
            'name' => 'Magazine Name',
            'lenght' =>'Magazine Lenght', 
            'publisher'   =>  'Magazine Ppublisher',
            'year' =>  'Magazine Publication Date',
            'number' =>  'Magazine Publication Number',
            'number_per_year' =>  'Magazine Publication Number Per Year',
            'dimensions'  => 'Magazine Dimensions',
            'price'   => 'Magazine Price',
            'sub_price' => 'Subscriber Discont',
            'old_price' => 'Magazine Old Price',
            'img' =>  'Magazine Image',
            'discont_global' => 'Magazine Discont Global',
            'discont_id' => 'Magazine Private Discont',
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
            'name' => 'The :attribute value is required:input is not between 1:min:1 - 32:max.',
            'lenght' => 'The :attribute value is required:input is not between min: - max:1024.', 
            'publisher'   =>  'The :attribute value is required:input is not between min:1 - max:32.',
            'year' =>  'The :attribute value is required:input is not between min:150 - max:2019',
            'number' =>  'The :attribute value is required:input is not between min:1 - max:32.',
            'number_per_year' =>  'The :attribute value is required:input is not between min:1 - max:10000000.',
            'dimensions'  => 'The :attribute value is required :input is not between min:1 - max:32.',
            'price'   => 'The :attribute value is required:input is not between min:1 - max:1000000.',
            'sub_price' => 'The :attribute value is required:input is not between min:1 - max:100.',
            'old_price' => 'The :attribute value is required:input is not between min:1 - max:1000000.',
            'img' =>  'The :attribute value is required:input is not between image.',
            'discont_global' => 'The :attribute value is required:input is not between min:1 - max:100.',
            'discont_id' => 'The :attribute value is required:input is not between min:1 - max:100.',
        ];
    }
}
