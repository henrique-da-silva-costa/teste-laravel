import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  server: {
    port: 5176,
    host: '0.0.0.0', // Essencial para Docker
    hmr: {
      clientPort: 5176, // Importante para o HMR funcionar
    },
    watch: {
      usePolling: true, // Necessário para alguns sistemas de arquivos
    }
  }
})