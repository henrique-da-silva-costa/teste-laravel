<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Job extends Model
{
    public static function mensagem($mensagem)
    {
        try {
            $retorno = new stdClass;
            $retorno->erro = FALSE;
            $retorno->msg = NULL;

            DB::table("mensagems_job")->where("id", "=", 1)->update(["mensagem" => $mensagem]);

            return $retorno;
        } catch (\Throwable $th) {
            $retorno = new stdClass;
            $retorno->erro = TRUE;
            $retorno->msg = $th->getMessage();

            return $retorno;
        }
    }

    public static function pegarMensagem()
    {
        try {
            $mensagem = DB::table("mensagems_job")->first(["mensagem"]);

            return $mensagem;
        } catch (\Throwable $th) {
            return NULL;
        }
    }
}
