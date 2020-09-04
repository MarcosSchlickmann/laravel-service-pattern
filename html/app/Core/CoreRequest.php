<?php

namespace App\Core;

use App\Core\Exceptions\GenericException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Carbon;

class CoreRequest extends FormRequest
{
    protected $api;
    
    public function __construct()
    {
        $handlerVersion = new HandlerVersion();
        $this->api = $handlerVersion->getApi();
        parent::__construct();
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    protected function prepareForValidation()
    {

    }


    protected function failedValidation(Validator $validator) 
    {
        throw new GenericException(__('exception.erroNosDados'), $validator->errors()->all());
    }

    private function trataNumeric($numeric) {
        if(empty($numeric)) return 0;
        if(!is_float($numeric) && strpos($numeric, ',')) $numeric = str_replace(',', ".", str_replace(".", "", $numeric));
        return $numeric;
    }

    public static function removerCaractereEspecial($str)
    {
        $str = strip_tags(trim($str));
        return preg_replace('/[^A-Za-z0-9]/', '', $str);
    }
}