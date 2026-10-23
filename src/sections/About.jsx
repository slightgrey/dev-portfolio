import { useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import AnimatedHeaderSection from "../components/AnimatedHeaderSection";
import { AnimatedTextLines } from "../components/AnimatedTextLines";
import { placeholderGradient, useData } from "../lib/data";

const About = () => {
  const { about, sections } = useData();
  const sectionRef = useRef(null);
  const imgRef = useRef(null);

  useGSAP(() => {
    gsap.to(sectionRef.current, {
      scale: 0.95,
      scrollTrigger: {
        trigger: sectionRef.current,
        start: "bottom 80%",
        end: "bottom 20%",
        scrub: true,
      },
      ease: "power1.inOut",
    });

    gsap.set(imgRef.current, {
      clipPath: "polygon(0 100%, 100% 100%, 100% 100%, 0% 100%)",
    });
    gsap.to(imgRef.current, {
      clipPath: "polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)",
      duration: 2,
      ease: "power4.out",
      scrollTrigger: { trigger: imgRef.current },
    });
  });

  return (
    <section
      id="about"
      ref={sectionRef}
      className={`min-h-screen bg-black rounded-b-4xl ${
        sections.services ? "" : "rounded-t-4xl"
      }`}
    >
      <AnimatedHeaderSection
        subTitle={about.subTitle}
        title={about.title}
        text={about.text}
        textColor={"text-white"}
        withScrollTrigger={true}
      />
      <div className="flex flex-col items-center justify-between gap-16 px-10 pb-16 text-xl font-light tracking-wide lg:flex-row md:text-2xl lg:text-3xl text-white/60">
        {about.image ? (
          <img
            ref={imgRef}
            src={about.image}
            alt={about.imageAlt || about.title}
            className="w-md rounded-3xl"
          />
        ) : (
          <div
            ref={imgRef}
            className="w-full max-w-md aspect-[4/5] rounded-3xl shrink-0"
            style={{ background: placeholderGradient(3) }}
            aria-hidden="true"
          />
        )}
        <AnimatedTextLines text={about.body} className={"w-full"} />
      </div>
    </section>
  );
};

export default About;
