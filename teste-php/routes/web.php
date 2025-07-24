<?php

use App\Http\Controllers\HomeController;
use App\Jobs\DeputadosJob;
use App\Models\Job;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class, "home"])->name("HomeController.home");
Route::get("/deputados", [HomeController::class, "deputados"])->name("HomeController.deputados");
Route::get("/despesas", [HomeController::class, "despesas"])->name("HomeController.despesas");
Route::get("/sincronizar", function () {
    DeputadosJob::dispatch();
});

Route::get("/jobmensagem", function () {
    return response()->json(Job::pegarMensagem());
});
