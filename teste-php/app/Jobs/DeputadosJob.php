<?php

namespace App\Jobs;

use App\Models\Deputados;
use App\Models\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class DeputadosJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 500;

    public function __construct() {}

    public function cadastrarDespesa($id, $idDeputado, $deputadosModel)
    {
        $despesas = Http::get("https://dadosabertos.camara.leg.br/api/v2/deputados/$id/despesas");
        foreach ($despesas["dados"] as $despesa) {
            $cadastrarDespesa = $deputadosModel->cadastrarDespesa($despesa, $idDeputado);
            if ($cadastrarDespesa->erro) {
                Job::mensagem($cadastrarDespesa->msg);
                return;
            }
        }
    }

    public function cadastrarOrgao($id, $idDeputado, $deputadosModel)
    {
        $orgaos = Http::get("https://dadosabertos.camara.leg.br/api/v2/deputados/$id/orgaos");
        foreach ($orgaos["dados"] as $orgao) {
            $cadastrarOrgao = $deputadosModel->cadastrarOrgaos($orgao, $idDeputado);
            if ($cadastrarOrgao->erro) {
                Job::mensagem($cadastrarOrgao->msg);
                return;
            }
        }
    }

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

            $this->cadastrarDespesa($valor["id"], $cadastrarDeputado->id, $deputadosModel);
            $this->cadastrarOrgao($valor["id"], $cadastrarDeputado->id, $deputadosModel);
        }

        Job::mensagem("Concluido com sucesso!");
        return;
    }
}
