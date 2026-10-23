import { useRef } from "react";
import { useMediaQuery } from "react-responsive";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import AnimatedHeaderSection from "../components/AnimatedHeaderSection";
import { useData } from "../lib/data";

const Services = () => {
  const { servicesHeader, services, sections } = useData();
  const serviceRefs = useRef([]);
  const isDesktop = useMediaQuery({ minWidth: "48rem" }); //768px

  useGSAP(() => {
    serviceRefs.current.forEach((el) => {
      if (!el) return;
      gsap.from(el, {
        y: 200,
        scrollTrigger: {
          trigger: el,
          start: "top 80%",
        },
        duration: 1,
        ease: "circ.out",
      });
    });
  }, []);

  return (
    <section
      id="services"
      className={`min-h-screen bg-black rounded-t-4xl ${
        sections.about ? "" : "rounded-b-4xl"
      }`}
    >
      <AnimatedHeaderSection
        subTitle={servicesHeader.subTitle}
        title={servicesHeader.title}
        text={servicesHeader.text}
        textColor={"text-white"}
        withScrollTrigger={true}
      />
      {services.map((service, index) => (
        <div
          ref={(el) => (serviceRefs.current[index] = el)}
          key={index}
          className="sticky px-10 pt-6 pb-12 text-white bg-black border-t-2 border-white/30"
          style={
            isDesktop
              ? {
                  top: `calc(10vh + ${index * 5}em)`,
                  marginBottom: `${(services.length - index - 1) * 5}rem`,
                }
              : { top: 0 }
          }
        >
          <div className="flex items-center justify-between gap-4 font-light">
            <div className="flex flex-col gap-6">
              <h3 className="text-4xl lg:text-5xl">{service.title}</h3>
              {service.description && (
                <p className="text-xl leading-relaxed tracking-widest lg:text-2xl text-white/60 text-pretty">
                  {service.description}
                </p>
              )}
              <div className="flex flex-col gap-2 text-2xl sm:gap-4 lg:text-3xl text-white/80">
                {service.items.map((item, itemIndex) => (
                  <div key={`item-${index}-${itemIndex}`}>
                    <h4 className="flex flex-wrap items-baseline">
                      <span className="mr-12 text-lg text-white/30">
                        {String(itemIndex + 1).padStart(2, "0")}
                      </span>
                      {item.title}
                      {item.description && (
                        <span className="hidden ml-4 text-base lg:inline text-white/40">
                          {item.description}
                        </span>
                      )}
                    </h4>
                    {itemIndex < service.items.length - 1 && (
                      <div className="w-full h-px my-2 bg-white/30" />
                    )}
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      ))}
    </section>
  );
};

export default Services;
