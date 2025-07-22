<?php

namespace App\Http\Controllers;

use App\Jobs\DeputadosJob;
use App\Models\Deputados;
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
        DeputadosJob::dispatch();
    }
}
