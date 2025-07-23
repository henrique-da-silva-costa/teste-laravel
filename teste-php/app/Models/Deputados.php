<?php

namespace App\Models;

use App\Http\Controllers\Tabelas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Deputados extends Model
{

    private $tabelaDeputados;
    private $tabelaDespesas;

    public function __construct()
    {
        $this->tabelaDeputados = Tabelas::DEPUTADOS;
        $this->tabelaDespesas = Tabelas::DESPESAS;
    }

    public function pegarDeputados($filtros)
    {
        try {
            $nome = isset($filtros["nome"]) ? $filtros["nome"] : NULL;
            $estado = isset($filtros["estado"]) ? $filtros["estado"] : NULL;

            $dados = DB::table($this->tabelaDeputados)
                ->join($this->tabelaDespesas, "{$this->tabelaDespesas}.deputados_id", "=", "{$this->tabelaDeputados}.id")
                ->where("{$this->tabelaDeputados}.nome", "like", "{$nome}%")
                ->where("{$this->tabelaDeputados}.siglaUf", "like", "{$estado}%")
                ->select([
                    "{$this->tabelaDeputados}.nome",
                    "{$this->tabelaDeputados}.urlFoto AS foto",
                    "{$this->tabelaDeputados}.siglaUf AS estado",
                    "{$this->tabelaDeputados}.siglaPartido AS partido",
                    "{$this->tabelaDespesas}.tipoDespesa AS despesa",
                    "{$this->tabelaDespesas}.valorDocumento AS valor",
                ])->limit(100)->paginate(20);

            return $dados;
        } catch (\Throwable $th) {
            return [$th->getMessage()];
        }
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

    public function cadastrarDeputado($dados)
    {
        try {
            $retorno = new stdClass;
            $retorno->erro = FALSE;
            $retorno->msg = NULL;
            $retorno->id = 0;

            $nome = isset($dados["nome"]) ? $dados["nome"] : NULL;
            $email = isset($dados["email"]) ? $dados["email"] : NULL;
            $uri = isset($dados["uri"]) ? $dados["uri"] : NULL;
            $siglaPartido = isset($dados["siglaPartido"]) ? $dados["siglaPartido"] : NULL;
            $uriPartido = isset($dados["uriPartido"]) ? $dados["uriPartido"] : NULL;
            $siglaUf = isset($dados["siglaUf"]) ? $dados["siglaUf"] : NULL;
            $idLegislatura = isset($dados["idLegislatura"]) ? $dados["idLegislatura"] : NULL;
            $urlFoto = isset($dados["urlFoto"]) ? $dados["urlFoto"] : NULL;

            $retorno->id = DB::table($this->tabelaDeputados)->insertGetId([
                "nome" => $nome,
                "email" => $email,
                "uri" => $uri,
                "siglaPartido" => $siglaPartido,
                "uriPartido" => $uriPartido,
                "siglaUf" => $siglaUf,
                "idLegislatura" => $idLegislatura,
                "urlFoto" => $urlFoto,
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

            DB::table($this->tabelaDespesas)->insertGetId([
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
