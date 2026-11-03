import fs from "node:fs";
import path from "node:path";
import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import tailwindcss from "@tailwindcss/vite";

const DEV_ORIGIN = "http://localhost:5173";
const hotFile = path.resolve(import.meta.dirname, "hot");

// Writes ./hot while the dev server runs so inc/vite.php loads assets from it instead of dist/.
function wordpressHotFile() {
  const clean = () => fs.existsSync(hotFile) && fs.rmSync(hotFile);
  return {
    name: "vincent-wordpress-hot-file",
    apply: "serve",
    configureServer(server) {
      server.httpServer?.once("listening", () => {
        fs.writeFileSync(hotFile, DEV_ORIGIN);
      });
      process.on("exit", clean);
      process.on("SIGINT", () => process.exit());
      process.on("SIGTERM", () => process.exit());
    },
  };
}

export default defineConfig({
  base: "./",
  plugins: [tailwindcss(), react(), wordpressHotFile()],
  publicDir: false,
  // No index.html, so tell the dep optimizer where to crawl; otherwise it re-optimizes mid-load (504s).
  optimizeDeps: {
    entries: ["src/main.jsx", "src/neumorphism/main.jsx"],
  },
  server: {
    host: "localhost",
    port: 5173,
    strictPort: true,
    cors: true,
    origin: DEV_ORIGIN,
  },
  build: {
    outDir: "dist",
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: ["src/main.jsx", "src/neumorphism/main.jsx", "src/site.css", "src/admin.css"],
    },
  },
});
