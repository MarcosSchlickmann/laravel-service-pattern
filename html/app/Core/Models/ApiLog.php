<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $table = 'api_log';

    protected $fillable = [
        'data', 
        'ip', 
        'route', 
        'chave', 
        'email_corretor', 
        'request', 
        'erro', 
        'tempo_resposta', 
    ];

    public $timestamps = false;
}
