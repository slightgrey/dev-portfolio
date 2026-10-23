import { useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";

// Planet, moon and ring built from primitives (no external model), with the original intro timeline.
export function Planet(props) {
  const shapeContainer = useRef(null);
  const spheresContainer = useRef(null);
  const ringContainer = useRef(null);

  useGSAP(() => {
    const tl = gsap.timeline();
    tl.from(shapeContainer.current.position, {
      y: 5,
      duration: 3,
      ease: "circ.out",
    });
    tl.from(
      spheresContainer.current.rotation,
      {
        x: 0,
        y: Math.PI,
        z: -Math.PI,
        duration: 10,
        ease: "power1.inOut",
      },
      "-=25%"
    );
    tl.from(
      ringContainer.current.rotation,
      {
        x: 0.8,
        y: 0,
        z: 0,
        duration: 10,
        ease: "power1.inOut",
      },
      "<"
    );
  }, []);

  return (
    <group ref={shapeContainer} {...props} dispose={null}>
      <group ref={spheresContainer}>
        <mesh castShadow receiveShadow rotation={[0, 0, 0.741]}>
          <sphereGeometry args={[1, 96, 96]} />
          <meshStandardMaterial color="#393632" roughness={0.35} metalness={0.45} />
        </mesh>
        <mesh
          castShadow
          receiveShadow
          position={[0.647, 1.03, -0.724]}
          rotation={[0, 0, 0.741]}
          scale={0.223}
        >
          <sphereGeometry args={[1, 48, 48]} />
          <meshStandardMaterial color="#cfa355" roughness={0.25} metalness={0.85} />
        </mesh>
      </group>
      <mesh
        ref={ringContainer}
        castShadow
        receiveShadow
        rotation={[-0.124, 0.123, -0.778]}
        scale={2}
      >
        <torusGeometry args={[0.8, 0.012, 24, 160]} />
        <meshStandardMaterial color="#cfa355" roughness={0.25} metalness={0.85} />
      </mesh>
    </group>
  );
}
