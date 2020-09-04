<?php

namespace App\Http\Controllers;

use App\Core\CoreController;

use App\Renault\Models\Parametro;
use Illuminate\Http\Request;

use App\Renault\Services\ParametroService;

use App\Renault\Requests\ParametroRequest;

class ParametroController extends CoreController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Get(
     *      path="/api/v1/parametros",
     *      operationId="show",
     *      tags={"Parametros"},
     *      summary="Exibir parâmetros do sistema",
     *      description="Exibe os parâmetros de bloqueio do sistema e de periodicidade dos logs em meses",
     *      security={
     *          {"api_token": {}},
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *       )
     *)
     *
     */
    public function show()
    {
        return $this->api->responseOk(
            $obj = ParametroService::retrieve(),
            $message = "Sucesso na requisição.",
            $successCode = 200
        );
    }

    /**
     * @OA\Post(
     *      path="/api/v1/parametros",
     *      operationId="update",
     *      tags={"Parametros"},
     *      summary="Altera parâmetros do sistema",
     *      description="Alteração para definir se sistema está bloqueado e a periodicidade dos logs em meses",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=403, description="Forbidden"),
     *      security={
     *          {"api_token": {}},
     *      },
     *      @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="sistema_bloqueado",
     *                     description="(nullable) Bloqueia ou libera o sistema",
     *                     type="bool",
     *                 ),
     *                 @OA\Property(
     *                     property="periodo_log",
     *                     description="(nullable) Período, em meses, de guarda do log",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="data_limite_inscricao_equipe",
     *                     description="data limite para inscricao equipe (Y-m-d)",
     *                     type="date",
     *                 ),
     *                 @OA\Property(
     *                     property="data_limite_edicao_equipe",
     *                     description="data limite para edicao equipe (Y-m-d)",
     *                     type="date",
     *                 ),
     *                 @OA\Property(
     *                     property="data_limite_avaliacao_financeira",
     *                     description="data limite para avaliacao financeira (Y-m-d)",
     *                     type="date",
     *                 ),
     
     *             )
     *         )
     *      )
     *)
     *
     * Return whether the CPF is valid or not
     */
    public function update(ParametroRequest $request)
    {
        $parametro = ParametroService::register($request->only(
            'sistema_bloqueado',
            'periodo_log',
            'data_limite_inscricao_equipe',
            'data_limite_edicao_equipe',
            'data_limite_avaliacao_financeira',
        ));

        if(!$parametro){
            return $this->api->responseFail(
                $obj = [ 'Não foi possível alterar os parâmetros, tente novamente mais tarde.' ],
                $message = 'Erro ao alterar parâmetros.'
            );
        }

        return $this->api->responseOk(
            $obj = $parametro,
            $message = "Sucesso ao alterar parâmetros.",
            $successCode = 200
        );
    }
}
