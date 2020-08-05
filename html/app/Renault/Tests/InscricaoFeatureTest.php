<?php

namespace App\Renault\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Core\Tests\CoreTestFeature;
use App\Renault\Models\Inscricao;

class InscricaoFeatureTest extends CoreTestFeature
{
    use DatabaseTransactions;

    private $route = '/api/v1/inscricoes';

    public function testListarTodasInscricoes()
    {
        $response = $this->get($this->route);
        $response->assertStatus(200);
    }
    
    public function testAdicionarProdutoNovo()
    {
        $response = $this->post(
            $this->route,
            [
                'lider_cpf' => $this->faker->cpf,
                'telefone' => $this->faker->cellphoneNumber,
                'email' => $this->faker->safeEmail
            ]
        );
        $response->assertStatus(201);

        $this->assertDatabaseHas('inscricoes', $response->original->data);
    }

    public function testExibirInscricaoEspecifica()
    {
        $inscricao = Inscricao::all()->random();
        $response = $this->get("{$this->route}/{$inscricao->id}");
        $response->assertStatus(200);
    }

    // DOCUMENTAÇÃO FAKER - https://github.com/fzaninotto/Faker
}