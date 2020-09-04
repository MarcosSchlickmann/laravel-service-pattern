<?php
namespace App\Microtech\Services;

use App\Core\Exceptions\GenericException;

use App\Microtech\Models\Parametro;
use App\Core\Core;

class ParametroService
{
	public static function register($parametro) {
		$versao_sistema = isset($parametro['versao_sistema']) ? $parametro['versao_sistema'] : Core::getVersion();
		return Parametro::updateOrCreate(
			[ 'versao_sistema' => $versao_sistema ],
			$parametro
		);
	}

	public static function retrieve() {
		$parametro = Parametro::sistemaAtual()->first();
		if(!$parametro){
			return self::register([
				'versao_sistema' => Core::getVersion() 
			]);
		}
		return $parametro;
	}
}
