<?php

namespace App\Models;

use App\Http\Controllers\Tabelas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Deputados extends Model
{

    private $tabelaDeputados;
    private $tabelaDespesa;

    public function __construct()
    {
        $this->tabelaDeputados = Tabelas::DEPUTADOS;
        $this->tabelaDespesa = Tabelas::DESPESAS;
    }

    public function existeDeputado($email)
    {
        try {
            $totalDeputados = DB::table($this->tabelaDeputados)->where("email", "=", $email)->count();

            if ($totalDeputados > 0) {
                return TRUE;
            }

            return FALSE;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function existeDespesa($codDocumento)

    {
        try {
            $totalDeputados = DB::table($this->tabelaDespesa)->where("codDocumento", "=", $codDocumento)->count();

            if ($totalDeputados > 0) {
                return TRUE;
            }

            return FALSE;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function cadastrarDeputado($dados)
    {
        try {
            $retorno = new stdClass;
            $retorno->erro = FALSE;
            $retorno->msg = NULL;
            $retorno->id = 0;

            $nome = isset($dados["nome"]) ? $dados["nome"] : NULL;
            $email = isset($dados["email"]) ? $dados["email"] : NULL;

            $retorno->id = DB::table($this->tabelaDeputados)->insertGetId([
                "nome" => $nome,
                "email" => $email
            ]);

            return $retorno;
        } catch (\Throwable $th) {
            $retorno = new stdClass;
            $retorno->erro = TRUE;
            $retorno->msg = $th->getMessage();

            return $retorno;
        }
    }

    public function cadastrarDespesa($dados, $deputado_id)
    {
        try {
            $retorno = new stdClass;
            $retorno->erro = FALSE;
            $retorno->msg = NULL;

            $dataDocumento = isset($dados["dataDocumento"]) ? $dados["dataDocumento"] : NULL;
            $tipoDespesa = isset($dados["tipoDespesa"]) ? $dados["tipoDespesa"] : NULL;
            $valorDocumento = isset($dados["valorDocumento"]) ? $dados["valorDocumento"] : NULL;
            $tipoDocumento = isset($dados["tipoDocumento"]) ? $dados["tipoDocumento"] : NULL;
            $ano = isset($dados["ano"]) ? $dados["ano"] : NULL;
            $mes = isset($dados["mes"]) ? $dados["mes"] : NULL;
            $codDocumento = isset($dados["codDocumento"]) ? $dados["codDocumento"] : NULL;
            $codTipoDocumento = isset($dados["codTipoDocumento"]) ? $dados["codTipoDocumento"] : NULL;
            $numDocumento = isset($dados["numDocumento"]) ? $dados["numDocumento"] : NULL;
            $urlDocumento = isset($dados["urlDocumento"]) ? $dados["urlDocumento"] : NULL;
            $nomeFornecedor = isset($dados["nomeFornecedor"]) ? $dados["nomeFornecedor"] : NULL;
            $cnpjCpfFornecedor = isset($dados["cnpjCpfFornecedor"]) ? $dados["cnpjCpfFornecedor"] : NULL;
            $valorLiquido = isset($dados["valorLiquido"]) ? $dados["valorLiquido"] : NULL;
            $valorGlosa = isset($dados["valorGlosa"]) ? $dados["valorGlosa"] : NULL;
            $numRessarcimento = isset($dados["numRessarcimento"]) ? $dados["numRessarcimento"] : NULL;
            $codLote = isset($dados["codLote"]) ? $dados["codLote"] : NULL;
            $parcela = isset($dados["parcela"]) ? $dados["parcela"] : NULL;

            DB::table($this->tabelaDespesa)->insertGetId([
                "deputados_id" => $deputado_id,
                "ano" => $ano,
                "mes" => $mes,
                "tipoDespesa" => $tipoDespesa,
                "codDocumento" => $codDocumento,
                "tipoDocumento" => $tipoDocumento,
                "codTipoDocumento" => $codTipoDocumento,
                "dataDocumento" => $dataDocumento,
                "numDocumento" => $numDocumento,
                "valorDocumento" => $valorDocumento,
                "urlDocumento" => $urlDocumento,
                "nomeFornecedor" => $nomeFornecedor,
                "cnpjCpfFornecedor" => $cnpjCpfFornecedor,
                "valorLiquido" => $valorLiquido,
                "valorGlosa" => $valorGlosa,
                "numRessarcimento" => $numRessarcimento,
                "codLote" => $codLote,
                "parcela" => $parcela
            ]);

            return $retorno;
        } catch (\Throwable $th) {
            $retorno = new stdClass;
            $retorno->erro = TRUE;
            $retorno->msg = $th->getMessage();

            return $retorno;
        }
    }
}
