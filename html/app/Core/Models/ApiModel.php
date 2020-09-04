<?php

namespace App\Core\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ApiModel extends Model
{
    protected static function insertHistory($model, $custom_data = [])
    {
        $alteracoes = $model->getChanges();
        $original   = $model->getOriginal();
        $desc = "";
        foreach($alteracoes as $field => $value){
            if(in_array($field, $model->fields_history)){
                $desc .= " O valor do campo '{$field}' foi alterado de '{$original[$field]}' para '{$value}'.";
            }
        }
        
        if(strlen($desc) > 0){
            DB::table($model->table_history)->insert(array_merge($custom_data,[
                'movimento'    => $desc,
                'dt_movimento' => Carbon::now()
            ]));
        }
    }
}
