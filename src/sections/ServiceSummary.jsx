import { Fragment, useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/all";
import { useData } from "../lib/data";
gsap.registerPlugin(ScrollTrigger);

// Starting offset and scroll drift per row, cycled when there are more than four rows.
const rowStyles = [
  { className: "", xPercent: 20 },
  { className: "translate-x-16", xPercent: -30 },
  { className: "-translate-x-48", xPercent: 40 },
  { className: "translate-x-48", xPercent: -100 },
];

const wordClass = (words, index) => {
  if (words.length === 2 && index === 0) return "font-normal";
  if (words.length > 2 && index === Math.floor(words.length / 2)) return "italic";
  return "";
};

const ServiceSummary = () => {
  const { serviceSummary } = useData();
  const rowRefs = useRef([]);

  useGSAP(() => {
    rowRefs.current.forEach((el, index) => {
      if (!el) return;
      gsap.to(el, {
        xPercent: rowStyles[index % rowStyles.length].xPercent,
        scrollTrigger: {
          trigger: el,
          scrub: true,
        },
      });
    });
  }, [serviceSummary]);

  if (serviceSummary.length === 0) return null;

  return (
    <section className="mt-20 overflow-hidden font-light leading-snug text-center mb-42 contact-text-responsive">
      {serviceSummary.map((words, rowIndex) => (
        <div
          key={rowIndex}
          ref={(el) => (rowRefs.current[rowIndex] = el)}
          className={`flex items-center justify-center gap-3 ${
            rowStyles[rowIndex % rowStyles.length].className
          }`}
        >
          {words.map((word, index) => (
            <Fragment key={index}>
              {index > 0 && <div className="w-10 h-1 md:w-32 bg-gold" />}
              <p className={wordClass(words, index)}>{word}</p>
            </Fragment>
          ))}
        </div>
      ))}
    </section>
  );
};

export default ServiceSummary;
