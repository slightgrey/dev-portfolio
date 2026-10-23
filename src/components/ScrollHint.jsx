import { useId, useRef } from "react";
import { Icon } from "@iconify/react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/all";
import { useLenis } from "lenis/react";
gsap.registerPlugin(ScrollTrigger);

// Scroll-down cue for the hero: rotating text badge on desktop, small label below lg.
// Clicking scrolls past `sectionRef`. Fades in after the intro and out once the visitor scrolls.
const ScrollHint = ({ text, sectionRef }) => {
  const pathId = useId();
  const fadeRef = useRef(null);
  const enterRef = useRef(null);
  const lenis = useLenis();

  useGSAP(() => {
    // Separate elements so the entry tween and the scrubbed exit don't fight over opacity.
    gsap.from(enterRef.current, {
      autoAlpha: 0,
      y: 24,
      duration: 1,
      delay: 1.8,
      ease: "power3.out",
    });
    gsap.to(fadeRef.current, {
      autoAlpha: 0,
      ease: "none",
      scrollTrigger: {
        trigger: sectionRef.current,
        start: "top top",
        end: "+=240",
        scrub: true,
      },
    });
  }, []);

  const scrollNext = () => {
    const section = sectionRef.current;
    if (!section) return;
    const target = section.offsetTop + section.offsetHeight;
    if (lenis) {
      lenis.scrollTo(target, { duration: 1.6 });
    } else {
      window.scrollTo({ top: target, behavior: "smooth" });
    }
  };

  const label = `${text} • ${text} • `;

  return (
    <div ref={fadeRef}>
      <div ref={enterRef}>
        {/* Desktop: rotating text badge in the empty bottom-left corner. */}
        <button
          type="button"
          onClick={scrollNext}
          aria-label={text}
          className="group absolute hidden lg:flex items-center justify-center rounded-full cursor-pointer bottom-10 left-10 size-32 text-black transition-colors duration-500 hover:bg-black hover:text-white"
        >
          <svg
            viewBox="0 0 100 100"
            className="absolute inset-0 size-full animate-spin-slow motion-reduce:animate-none"
            aria-hidden="true"
          >
            <defs>
              <path
                id={pathId}
                d="M50,50 m-38,0 a38,38 0 1,1 76,0 a38,38 0 1,1 -76,0"
              />
            </defs>
            <text
              className="fill-current uppercase"
              style={{ fontSize: "8.5px", letterSpacing: "0.12em" }}
            >
              <textPath href={`#${pathId}`} textLength="238" lengthAdjust="spacing">
                {label}
              </textPath>
            </text>
          </svg>
          <span className="flex items-center justify-center rounded-full size-12 border border-current transition-transform duration-500 group-hover:scale-110">
            <Icon
              icon="lucide:arrow-down"
              className="size-5 animate-nudge motion-reduce:animate-none"
            />
          </span>
        </button>

        {/* Mobile/tablet: compact label at the bottom centre. */}
        <button
          type="button"
          onClick={scrollNext}
          className="absolute flex lg:hidden flex-col items-center gap-1 -translate-x-1/2 cursor-pointer bottom-3 left-1/2 text-black/70"
        >
          <span className="text-[10px] font-light uppercase tracking-[0.4em] whitespace-nowrap">
            {text}
          </span>
          <Icon
            icon="lucide:chevron-down"
            className="size-5 animate-nudge motion-reduce:animate-none"
            aria-hidden="true"
          />
        </button>
      </div>
    </div>
  );
};

export default ScrollHint;
