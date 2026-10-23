import { useEffect, useState } from "react";
import ReactLenis from "lenis/react";
import { useProgress } from "@react-three/drei";
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
  const { progress, active } = useProgress();
  const [isReady, setIsReady] = useState(false);
  const reduceMotion = useMediaQuery({ query: "(prefers-reduced-motion: reduce)" });

  // The planet is built in code, so there may be nothing to load: become ready when
  // loading finishes, when no loader is active shortly after mount, or after a fallback timeout.
  useEffect(() => {
    if (progress >= 100) {
      setIsReady(true);
      return;
    }
    const idle = !active ? setTimeout(() => setIsReady(true), 400) : null;
    const fallback = setTimeout(() => setIsReady(true), 6000);
    return () => {
      clearTimeout(idle);
      clearTimeout(fallback);
    };
  }, [progress, active]);

  const shownProgress = isReady ? 100 : Math.floor(progress);

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
