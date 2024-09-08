<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Formularios\Formulario;
use App\Models\Formularios\FormularioQuestao;
use App\Models\Formularios\MultiplaEscolha;
use App\Models\Usuario;

class FormulariosSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $admin = Usuario::query()->where('tipo', 'ADMIN')->first();

        for ($i = 0; $i < 20; $i++) {
            $formulario = Formulario::create([
                'nome_formulario' => 'Formulário ' . $faker->word(),
                'status' => Formulario::CRIADO,
                'professor_id' => $admin->id,
            ]);

            for ($j = 0; $j < rand(5, 10); $j++) {
                $tipo = $faker->randomElement([FormularioQuestao::TEXTO_LIVRE, FormularioQuestao::MULTIPLA_ESCOLHA]);

                $questao = FormularioQuestao::create([
                    'formulario_id' => $formulario->id,
                    'questao' => $faker->sentence(),
                    'tipo' => $tipo,
                ]);

                if ($tipo === FormularioQuestao::MULTIPLA_ESCOLHA) {
                    for ($k = 0; $k < rand(2, 4); $k++) {
                        MultiplaEscolha::create([
                            'formulario_questao_id' => $questao->id,
                            'opcao_resposta' => $faker->word(),
                        ]);
                    }
                }
            }
        }
    }
}
