import { Canvas } from "@react-three/fiber";
import { Environment, Float, Lightformer } from "@react-three/drei";
import { Planet } from "./Planet";

// Everything three.js lives in this file. Hero.jsx loads it with React.lazy, so the
// 3D libraries are a separate chunk that downloads after the page has rendered.
const HeroScene = ({ scale = 1 }) => (
  <Canvas shadows camera={{ position: [0, 0, -10], fov: 17.5, near: 1, far: 20 }}>
    <ambientLight intensity={0.5} />
    <Float speed={0.5}>
      <Planet scale={scale} />
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
);

export default HeroScene;
