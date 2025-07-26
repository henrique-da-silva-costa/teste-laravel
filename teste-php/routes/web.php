<?php

use App\Http\Controllers\DeputadosController;
use App\Jobs\DeputadosJob;
use App\Models\Job;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get("/", [DeputadosController::class, "home"])->name("DeputadosController.home");
Route::get("/deputados", [DeputadosController::class, "deputados"])->name("DeputadosController.deputados");
Route::get("/despesas", [DeputadosController::class, "despesas"])->name("DeputadosController.despesas");
Route::get("/orgaos", [DeputadosController::class, "orgaos"])->name("DeputadosController.orgaos");
Route::get("/sincronizar", function () {
    DeputadosJob::dispatch();
});

Route::get("/jobmensagem", function () {
    return response()->json(Job::pegarMensagem());
});

Route::delete("/limpar", function () {
    try {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("deputados")->truncate();
        DB::table("despesas")->truncate();
        DB::table("orgaos")->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return response()->json(["erro" => FALSE]);
    } catch (\Throwable $th) {
        return response()->json(["erro" => TRUE, "msg" => $th->getMessage()]);
    }
});
