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
    private $tabelaOrgaos;

    public function __construct()
    {
        $this->tabelaDeputados = Tabelas::DEPUTADOS;
        $this->tabelaDespesas = Tabelas::DESPESAS;
        $this->tabelaOrgaos = Tabelas::ORGAOS;
    }

    public function pegarDeputados($filtros)
    {
        try {
            $filtroNome = isset($filtros["nome"]) ? $filtros["nome"] : NULL;
            $filtroEstado = isset($filtros["estado"]) ? $filtros["estado"] : NULL;
            $filtroPartido = isset($filtros["partido"]) ? $filtros["partido"] : NULL;

            $sql = DB::table($this->tabelaDeputados);
            if ($filtroNome) {
                $sql->where("{$this->tabelaDeputados}.nome", "like", "{$filtroNome}%");
            }
            if ($filtroPartido) {
                $sql->where("{$this->tabelaDeputados}.siglaPartido", "like", "{$filtroPartido}%");
            }
            if ($filtroEstado) {
                $sql->where("{$this->tabelaDeputados}.siglaUf", "like", "{$filtroEstado}%");
            }
            $sql->select([
                "{$this->tabelaDeputados}.id",
                "{$this->tabelaDeputados}.nome",
                "{$this->tabelaDeputados}.urlFoto AS foto",
                "{$this->tabelaDeputados}.siglaUf AS estado",
                "{$this->tabelaDeputados}.siglaPartido AS partido"
            ]);

            $sql->limit(100);

            $dados = $sql->paginate(4);

            return $dados;
        } catch (\Throwable $th) {
            return [$th->getMessage()];
        }
    }

    public function pegarDespesas($id)
    {
        try {
            $dados = DB::table($this->tabelaDespesas)
                ->where("deputados_id", "=", $id)
                ->select([
                    "{$this->tabelaDespesas}.tipoDespesa as tipo",
                    "{$this->tabelaDespesas}.nomeFornecedor as fornecedor",
                    "{$this->tabelaDespesas}.dataDocumento as data",
                    "{$this->tabelaDespesas}.valorDocumento AS valor",
                ])
                ->limit(100)
                ->paginate(7);

            return $dados;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function pegarOrgaos($id)
    {
        try {
            $dados = DB::table($this->tabelaOrgaos)
                ->where("deputados_id", "=", $id)
                ->select([
                    "{$this->tabelaOrgaos}.siglaOrgao as sigla",
                    "{$this->tabelaOrgaos}.nomePublicacao as nome",
                    "{$this->tabelaOrgaos}.titulo",
                    "{$this->tabelaOrgaos}.dataInicio",
                    "{$this->tabelaOrgaos}.dataFim",
                ])
                ->limit(100)
                ->paginate(7);

            return $dados;
        } catch (\Throwable $th) {
            return [];
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

            DB::table($this->tabelaDespesas)->insert([
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

    public function cadastrarOrgaos($dados, $deputado_id)
    {
        try {
            $retorno = new stdClass;
            $retorno->erro = FALSE;
            $retorno->msg = NULL;

            $siglaOrgao = isset($dados["siglaOrgao"]) ? $dados["siglaOrgao"] : NULL;
            $nomeOrgao = isset($dados["nomeOrgao"]) ? $dados["nomeOrgao"] : NULL;
            $nomePublicacao = isset($dados["nomePublicacao"]) ? $dados["nomePublicacao"] : NULL;
            $titulo = isset($dados["titulo"]) ? $dados["titulo"] : NULL;
            $codTitulo = isset($dados["codTitulo"]) ? $dados["codTitulo"] : NULL;
            $dataInicio = isset($dados["dataInicio"]) ? $dados["dataInicio"] : NULL;
            $dataFim = isset($dados["dataFim"]) ? $dados["dataFim"] : NULL;

            DB::table($this->tabelaOrgaos)->insert([
                "deputados_id" => $deputado_id,
                "siglaOrgao" => $siglaOrgao,
                "nomeOrgao" => $nomeOrgao,
                "nomePublicacao" => $nomePublicacao,
                "titulo" => $titulo,
                "codTitulo" => $codTitulo,
                "dataInicio" => $dataInicio,
                "dataFim" => $dataFim,
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
