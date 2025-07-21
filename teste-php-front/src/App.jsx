import { useState } from 'react'
import { BrowserRouter, Routes, Route } from 'react-router-dom'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'
import Home from './componentes/Home'

function App() {
  const [count, setCount] = useState(0)

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
