import { useRef, useState } from "react";
import { Icon } from "@iconify/react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import AnimatedHeaderSection from "../components/AnimatedHeaderSection";
import { placeholderGradient, useData } from "../lib/data";

const ProjectImage = ({ src, alt, index, className }) =>
  src ? (
    <img src={src} alt={alt} className={className} />
  ) : (
    <div
      className={className}
      style={{ background: placeholderGradient(index) }}
      aria-hidden="true"
    />
  );

const Works = () => {
  const { worksHeader, projects } = useData();
  const listRef = useRef(null);
  const overlayRefs = useRef([]);
  const previewRef = useRef(null);
  const [currentIndex, setCurrentIndex] = useState(null);

  const mouse = useRef({ x: 0, y: 0 });
  const moveX = useRef(null);
  const moveY = useRef(null);

  useGSAP(
    () => {
      moveX.current = gsap.quickTo(previewRef.current, "x", {
        duration: 1.5,
        ease: "power3.out",
      });
      moveY.current = gsap.quickTo(previewRef.current, "y", {
        duration: 2,
        ease: "power3.out",
      });

      gsap.from(".work-project", {
        y: 100,
        opacity: 0,
        delay: 0.5,
        duration: 1,
        stagger: 0.3,
        ease: "back.out",
        scrollTrigger: {
          trigger: ".work-project",
        },
      });
    },
    { scope: listRef }
  );

  const handleMouseEnter = (index) => {
    if (window.innerWidth < 768) return;
    setCurrentIndex(index);

    const el = overlayRefs.current[index];
    if (!el) return;

    gsap.killTweensOf(el);
    gsap.fromTo(
      el,
      { clipPath: "polygon(0 100%, 100% 100%, 100% 100%, 0 100%)" },
      {
        clipPath: "polygon(0 0, 100% 0, 100% 100%, 0% 100%)",
        duration: 0.15,
        ease: "power2.out",
      }
    );

    gsap.to(previewRef.current, {
      opacity: 1,
      scale: 1,
      duration: 0.3,
      ease: "power2.out",
    });
  };

  const handleMouseLeave = (index) => {
    if (window.innerWidth < 768) return;
    setCurrentIndex(null);

    const el = overlayRefs.current[index];
    if (!el) return;

    gsap.killTweensOf(el);
    gsap.to(el, {
      clipPath: "polygon(0 100%, 100% 100%, 100% 100%, 0 100%)",
      duration: 0.2,
      ease: "power2.in",
    });

    gsap.to(previewRef.current, {
      opacity: 0,
      scale: 0.95,
      duration: 0.3,
      ease: "power2.out",
    });
  };

  const handleMouseMove = (e) => {
    if (window.innerWidth < 768) return;
    mouse.current.x = e.clientX + 24;
    mouse.current.y = e.clientY + 24;
    moveX.current(mouse.current.x);
    moveY.current(mouse.current.y);
  };

  const current = currentIndex !== null ? projects[currentIndex] : null;

  return (
    <section id="work" className="flex flex-col min-h-screen">
      <AnimatedHeaderSection
        subTitle={worksHeader.subTitle}
        title={worksHeader.title}
        text={worksHeader.text}
        textColor={"text-black"}
        withScrollTrigger={true}
      />
      <div
        ref={listRef}
        className="relative flex flex-col font-light"
        onMouseMove={handleMouseMove}
      >
        {projects.map((project, index) => {
          const Row = project.href ? "a" : "div";
          const linkProps = project.href
            ? { href: project.href, target: "_blank", rel: "noopener noreferrer" }
            : {};

          return (
            <Row
              key={project.id}
              {...linkProps}
              className="work-project relative flex flex-col gap-1 py-5 cursor-pointer group md:gap-0"
              onMouseEnter={() => handleMouseEnter(index)}
              onMouseLeave={() => handleMouseLeave(index)}
            >
              {/* overlay */}
              <div
                ref={(el) => {
                  overlayRefs.current[index] = el;
                }}
                className="absolute inset-0 hidden md:block duration-200 bg-black -z-10 clip-path"
              />

              {/* title */}
              <div className="flex justify-between px-10 text-black transition-all duration-500 md:group-hover:px-12 md:group-hover:text-white">
                <h3 className="lg:text-[32px] text-[26px] leading-none">
                  {project.name}
                </h3>
                <Icon icon="lucide:arrow-up-right" className="md:size-6 size-5" />
              </div>
              {/* divider */}
              <div className="w-full h-0.5 bg-black/80" />
              {/* frameworks */}
              <div className="flex flex-wrap px-10 text-xs leading-loose uppercase transition-all duration-500 md:text-sm gap-x-5 md:group-hover:px-12">
                {project.frameworks.map((framework) => (
                  <p
                    key={framework}
                    className="text-black transition-colors duration-500 md:group-hover:text-white"
                  >
                    {framework}
                  </p>
                ))}
              </div>
              {/* mobile preview image */}
              <div className="relative flex items-center justify-center px-10 md:hidden h-[400px]">
                <ProjectImage
                  src={project.bgImage}
                  alt=""
                  index={index + 7}
                  className="object-cover w-full h-full rounded-md brightness-50"
                />
                {project.image && (
                  <img
                    src={project.image}
                    alt={project.name}
                    className="absolute bg-center px-14 rounded-xl"
                  />
                )}
              </div>
            </Row>
          );
        })}
        {/* desktop floating preview image */}
        <div
          ref={previewRef}
          className="fixed -top-2/6 left-0 z-50 overflow-hidden border-8 border-black pointer-events-none w-[960px] md:block hidden opacity-0"
          aria-hidden="true"
        >
          {current && (
            <ProjectImage
              src={current.image}
              alt=""
              index={currentIndex}
              className="object-cover w-full h-full aspect-video"
            />
          )}
        </div>
      </div>
    </section>
  );
};

export default Works;
