<?php

namespace App\Renault\Requests;

use App\Core\CoreRequest;

class InscricaoRequest extends CoreRequest
{
	public function rules()
	{
		return [
			'lider_cpf' => 'required|regex:/([0-9]){3}\.([0-9]{3})\.([0-9]{3})\-([0-9]{2})/',
			'telefone' => 'required|regex:/\(([0-9]){2}\) ([0-9]{5})\-([0-9]{4})/',
			'email' => 'required|email'
		];
	}
	public function messages()
	{
	    return [
	        "lider_cpf.required" => __("validation.required"),
	        "telefone.required" => __("validation.required"),
	        "email.required" => __("validation.required")
	    ];
	}
}