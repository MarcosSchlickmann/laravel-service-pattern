<?php

namespace App\Microtech\Requests;
use App\Core\CoreRequest;

class AuthRequest extends CoreRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}