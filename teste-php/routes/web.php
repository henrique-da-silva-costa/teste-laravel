<?php

use App\Http\Controllers\HomeController;
use App\Jobs\DeputadosJob;
use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class, "home"])->name("HomeController.home");
Route::get("/deputados", [HomeController::class, "deputados"])->name("HomeController.deputados");
Route::get("/sincronizar", function () {
    DeputadosJob::dispatch();

    return response()->json(["erro" => FALSE, "msg" => "tudo certo"]);
});
