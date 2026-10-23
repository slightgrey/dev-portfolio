import { lazy, Suspense, useEffect, useRef, useState } from "react";
import { useMediaQuery } from "react-responsive";
import AnimatedHeaderSection from "../components/AnimatedHeaderSection";
import ScrollHint from "../components/ScrollHint";
import { useData } from "../lib/data";

// Code-split: three.js, React Three Fiber and drei load in their own chunk.
const HeroScene = lazy(() => import("../components/HeroScene"));

const Hero = () => {
  const { hero } = useData();
  const isMobile = useMediaQuery({ maxWidth: 853 });
  const sectionRef = useRef(null);
  const [show3D, setShow3D] = useState(false);

  // Mount the 3D scene once the browser is idle after the first paint, so downloading and
  // parsing three.js doesn't compete with getting the text on screen.
  useEffect(() => {
    if ("requestIdleCallback" in window) {
      const id = window.requestIdleCallback(() => setShow3D(true), { timeout: 2000 });
      return () => window.cancelIdleCallback(id);
    }
    const id = setTimeout(() => setShow3D(true), 1200);
    return () => clearTimeout(id);
  }, []);

  return (
    <section
      id="home"
      ref={sectionRef}
      className="relative flex flex-col justify-end min-h-screen"
    >
      <AnimatedHeaderSection
        subTitle={hero.subTitle}
        title={hero.title}
        text={hero.text}
        textColor={"text-black"}
        as="h1"
      />
      {hero.scroll && <ScrollHint text={hero.scroll} sectionRef={sectionRef} />}
      <figure
        className="absolute inset-0 -z-50"
        style={{ width: "100vw", height: "100vh" }}
        aria-hidden="true"
      >
        {show3D && (
          <Suspense fallback={null}>
            <HeroScene scale={isMobile ? 0.7 : 1} />
          </Suspense>
        )}
      </figure>
    </section>
  );
};

export default Hero;
