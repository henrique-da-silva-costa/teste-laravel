import axios from 'axios';
import { useEffect, useState } from 'react'
import styles from "../stilos.module.css"
import { Button, Container, Table } from 'reactstrap';
import Carregando from './Carregando';
import { useNavigate, useParams } from 'react-router-dom';
import moment from 'moment';
import { FaArrowLeft } from 'react-icons/fa';

const Orgaos = () => {
    const [dados, setDados] = useState([]);
    const [msg, setMsg] = useState("");
    const [paginaAtual, setPaginaAtual] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [botaoDesabilitado, setBotaoDesabilitado] = useState(false);
    const [removerLoading, setRemoverLoading] = useState(false);
    const { id, nome, pagina } = useParams();
    const navegacao = useNavigate();

    const pegarDados = (page) => {
        setBotaoDesabilitado(true)
        axios.get("http://127.0.0.1:80/orgaos", { params: { id, page } })
            .then((res) => {
                setBotaoDesabilitado(true)
                setDados(!res.data.data ? [] : res.data.data);
                setPaginaAtual(res.data.current_page);
                setTotalPages(res.data.last_page);
                setBotaoDesabilitado(false); ''
                setRemoverLoading(true);
            }).catch(() => {
                setMsg("erro interno servidor, entre em  contato com o suporte");
                setBotaoDesabilitado(false);
            });
    }

    useEffect(() => {
        const timer = setTimeout(() => {
            pegarDados(paginaAtual);
        }, 1000);

        return () => clearTimeout(timer);
    }, []);

    const paginar = (page) => {
        setBotaoDesabilitado(true);
        if (page >= 1 && page <= totalPages) {
            setPaginaAtual(page);
            pegarDados(page);
        }
    };

    const renderizarBotoesPagina = () => {
        const grupoAtual = Math.ceil(paginaAtual / 5);
        const inicio = (grupoAtual - 1) * 5 + 1;
        const fim = Math.min(grupoAtual * 5, totalPages);

        return (
            <>
                {paginaAtual > 1 && (
                    <Button
                        color="primary"
                        onClick={() => paginar(1)}
                        disabled={botaoDesabilitado}
                        title="Primeira página"
                    >
                        |&lt;
                    </Button>
                )}

                {grupoAtual > 1 && (
                    <Button
                        color="primary"
                        onClick={() => paginar((grupoAtual - 2) * 5 + 1)}
                        disabled={botaoDesabilitado}
                        title="Grupo anterior"
                    >
                        &lt;&lt;
                    </Button>
                )}

                {Array.from({ length: fim - inicio + 1 }, (_, i) => inicio + i).map((pagina) => (
                    <Button
                        color="primary"
                        disabled={pagina === paginaAtual ? true : botaoDesabilitado}
                        key={pagina}
                        onClick={() => paginar(pagina)}
                        className={paginaAtual === pagina ? "active" : ""}
                    >
                        {pagina}
                    </Button>
                ))}

                {grupoAtual < Math.ceil(totalPages / 5) && (
                    <Button
                        color="primary"
                        onClick={() => paginar(grupoAtual * 5 + 1)}
                        disabled={botaoDesabilitado}
                        title="Próximo grupo"
                    >
                        &gt;&gt;
                    </Button>
                )}

                {paginaAtual < totalPages && (
                    <Button
                        color="primary"
                        onClick={() => paginar(totalPages)}
                        disabled={botaoDesabilitado}
                        title="Última página"
                    >
                        &gt;|
                    </Button>
                )}
            </>
        );
    };

    return (
        <>
            <Container className='mt-2'>
                <Button color='transparent' onClick={() => navegacao(`/${pagina}`)}><FaArrowLeft size={40} color='black' /></Button>
                <div className="d-flex gap-1">
                    <h1>Orgão de {nome}</h1>
                    {botaoDesabilitado && removerLoading ? <Carregando /> : ""}
                </div>
                {dados.length > 0 ?
                    <Table responsive striped size="sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Sigla</th>
                                <th>Título</th>
                                <th>Data de início</th>
                                <th>Data final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <>
                                {dados.length > 0 ? dados.map((dado, index) => {
                                    return (
                                        <tr key={index}>
                                            <td>{!dado.nomePublicacao ? "Não informado" : dado.nomePublicacao}</td>
                                            <td>{!dado.siglaOrgao ? "Não informado" : dado.siglaOrgao}</td>
                                            <td>{!dado.titulo ? "Não informado" : dado.titulo}</td>
                                            <td>{!dado.dataInicio ? "Não informado" : moment(dado.dataInicio).format("DD/MM/YYYY")}</td>
                                            <td>{!dado.dataFim ? "Não informado" : moment(dado.dataFim).format("DD/MM/YYYY")}</td>
                                        </tr>
                                    )
                                }) : ""}
                            </>
                        </tbody>
                    </Table>
                    : !removerLoading ? <Carregando /> : dados.length > 0 ? "" : <h2 className="text-center">SEM INFORMAÇÕES</h2>}

                {msg ? <p className={styles.erro}>{msg}</p> : ""}

                {dados.length > 0 ?
                    <>
                        <div className="d-flex gap-2 justify-content-center">
                            <Button
                                color="primary"
                                onClick={() => paginar(paginaAtual - 1)}
                                disabled={paginaAtual === 1 ? paginaAtual : botaoDesabilitado}
                            >
                                Anterior
                            </Button>
                            <div className={styles.botaoPaginas}>
                                {renderizarBotoesPagina()}
                            </div>
                            <Button
                                color="primary"
                                onClick={() => paginar(paginaAtual + 1)}
                                disabled={paginaAtual === totalPages ? paginaAtual : botaoDesabilitado}
                            >
                                Próximo
                            </Button>
                        </div>
                    </>
                    : ""}
            </Container>
        </>
    )
}

export default Orgaos