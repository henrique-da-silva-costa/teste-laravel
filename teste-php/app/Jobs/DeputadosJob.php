<?php

namespace App\Jobs;

use App\Models\Deputados;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DeputadosJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 300;

    public function __construct() {}

    public function handle()
    {
        $deputadosModel = new Deputados();

        $response = Http::get('https://dadosabertos.camara.leg.br/api/v2/deputados');
        $valores = $response->json();

        foreach ($valores["dados"] as $valor) {
            if ($deputadosModel->existeDeputado($valor["email"])) {
                return ["erro" => TRUE, "msg" => "Esse email já existe!"];
                // continue;
            }

            $cadastrarDeputado = $deputadosModel->cadastrarDeputado($valor);

            if ($cadastrarDeputado->erro) {
                return print_r(json_encode(["erro" => TRUE, "msg" => $cadastrarDeputado->msg]));
            }

            $despesas = Http::get("https://dadosabertos.camara.leg.br/api/v2/deputados/{$valor['id']}/despesas");

            foreach ($despesas["dados"] as $despesa) {
                $cadastrarDespesa = $deputadosModel->cadastrarDespesa($despesa, $cadastrarDeputado->id);
                if ($cadastrarDespesa->erro) {
                    return print_r(json_encode(["erro" => TRUE, "msg" => $cadastrarDespesa->msg]));
                }
            }
        }

        return print_r(json_encode(["erro" => FALSE, "msg" => "tudo certo ok mestre"]));
    }
}
