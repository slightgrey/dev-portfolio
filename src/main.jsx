import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/all";
import "./index.css";
import App from "./App.jsx";

// The intro animations assume the visitor starts at the hero, so don't let the
// browser restore the previous scroll position on reload (unless a #hash asks for one).
// ScrollTrigger caches history.scrollRestoration and re-applies it on refresh, so it
// has to be told via clearScrollMemory() rather than by setting the property directly.
if (!window.location.hash) {
  gsap.registerPlugin(ScrollTrigger);
  ScrollTrigger.clearScrollMemory("manual");
  window.scrollTo(0, 0);
}

const root = document.getElementById("vincent-app");

if (root) {
  createRoot(root).render(
    <StrictMode>
      <App />
    </StrictMode>
  );
}
