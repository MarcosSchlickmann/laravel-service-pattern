<?php

namespace App\Microtech\Requests;
use App\Microtech\Requests\ColaboradorDadosRequest;

class UserRequest extends ColaboradorDadosRequest
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                'email' => [
                    'required',
                    'email',
                    'unique:users,email'
                ],
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    'min:8',
                    'regex:/[aA-zZ]/',
                    'regex:/[0-9]/',
                ],
            ]
        );
    }

    public function messages()
    {
        return [
        ];
    }
}