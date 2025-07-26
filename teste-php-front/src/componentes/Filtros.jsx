import { useRef, useState } from 'react'
import { Button, Container, Input } from 'reactstrap';
import styles from "../stilos.module.css"
import { useNavigate } from 'react-router-dom';
import { estadosBrasil } from './listaEstados';
import { SiCcleaner } from "react-icons/si";
import { FaSearch } from 'react-icons/fa';

const Filtros = ({ paginaAtual, pegarDados = () => { } }) => {
    const [formulario, setFormulario] = useState({});
    const formRef = useRef();
    const navegacao = useNavigate();

    const [busca, setBusca] = useState('');
    const [estadoSelecionado, setEstadoSelecionado] = useState('');
    const [mostrarOpcoes, setMostrarOpcoes] = useState(false);

    const estadosFiltrados = estadosBrasil.filter(estadoObj => {
        const [nome] = Object.entries(estadoObj)[0];
        return nome.toLowerCase().includes(busca.toLowerCase());
    });

    const selecionarEstado = (sigla, nome) => {
        setEstadoSelecionado(sigla);
        setBusca(sigla);
        setMostrarOpcoes(false);
        setFormulario({
            ...formulario,
            estado: sigla
        });
    };

    const changeformulario = (e) => {
        const { name, value, files } = e.target;

        if (value.length < 1) {
            setMostrarOpcoes(false);
        }

        formulario.estado = busca;

        setFormulario({
            ...formulario, [name]: name === "img" ? files[0] : value
        });

    }

    const filtrar = (e) => {
        console.log(formulario)
        e.preventDefault();
        navegacao("/");
        pegarDados(1, formulario);
        localStorage.setItem("filtros", JSON.stringify(formulario));
    }

    const limpar = (e) => {
        e.preventDefault();
        setBusca("");
        pegarDados(paginaAtual, {});
        setFormulario({});
        formRef.current.reset();
        localStorage.setItem("filtros", JSON.stringify({}));
    }

    return (
        <Container>
            <form ref={formRef} onSubmit={filtrar}>
                <div className={`d-flex align-items-center gap-1 justify-content-center ${styles.divPaiFiltro}`}>
                    <div className={styles.divFilhaFiltro}>
                        <label><strong>Nome</strong></label>
                        <Input name="nome" placeholder="Nome do deputado" defaultValue={JSON.parse(localStorage.getItem("filtros")).nome} onChange={changeformulario} />
                    </div>
                    <div className={styles.divFilhaFiltro}>
                        <label><strong>Partido</strong></label>
                        <Input name="partido" placeholder="Nome do partido" defaultValue={JSON.parse(localStorage.getItem("filtros")).partido} onChange={changeformulario} />
                    </div>
                    <div className={styles.divFilhaFiltro}>
                        <label><strong>Sigla do Estado</strong></label>
                        <div className={styles.selectComBusca}>
                            <div className={styles.campoBusca}>
                                <Input
                                    type="text"
                                    value={busca}
                                    name="estado"
                                    // onChange={changeformulario}
                                    onChange={(e) => {
                                        setBusca(e.target.value);
                                        if (e.target.value.length > 0) {
                                            setMostrarOpcoes(true);
                                        } else {
                                            setMostrarOpcoes(false)
                                        }

                                        setFormulario({
                                            ...formulario,
                                            estado: e.target.value
                                        });
                                    }}
                                    onFocus={() => setMostrarOpcoes(true)}
                                    placeholder="Busque um estado..."
                                />
                            </div>

                            {mostrarOpcoes && (
                                <ul className={styles.listaOpcoes}>
                                    {estadosFiltrados.length > 0 ? (
                                        estadosFiltrados.map((estadoObj, index) => {
                                            const [nome, sigla] = Object.entries(estadoObj)[0];
                                            return (
                                                <li
                                                    key={sigla}
                                                    onClick={() => selecionarEstado(sigla, nome)}
                                                    className={estadoSelecionado === sigla ? 'selecionado' : ''}
                                                >
                                                    {nome} ({sigla})
                                                </li>
                                            );
                                        })
                                    ) : (
                                        <li className={styles.nenhumaOpcao}>Nenhum estado encontrado</li>
                                    )}
                                </ul>
                            )}
                        </div>
                    </div>
                    <div className="d-flex align-self-end gap-1">
                        <Button onClick={limpar} color="transparent"><SiCcleaner size={30} color="blue" /></Button>
                        <Button color="transparent" ><FaSearch size={30} color="green" /></Button>
                    </div>
                </div>
            </form>
        </Container>
    )
}

export default Filtros