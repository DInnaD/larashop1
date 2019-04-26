<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
    {//not request param
       // $id = (int)$this->route('book');

        return [
            'name' =>'required|min:1|max:32',
            'author_name'   => 'required|min:1|max:32',
            'lenght' => 'required|min:1|max:1024',
            'publisher'   => 'required|min:1|max:64',
            'year' => 'required|min:1500|max:2019',//currentDate()?
            'format' => 'required|min:1|max:32',
            'genre' => 'required|min:1|max:32',
            'dimensions'  => 'required|min:1|max:32',
            'price'   => 'required|min:1|max:1000000',
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
            'name' => 'Book Name',
            'author_name'   => 'Author Name',
            'lenght' => 'Book Lenght',
            'publisher'   => 'Book Publisher',
            'year' => 'Book .',//currentDate()?
            'format' => 'Select Book Format',
            'genre' => 'Book Genre',
            'dimensions'  => 'Book Dimensions',
            'price'   => 'Book Price',
            'old_price' => 'Book Old Price',
            'img' => 'Book Image',
            'discont_global' => 'Book Discont Global',
            'discont_id' => 'Book Private Discont',
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
            'name' => 'The :attribute value is required :input is not between 1:min - 32:max.',
            'author_name'  => 'The :attribute value :input is no more then 32:max.',
            'lenght' => 'The :attribute value is required :input is not  between min:1 - max:1024.',
            'publisher'   => 'The :attribute value is required :input is not between min:1 - max:64.',
            'year' => 'req uired|mi :1500 - max:2019.',//currentDate()?
            'format' => 'The :attribute value is required :input is not between min:1 - max:32.',
            'genre' => 'The :attribute value is required :input is not between min:1 - max:32.',
            'dimensions'  => 'The :attribute value is required :input is not between min:1 - max:32.',
            'price'   => ' required min:1 - max:1000000.',
            'old_price' => 'The :attribute value is not required :input is not between min:1 - max:1000000.',
            'img' =>  'The :attribute value is required :input is not image.',
            'discont_global' => 'The :attribute value is required :input is not between min:1 - max:100.',
            'discont_id' => 'The :attribute value is required :input is not between min:1 - max:100.'
        ];
    }
}
