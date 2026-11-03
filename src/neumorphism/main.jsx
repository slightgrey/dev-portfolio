// Entry for the Neumorphism design, loaded instead of src/main.jsx when it's the active
// Site design (inc/vite.php → vincent_app_entry()).
import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import "./neumorphism.css";
import App from "./App.jsx";

const root = document.getElementById("vincent-app");

if (root) {
  createRoot(root).render(
    <StrictMode>
      <App />
    </StrictMode>
  );
}
