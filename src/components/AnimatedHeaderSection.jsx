import { useLayoutEffect, useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { AnimatedTextLines } from "./AnimatedTextLines";

// Allow a line break after punctuation so titles like "vince.layte" can wrap on narrow screens.
const withBreaks = (word) =>
  word
    .split(/(?<=[.\-_/@])/)
    .flatMap((segment, i) => (i ? [<wbr key={i} />, segment] : [segment]));

// Last resort for a single word that still doesn't fit: scale the font down until it does.
const useFitWidth = (ref, title) => {
  useLayoutEffect(() => {
    const el = ref.current;
    if (!el) return;

    const fit = () => {
      el.style.fontSize = "";
      const overflow = el.scrollWidth - el.clientWidth;
      if (overflow > 1) {
        const base = parseFloat(getComputedStyle(el).fontSize);
        el.style.fontSize = `${Math.floor(base * (el.clientWidth / el.scrollWidth) * 0.98)}px`;
      }
    };

    fit();
    document.fonts?.ready.then(fit);
    const observer = new ResizeObserver(fit);
    observer.observe(el.parentElement);
    return () => observer.disconnect();
  }, [ref, title]);
};

const AnimatedHeaderSection = ({
  subTitle = "",
  title = "",
  text = "",
  textColor,
  withScrollTrigger = false,
  as = "h2",
}) => {
  const Heading = as;
  const contextRef = useRef(null);
  const headerRef = useRef(null);
  const headingRef = useRef(null);
  const titleParts = title.includes(" ") ? title.split(" ") : [title];
  useFitWidth(headingRef, title);

  useGSAP(() => {
    const tl = gsap.timeline({
      scrollTrigger: withScrollTrigger
        ? {
            trigger: contextRef.current,
          }
        : undefined,
    });
    tl.from(contextRef.current, {
      y: "50vh",
      duration: 1,
      ease: "circ.out",
    });
    tl.from(
      headerRef.current,
      {
        opacity: 0,
        y: "200",
        duration: 1,
        ease: "circ.out",
      },
      "<+0.2"
    );
  }, []);

  return (
    <div ref={contextRef}>
      <div style={{ clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)" }}>
        <div
          ref={headerRef}
          className="flex flex-col justify-center gap-12 pt-16 sm:gap-16"
        >
          <p
            className={`text-sm font-light tracking-[0.5rem] uppercase px-10 ${textColor}`}
          >
            {subTitle}
          </p>
          <div className="px-10">
            <Heading
              ref={headingRef}
              // A little room below the baseline so the clip-path and divider never touch the letters.
              className={`flex flex-col gap-6 pb-[0.1em] uppercase banner-text-responsive sm:gap-8 md:block ${textColor}`}
            >
              {titleParts.map((part, index) => (
                <span key={index}>{withBreaks(part)} </span>
              ))}
            </Heading>
          </div>
        </div>
      </div>
      <div className={`relative px-10 ${textColor}`}>
        <div className="absolute inset-x-0 border-t-2" />
        <div className="py-12 sm:py-16 text-end">
          <AnimatedTextLines
            text={text}
            className={`font-light uppercase value-text-responsive ${textColor}`}
          />
        </div>
      </div>
    </div>
  );
};

export default AnimatedHeaderSection;
