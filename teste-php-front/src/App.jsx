import { useEffect, useState } from 'react'
import { BrowserRouter, Routes, Route } from 'react-router-dom'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'
import Home from './Home'
import axios from 'axios'

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
        </Routes>
      </BrowserRouter>
    </>
  )
}

export default App
