<?php

namespace App\Http\Controllers;

use App\Core\CoreController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Renault\Requests\AuthRequest;
use App\Renault\Requests\SolicitarRecuperarSenhaRequest;
use App\Renault\Requests\RecuperarSenhaRequest;

use Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecuperacaoSenha;

use App\Events\UserLoggedIn;

class AuthController extends CoreController
{
    /**
    * @OA\Post(
    *      path="/api/v1/login",
    *      operationId="login",
    *      tags={"Auth"},
    *      summary="Login",
    *      description="Route to login",
    *      @OA\Response(
    *          response=200,
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
    *                     property="email",
    *                     description="User's email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     description="User's password",
    *                     type="string",
    *                 )
    *             )
    *         )
    *      )
    *)
    *
    */   
    public function login(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return $this->api->responseFail(
                ["Credencias incorretas."]
            );
        }

    	Auth::user()->update(['api_token' => Str::random(60)]);

        $usuario = User::find(Auth::user()->id);

        event(new UserLoggedIn($usuario));

        $usuario = $usuario->toArray();

        $usuario['api_token'] = Auth::user()->api_token;

        return $this->api->responseOk(
            $obj = $usuario,
            $message = "Sucesso na requisição.",
            $successCode = 200
        );
        
        return $this->api->responseFail(
            ["Erro no login."]
        );
    }

    /**
    * @OA\Post(
    *      path="/api/v1/logout",
    *      operationId="logout",
    *      tags={"Auth"},
    *      summary="Logout (NECESSÁRIO ESTAR AUTENTICADO)",
    *      description="Rota para logout. Usuário DEVE estar autenticado para acessar.",
    *      @OA\Response(
    *          response=200,
    *          description="successful operation"
    *       ),
    *       @OA\Response(response=401, description="Unauthorized"),
    *       @OA\Response(response=500, description="Unauthenticated"),
    *       security={
    *           {"api_token": {}}
    *       }
    *      )
    *)
    *
    */  
    public function logout() {
        try{
            Auth::user()->update(['api_token' => null]);
            Session::flush();
            return $this->api->responseOk(
                $obj = ['Você saiu do sistema.'],
                $message = "Sucesso na requisição.",
                $successCode = 200
            );
        } catch(\Exception $e){
            return $this->api->responseFail(
                ["Erro no logout."]
            );
        }
    }

    /**
    * @OA\Post(
    *      path="/api/v1/solicitar_recuperar_senha",
    *      operationId="solicitar_recuperar_senha",
    *      tags={"Auth"},
    *      summary="solicitar recuperar senha",
    *      description="Route to solicitar_recuperar_senha",
    *      @OA\Response(
    *          response=200,
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
    *                     property="email",
    *                     description="User's email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="link_definir_senha",
    *                     description="Link do front para definir nova senha",
    *                     type="string url",
    *                 )
    *             )
    *         )
    *      )
    *)
    *
    */  
    public function solicitarRecuperarSenha(SolicitarRecuperarSenhaRequest $request){
        $user = User::whereEmail($request->input('email'))->first();

        $tokenRecuperacaoSenha = Str::random(60);
        $user->update(['api_token' => $tokenRecuperacaoSenha]);

        Mail::to($user->email)->send( new RecuperacaoSenha($request->input('link_definir_senha'), $tokenRecuperacaoSenha) );

        return $this->api->responseOk(
            $obj = ['Email enviado com sucesso.'],
            $message = "Sucesso na requisição.",
            $successCode = 200
        );
    }

    /**
    * @OA\Post(
    *      path="/api/v1/recuperar_senha",
    *      operationId="recuperarSenha",
    *      tags={"Auth"},
    *      summary="Define nova senha para usuário",
    *      description="Define nova senha para usuário",
    *      @OA\Response(
    *          response=200,
    *          description="successful operation"
    *       ),
    *       @OA\Response(response=400, description="Bad request"),
    *       @OA\RequestBody(
    *         description="Input data format",
    *         @OA\MediaType(
    *             mediaType="application/x-www-form-urlencoded",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="password",
    *                     description="User's password",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password_confirmation",
    *                     description="User's password confirmation",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="token",
    *                     description="Token de recuperação de senha. Enviado junto ao link no e-mail de recuperação.",
    *                     type="string",
    *                 )
    *             )
    *         )
    *      )
    *)
    *
    */
    public function recuperarSenha(RecuperarSenhaRequest $request){
        $user = User::where('api_token', $request->input('token'))->first();
        
        $password_hash = Hash::make($request->input('password'));

        $user->update([
            'password' => $password_hash,
            'api_token' => null
        ]);
        return $this->api->responseOk(
            $obj = ['Senha alterada com sucesso.'],
            $message = "Sucesso na requisição.",
            $successCode = 200
        );
    }

     
}
            