import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/all";
import { useRef } from "react";
gsap.registerPlugin(ScrollTrigger);

export const AnimatedTextLines = ({ text = "", className }) => {
  const containerRef = useRef(null);
  const lineRefs = useRef([]);
  const lines = text.split("\n").filter((line) => line.trim() !== "");

  useGSAP(
    () => {
      const els = lineRefs.current.filter(Boolean);
      if (els.length > 0) {
        gsap.from(els, {
          y: 100,
          opacity: 0,
          duration: 1,
          stagger: 0.3,
          ease: "back.out",
          scrollTrigger: {
            trigger: containerRef.current,
          },
        });
      }
    },
    { scope: containerRef, dependencies: [text] }
  );

  return (
    <div ref={containerRef} className={className}>
      {lines.map((line, index) => (
        <span
          key={index}
          ref={(el) => (lineRefs.current[index] = el)}
          className="block leading-relaxed tracking-wide text-pretty"
        >
          {line.trim()}
        </span>
      ))}
    </div>
  );
};
