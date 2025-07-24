import axios from 'axios';
import { useEffect, useState } from 'react'
import styles from "./stilos.module.css"
import { Button, Container, Table } from 'reactstrap';
import Carregando from './componentes/Carregando';
import Filtros from './componentes/Filtros';
import { useNavigate } from 'react-router-dom';
import { GrUpdate } from 'react-icons/gr';

const Home = () => {
    const [dados, setDados] = useState([]);
    const [msg, setMsg] = useState("");
    const [paginaAtual, setPaginaAtual] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [botaoDesabilitado, setBotaoDesabilitado] = useState(false);
    const [removerLoading, setRemoverLoading] = useState(false);
    const [mensagemJob, setMensagemJob] = useState("");
    const navegacao = useNavigate();

    const pegarDados = (page, filtros) => {
        axios.get("http://127.0.0.1:80/jobmensagem").then((res) => {
            setMensagemJob(res.data.mensagem)
        }).catch((err) => {
            console.log(err)
        })

        setBotaoDesabilitado(true)
        axios.get("http://127.0.0.1:80/deputados", { params: { filtros, page } })
            .then((res) => {
                setDados(!res.data.data ? [] : res.data.data);
                setPaginaAtual(res.data.current_page);
                setTotalPages(res.data.last_page);
                setBotaoDesabilitado(false); ''
                setRemoverLoading(true);
            }).catch((err) => {
                setMsg("erro interno servidor, entre em  contato com o suporte");
                setBotaoDesabilitado(false);
            });
    }

    useEffect(() => {
        setTimeout(() => {
            pegarDados(paginaAtual);
        }, 1000);
    }, [paginaAtual]);

    const paginar = (page) => {
        setBotaoDesabilitado(true);
        if (page >= 1 && page <= totalPages) {
            setPaginaAtual(page);
        }
    };

    const renderizarBotoesPagina = () => {
        const grupoAtual = Math.ceil(paginaAtual / 5);
        const inicio = (grupoAtual - 1) * 5 + 1;
        const fim = Math.min(grupoAtual * 5, totalPages);

        return (
            <>
                {/* Botão para primeira página */}
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

                {/* Botão para voltar ao grupo anterior */}
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

                {/* Renderizar páginas do grupo atual */}
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

                {/* Botão para avançar ao próximo grupo */}
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

                {/* Botão para última página */}
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
            <Container>
                <div className="d-flex gap-2 mt-3">
                    <h1>Deputados</h1>
                    <Filtros paginaAtual={paginaAtual} pegarDados={pegarDados} />
                    <span>{mensagemJob}</span>
                    <Button color="transparent" alt="atualizar registros" onClick={() => pegarDados(paginaAtual)}><GrUpdate size={30} color="blue" /></Button>
                </div>
                {dados.length > 0 ?
                    <Table responsive striped size="sm">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nome</th>
                                <th>Partido</th>
                                <th>Estado</th>
                                <th className='text-end'>Despesas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <>
                                {dados.length > 0 ? dados.map((dado, index) => {
                                    return (
                                        <tr key={index}>
                                            <td>
                                                <img src={dado.foto} height={70} alt="foto do deputado" />
                                            </td>
                                            <td>{dado.nome ? dado.nome.slice(0, 30) + `${dado.nome.length >= 30 ? "..." : ""}` : "não informado"}</td>
                                            <td><strong>{dado.partido}</strong></td>
                                            <td><strong>{dado.estado}</strong></td>
                                            <td className="text-end">
                                                <Button onClick={() => navegacao(`despesas/${dado.id}`)}>Despesas</Button>
                                            </td>
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
                        {botaoDesabilitado ? <Carregando /> : ""}
                    </>
                    : ""}
            </Container>
        </>
    )
}

export default Home