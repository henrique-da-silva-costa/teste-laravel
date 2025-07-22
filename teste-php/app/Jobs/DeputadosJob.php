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
    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $deputadosModel = new Deputados();

        $response = Http::get('https://dadosabertos.camara.leg.br/api/v2/deputados');
        $valores = $response->json();

        foreach ($valores["dados"] as $valor) {
            if ($deputadosModel->existeDeputado($valor["email"])) {
                print_r(json_encode(["erro" => TRUE, "msg" => "Esse email já existe!"]));
            }

            $cadastrarDeputado = $deputadosModel->cadastrarDeputado($valor);

            if ($cadastrarDeputado->erro) {
                print_r(json_encode(["erro" => true, "msg" => $cadastrarDeputado->msg]));
            }

            $despesas = Http::get("https://dadosabertos.camara.leg.br/api/v2/deputados/{$valor['id']}/despesas");

            foreach ($despesas["dados"] as $despesa) {
                if ($deputadosModel->existeDespesa($despesa["codDocumento"])) {
                    print_r(json_encode(["erro" => TRUE, "msg" => "Código de documento já existe"]));
                }

                $cadastrarDespesa = $deputadosModel->cadastrarDespesa($despesa, $cadastrarDeputado->id);
                if ($cadastrarDespesa->erro) {
                    print_r(json_encode(["erro" => true, "msg" => $cadastrarDespesa->msg]));
                }
            }
        }

        print_r(json_encode(["erro" => FALSE, "msg" => "tudo certo"]));
    }
}
