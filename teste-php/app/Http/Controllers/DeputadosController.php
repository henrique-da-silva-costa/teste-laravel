<?php

namespace App\Http\Controllers;

use App\Models\Deputados;
use Illuminate\Http\Request;

class DeputadosController extends Controller
{
    private $deputados;

    public function __construct()
    {
        $this->deputados = new Deputados;
    }

    public function Home()
    {
        return response()->json(["Home"]);
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

        $despesas = $this->deputados->pegarDespesas($id);

        return response()->json($despesas);
    }

    public function orgaos(Request $request)
    {
        $id = isset($request["id"]) ? $request["id"] : 0;

        if (!is_numeric($id)) {
            return response()->json([]);
        }

        $orgaos = $this->deputados->pegarOrgaos($id);

        return response()->json($orgaos);
    }
}
