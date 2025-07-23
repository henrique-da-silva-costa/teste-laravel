import { useRef, useState } from 'react'
import { Button, Container, Input } from 'reactstrap';
import styles from "../stilos.module.css"

const Filtros = ({ paginaAtual, pegarDados = () => { } }) => {
    const [formulario, setFormulario] = useState({});
    const formRef = useRef();

    const changeformulario = (e) => {
        const { name, value, files } = e.target;

        setFormulario({
            ...formulario, [name]: name === "img" ? files[0] : value
        });

    }

    const filtrar = (e) => {
        e.preventDefault();
        pegarDados(paginaAtual, formulario);
        localStorage.setItem("filtros", JSON.stringify(formulario));
    }

    const limpar = (e) => {
        e.preventDefault();
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
                        <label>Nome</label>
                        <Input name="nome" onChange={changeformulario} />
                    </div>
                    <div className={styles.divFilhaFiltro}>
                        <label>Sigla do Estado</label>
                        <Input name="estado" onChange={changeformulario} />
                    </div>
                    <div className="d-flex align-self-end gap-1">
                        <Button onClick={limpar} color="secondary">Limpar</Button>
                        <Button color="primary">Filtrar</Button>
                    </div>
                </div>
            </form>
        </Container>
    )
}

export default Filtros