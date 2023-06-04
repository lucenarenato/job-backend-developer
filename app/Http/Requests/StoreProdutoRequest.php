<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreProdutoRequest extends FormRequest
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
            'name' => 'string|required',
            'price' => 'required',
            'description' => 'string|required',
            'category' => 'string|required',
            'image_url' => 'url'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo Name é obrigatório!',
            'price.required' => 'O campo Price é obrigatório!',
            'description.required' => 'O campo Description é obrigatório!',
            'category.required' => 'O campo Category é obrigatório!'
        ];
    }

    /**
     * Irá retornar os erros da validação caso não passe na validação
     * @param Validator $validator
     */
    public function failedValidation(Validator $validator)
    {
        //write your bussiness logic here otherwise it will give same old JSON response
        throw new HttpResponseException(
            response()->json(
                [
                    'error' => true,
                    'errors' => [
                        'validators' => $validator->errors()
                    ],
                    'model' => array(),
                    'collection' => array(),
                    'data' => array()
                ],
                409
            )
        );
    }
}
