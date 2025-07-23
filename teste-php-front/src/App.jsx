import { useEffect, useState } from 'react'
import { BrowserRouter, Routes, Route } from 'react-router-dom'
import './App.css'
import Home from './Home'
import axios from 'axios'
import Despesas from './componentes/Despesas'

function App() {

  useEffect(() => {
    axios.get("http://localhost:80/sincronizar").then((res) => {
      if (!res.data.erro) {
        console.log(res.data.msg)
      }
    }).catch((err) => {
      console.log(err);
    })

  }, []);

  return (
    <>
      <BrowserRouter>
        <Routes>
          <Route element={<Home />} path="/" />
          <Route element={<Despesas />} path="/despesas/:id" />
        </Routes>
      </BrowserRouter>
    </>
  )
}

export default App
