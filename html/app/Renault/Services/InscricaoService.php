<?php
namespace App\Renault\Services;

use App\Core\Exceptions\GenericException;
use App\Renault\Models\Inscricao;
use Illuminate\Support\Facades\DB;

class InscricaoService
{
	public static function all()
	{
		return Inscricao::all();
	}

	public static function specific($id)
	{
		$inscricao = Inscricao::where('id', $id)->first();

		if(!$inscricao) throw new GenericException(__('inscricao.naoEncontrado'));
		
		return $inscricao;
	}

	public static function create($request)
	{
		$inscricao = Inscricao::create($request);
		return $inscricao->fresh();
	}

	public static function edit($request)
	{
		$inscricao = Inscricao::firstOrFail($request['id']);
		$inscricao->fill($request);

		DB::transaction(function () use ($inscricao) {
			$inscricao->save();
		});

		return $inscricao->fresh();
	}
}