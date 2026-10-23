import { useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { DoubleSide, Vector2 } from "three";

// Built in code to match the original Planet.glb (not shipped: the source repo has no license).
// Values measured from that file: sphere = glossy light grey non-metal, moon and ring = gold metal,
// ring = flat band (radius 0.931–0.975, height 0.041) lying in the XZ plane, scaled 2×.
const SPHERE = { color: "#e7e7e7", roughness: 0.21, metalness: 0.01, side: DoubleSide };
const GOLD = { color: "#dba34f", roughness: 0.22, metalness: 1, side: DoubleSide };

// Cross-section of the ring band (x = radius, y = height), with a slightly bevelled top edge.
const RING_PROFILE = [
  [0.931, 0],
  [0.975, 0],
  [0.975, 0.033],
  [0.969, 0.041],
  [0.937, 0.041],
  [0.931, 0.033],
  [0.931, 0],
].map(([x, y]) => new Vector2(x, y));

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
          <meshStandardMaterial {...SPHERE} />
        </mesh>
        <mesh
          castShadow
          receiveShadow
          position={[0.647, 1.03, -0.724]}
          rotation={[0, 0, 0.741]}
          scale={0.223}
        >
          <sphereGeometry args={[1, 48, 48]} />
          <meshStandardMaterial {...GOLD} />
        </mesh>
      </group>
      <mesh
        ref={ringContainer}
        castShadow
        receiveShadow
        rotation={[-0.124, 0.123, -0.778]}
        scale={2}
      >
        {/* Lathe spins the profile around Y, so the band lies flat in XZ like the original. */}
        <latheGeometry args={[RING_PROFILE, 192]} />
        <meshStandardMaterial {...GOLD} />
      </mesh>
    </group>
  );
}
