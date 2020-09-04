<?php

namespace App\Core;

use Illuminate\Support\Facades\Log;
use App\Core\Models\ApiLog;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Carbon;
use Exception;

class Core
{
    public function makeLog($request, $erro = 0)
    {
        $logData = [
            'data' => Carbon::now(), 
            'ip' => self::getRealIpAddr(), 
            'route' => self::getRoute(), 
            'chave' => isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : 'null', 
            'email_corretor' => $request->email_corretor, 
            'request' => json_encode($request->all()), 
            'erro' => $erro, 
            'tempo_resposta' => self::getResponseTime(), 
        ];

        try 
        {
            ApiLog::create($logData);        
        }
        catch (Exception $e)
        {
            abort(500, "Erro ao inserir no banco de dados: ".$e->getMessage());
        }
    }


    public static function makeUuid()
    {
        try {
            return Uuid::uuid4();
        } catch (UnsatisfiedDependencyException $e) {
            self::logError('Caught exception: ' . $e->getMessage());
        }
    }

    public static function getVersion()
    {
        $route = self::getRoute();

        if (is_array($route)) return explode("/", self::getRoute())[0];

        return "v1";
    }

    public function getResponseTime()
    {
        return microtime(true) - LARAVEL_START;
    }

    public static function getRoute()
    {   
        $url = explode("/", request()->path());

        if (in_array('api', $url)) return explode("api/", request()->path())[1]; 
        
        return request()->path();   
    }

    public static function getSuccessCode()
    {
        switch (request()->method()) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
            case 'PATCH':
                return 201;
                break;
            
            case 'GET':
                return 200;
                break;
            
            default: 
                return 200;
                break;
        }
    }

    public function logError($message)
    {
        Log::error('***ERR Reference: ' . get_class($this) . ' Message: '. $message);
    }

    public function logInfo($message)
    {
        Log::info('---INF Reference: ' . get_class($this) . ' Message: '. $message);
    }

    public function logWarn($message)
    {
        Log::info('!!!WRN Reference: ' . get_class($this) . ' Message: '. $message);
    }

    public function logDebug($message)
    {
        Log::debug('???DBG Reference: ' . get_class($this) . ' Message: '. $message);
    }

    public static function getRealIpAddr()
    {
        $ip = '';

        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){

            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    
        }else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
    
            $ip = $_SERVER["REMOTE_ADDR"];
    
        }else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
    
            $ip = $_SERVER["HTTP_CLIENT_IP"];
    
        }
    
        return $ip;
    
    }
}