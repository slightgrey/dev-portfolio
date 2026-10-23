import { useEffect, useState } from "react";
import ReactLenis from "lenis/react";
import { useMediaQuery } from "react-responsive";
import Navbar from "./sections/Navbar";
import Hero from "./sections/Hero";
import ServiceSummary from "./sections/ServiceSummary";
import Services from "./sections/Services";
import About from "./sections/About";
import Works from "./sections/Works";
import ContactSummary from "./sections/ContactSummary";
import Contact from "./sections/Contact";
import { useData } from "./lib/data";

const App = () => {
  const { sections } = useData();
  const [isReady, setIsReady] = useState(false);
  const [shownProgress, setShownProgress] = useState(0);
  const reduceMotion = useMediaQuery({ query: "(prefers-reduced-motion: reduce)" });

  // Reveal the page once the self-hosted font is ready (capped), not when the 3D scene is.
  // The scene is a lazy chunk and drops in on its own, so waiting for it would only delay
  // the first paint of the content (and Largest Contentful Paint).
  useEffect(() => {
    let done = false;
    const reveal = () => {
      if (done) return;
      done = true;
      setShownProgress(100);
      setIsReady(true);
    };
    const tick = requestAnimationFrame(() => setShownProgress(70));
    const cap = setTimeout(reveal, 1500);
    (document.fonts?.ready ?? Promise.resolve()).then(reveal);
    return () => {
      done = true;
      cancelAnimationFrame(tick);
      clearTimeout(cap);
    };
  }, []);

  return (
    <ReactLenis root options={{ smoothWheel: !reduceMotion }}>
      <div className="relative w-full min-h-screen overflow-x-clip">
        {!isReady && (
          <div className="fixed inset-0 z-[999] flex flex-col items-center justify-center bg-black text-white transition-opacity duration-700 font-light">
            <p className="mb-4 text-xl tracking-widest animate-pulse">
              Loading {shownProgress}%
            </p>
            <div className="relative h-1 overflow-hidden rounded w-60 bg-white/20">
              <div
                className="absolute top-0 left-0 h-full transition-all duration-300 bg-white"
                style={{ width: `${shownProgress}%` }}
              ></div>
            </div>
          </div>
        )}
        <div
          className={`${
            isReady ? "opacity-100" : "opacity-0"
          } transition-opacity duration-1000`}
        >
          <Navbar />
          {sections.hero && <Hero />}
          {sections.service_summary && <ServiceSummary />}
          {sections.services && <Services />}
          {sections.about && <About />}
          {sections.works && <Works />}
          {sections.cta && <ContactSummary />}
          {sections.contact && <Contact />}
        </div>
      </div>
    </ReactLenis>
  );
};

export default App;
