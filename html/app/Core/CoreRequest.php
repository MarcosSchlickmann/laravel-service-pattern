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
        $data = array();
        if(!empty($this->route()->parameters)) {
            foreach ($this->route()->parameters as $key => $value) $data[$key] = is_string($value) && $value === '' ? null : $value;
        }

        if(isset($this->cpf)) $data['cpf'] = self::removerCaractereEspecial($this->cpf);
        if(isset($this->cep)) $data['cep'] = self::removerCaractereEspecial($this->cep);
        if(isset($this->cnpj)) $data['cnpj'] = self::removerCaractereEspecial($this->cnpj);
        $this->merge($data);

        foreach ($this->container->call([$this, 'rules']) as $field => $rule) {
            $rules_array = (is_array($rule)) ? $rule : explode("|", $rule);
            if (in_array('numeric', $rules_array)) {
                
                if(strpos($field, '.*.') !== false) {
                    $campoArray         = explode('.*.', $field);
                    $parametroArray     = $campoArray[0];
                    $parametroNumeric   = $campoArray[1];
                    $matrizParametro    = $this->all()[$parametroArray];

                    foreach($matrizParametro as $chave => $item) {

                        if(!isset($item[$parametroNumeric])) continue;

                        $item[$parametroNumeric] = self::trataNumeric($item[$parametroNumeric]);
                        $matrizParametro[$chave] = $item;
                    }

                     $this->merge([
                        $parametroArray => $matrizParametro
                    ]);

                    continue;
                }

                $this->merge([
                    $field => self::trataNumeric($this->get($field))
                ]);
            }

        }
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {

            $validator->after(function () {
                
                foreach ($this->container->call([$this, 'rules']) as $field => $rule) {

                    $rules_array        = (is_array($rule)) ? $rule : explode("|", $rule);
                    $possuiDateFormat   = false;
                    $formatoData        = '';

                    array_map(function($a) use (&$possuiDateFormat, &$formatoData) {
                        if(is_string($a)){ 
                            if(strpos($a, 'date_format') !== false) {
                                $possuiDateFormat   = true;
                                $formatoData        = preg_replace('/(.*)date_format:/', '', $a);
                            }
                        }

                    }, $rules_array);

                    if ($possuiDateFormat) {
                        
                        if(strpos($field, '.*.') !== false) {

                            $campoArray         = explode('.*.', $field);
                            $parametroArray     = $campoArray[0];
                            $parametroRegra     = $campoArray[1];
                            $matrizParametro    = $this->all()[$parametroArray];

                            foreach($matrizParametro as $chave => $item) {
           
                                if(!isset($item[$parametroRegra])) continue;

                                $item[$parametroRegra] = self::trataDateFormat($formatoData, $item[$parametroRegra]);
                                $matrizParametro[$chave] = $item;
                            }
        
                             $this->merge([
                                $parametroArray => $matrizParametro
                            ]);
        
                            continue;
                        }

                        if($this->get($field)){
                            $this->merge([
                                $field => Carbon::createFromFormat($formatoData, $this->get($field))->format('Y-m-d H:i:s')
                            ]);     
                        }
                    }
                }

                $this->validations();
            });
        }

        return $validator;
    }

    public function validations()
    {
        //
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

    private function trataDateFormat($dateFormat, $data) {
        return Carbon::createFromFormat($dateFormat, $data)->format('Y-m-d H:i:s');
    }

    public static function removerCaractereEspecial($str)
    {
        $str = strip_tags(trim($str));
        return preg_replace('/[^A-Za-z0-9]/', '', $str);
    }
}