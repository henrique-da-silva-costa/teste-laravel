<?php

namespace App\Http\Controllers;

use App\Models\Deputados;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    private $deputados;

    public function __construct()
    {
        $this->deputados = new Deputados;
    }

    public function Home() {}

    public function deputados()
    {
        $deputados = $this->deputados->pegarDeputados(["nome" => "e", "estado" => ""]);

        return response()->json(["deputados" => $deputados]);
    }
}
