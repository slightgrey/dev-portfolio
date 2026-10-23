import { useRef } from "react";
import { Canvas } from "@react-three/fiber";
import { Environment, Float, Lightformer } from "@react-three/drei";
import { useMediaQuery } from "react-responsive";
import { Planet } from "../components/Planet";
import AnimatedHeaderSection from "../components/AnimatedHeaderSection";
import ScrollHint from "../components/ScrollHint";
import { useData } from "../lib/data";

const Hero = () => {
  const { hero } = useData();
  const isMobile = useMediaQuery({ maxWidth: 853 });
  const sectionRef = useRef(null);

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
        <Canvas
          shadows
          camera={{ position: [0, 0, -10], fov: 17.5, near: 1, far: 20 }}
        >
          <ambientLight intensity={0.5} />
          <Float speed={0.5}>
            <Planet scale={isMobile ? 0.7 : 1} />
          </Float>
          <Environment resolution={256}>
            <group rotation={[-Math.PI / 3, 4, 1]}>
              <Lightformer form={"circle"} intensity={2} position={[0, 5, -9]} scale={10} />
              <Lightformer form={"circle"} intensity={2} position={[0, 3, 1]} scale={10} />
              <Lightformer form={"circle"} intensity={2} position={[-5, -1, -1]} scale={10} />
              <Lightformer form={"circle"} intensity={2} position={[10, 1, 0]} scale={16} />
            </group>
          </Environment>
        </Canvas>
      </figure>
    </section>
  );
};

export default Hero;
