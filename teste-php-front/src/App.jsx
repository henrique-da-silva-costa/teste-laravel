import { BrowserRouter, Routes, Route } from 'react-router-dom'
import './App.css'
import Home from './Home'
import Despesas from './componentes/Despesas'
import Orgaos from './componentes/Orgaos'

function App() {
  return (
    <>
      <BrowserRouter>
        <Routes>
          <Route element={<Home />} path="/:pagina?" />
          <Route element={<Despesas />} path="/despesas/:id/:nome/:pagina" />
          <Route element={<Orgaos />} path="/orgaos/:id/:nome/:pagina" />
        </Routes>
      </BrowserRouter>
    </>
  )
}

export default App
