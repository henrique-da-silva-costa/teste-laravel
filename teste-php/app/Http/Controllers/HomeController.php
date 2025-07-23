<?php

namespace App\Http\Controllers;

use App\Models\Deputados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    private $deputados;

    public function __construct()
    {
        $this->deputados = new Deputados;
    }

    public function Home()
    {


        $response = Http::get('https://dadosabertos.camara.leg.br/api/v2/deputados');
        $valores = $response->json();

        foreach ($valores["dados"] as $valor) {

            // $respostaD = Http::get($valor["uri"]);
            // $deputado = $respostaD->json();


            // var_dump($deputado["dados"]["nomeCivil"])  nome completo;
            // var_dump($deputado["dados"]["dataNascimento"]);
            // var_dump($deputado["dados"]["ufNascimento"]);
            // var_dump($deputado["dados"]["municipioNascimento"]);
            // var_dump($deputado["dados"]["escolaridade"]);
            // var_dump($deputado["dados"]);

            // foreach ($deputado["ultimoStatus"] as $status) {
            //     var_dump($dado);
            // }


            // if ($cadastrarDeputado->erro) {
            //     return print_r(json_encode(["erro" => TRUE, "msg" => $cadastrarDeputado->msg]));
            // }

            // $despesas = Http::get("https://dadosabertos.camara.leg.br/api/v2/deputados/{$valor['id']}/despesas");

            // foreach ($despesas["dados"] as $despesa) {
            //     $cadastrarDespesa = $this->deputados->cadastrarDespesa($despesa, $cadastrarDeputado->id);
            //     if ($cadastrarDespesa->erro) {
            //         return print_r(json_encode(["erro" => TRUE, "msg" => $cadastrarDespesa->msg]));
            //     }
            // }
        }
    }

    public function deputados(Request $request)
    {
        $deputados = $this->deputados->pegarDeputados($request["filtros"]);

        return response()->json($deputados);
    }

    public function despesas(Request $request)
    {
        $id = isset($request["id"]) ? $request["id"] : 0;

        if (!is_numeric($id)) {
            return response()->json([]);
        }

        $deputados = $this->deputados->pegarDespesas($id);

        return response()->json($deputados);
    }
}
