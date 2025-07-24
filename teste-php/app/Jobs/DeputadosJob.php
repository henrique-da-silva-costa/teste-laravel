<?php

namespace App\Jobs;

use App\Models\Deputados;
use App\Models\Job;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DeputadosJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 300;

    public function __construct() {}

    public function handle()
    {
        Job::mensagem("Carregando...");
        $deputadosModel = new Deputados();

        $response = Http::get('https://dadosabertos.camara.leg.br/api/v2/deputados');
        $valores = $response->json();

        foreach ($valores["dados"] as $valor) {
            if ($deputadosModel->existeDeputado($valor["email"])) {
                continue;
            }

            $cadastrarDeputado = $deputadosModel->cadastrarDeputado($valor);

            if ($cadastrarDeputado->erro) {
                Job::mensagem($cadastrarDeputado->msg);
                return;
            }

            $despesas = Http::get("https://dadosabertos.camara.leg.br/api/v2/deputados/{$valor['id']}/despesas");

            foreach ($despesas["dados"] as $despesa) {
                $cadastrarDespesa = $deputadosModel->cadastrarDespesa($despesa, $cadastrarDeputado->id);
                if ($cadastrarDespesa->erro) {
                    Job::mensagem($cadastrarDespesa->msg);
                    return;
                }
            }
        }

        Job::mensagem("Concluido com sucesso!");
        return;
    }
}
