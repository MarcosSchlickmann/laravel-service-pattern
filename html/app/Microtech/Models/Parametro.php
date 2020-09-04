<?php

namespace App\Microtech\Models;

use App\Core\Models\ApiModel;
use Spatie\Activitylog\Traits\LogsActivity;

use App\Core\Core;

class Parametro extends ApiModel
{
    use LogsActivity;
    protected $guarded = [];

    protected static $logUnguarded = true;

    protected $casts = [
		'data_limite_inscricao_equipe' => 'date',
		'data_limite_edicao_equipe' => 'date',
		'data_limite_avaliacao_financeira' => 'date',
    ];

    protected $hidden = [
    	'id',
    	'versao_sistema'
    ];

    public static function sistemaAtual(){
    	return self::where('versao_sistema', Core::getVersion());
    }
}
