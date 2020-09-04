<?php

namespace App\Http\Controllers;

use App\Core\Exceptions\GenericException;

use App\Core\CoreController;
use Illuminate\Support\Facades\Mail;

use App\Renault\Requests\UserRequest;
use App\Renault\Services\UserService;
use App\Mail\UserEmailConfirm;

use App\Renault\Models\Colaborador;
use App\Renault\Services\ColaboradorService;

class UserController extends CoreController
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
    * @OA\Post(
    *      path="/api/v1/user/register",
    *      operationId="register",
    *      tags={"User"},
    *      summary="Register user",
    *      description="Register the user after checking that they are a valid Colaborador",
    *      @OA\Response(
    *          response=201,
    *          description="successful operation"
    *       ),
    *       @OA\Response(response=400, description="Bad request"),
    *       @OA\RequestBody(
    *         description="Input data format",
    *         @OA\MediaType(
    *             mediaType="application/x-www-form-urlencoded",
    *             @OA\Schema(
    *                 type="object",
    *                 @OA\Property(
    *                     property="cpf",
    *                     description="Colaborador CPF",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="primeiro_nome",
    *                     description="Colaborador's first name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="ultimo_nome",
    *                     description="Colaborador's last name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="data_nascimento",
    *                     description="Colaborador's birthdate",
    *                     type="date('d/m/Y')",
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     description="User's email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     description="User's password",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password_confirmation",
    *                     description="User's password confirmation",
    *                     type="string",
    *                 )
    *             )
    *         )
    *      )
    *)
    *
    * Register user and return his attributes
    */
    public function register(UserRequest $request)
    {
        $colaborador = Colaborador::filtrar($request->only(
            'cpf',
            'primeiro_nome',
            'ultimo_nome',
            'data_nascimento'
        ))->first();

        if(!$colaborador){
            return $this->api->responseFail(
                ['Não foi encontrado colaborador com esses dados.'],
                __('exception.erroNosDados')
            );
        }

        $user = $request->only(
            'email',
            'password'
        );
        $user['colaborador_id'] = $colaborador->id;
        $user['name'] = $colaborador->colaborador;

        $user = UserService::register($user);

        Mail::to($user->email)->queue(new UserEmailConfirm($user));

        return $this->api->responseOk($user); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $requests)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
