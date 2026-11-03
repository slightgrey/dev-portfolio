import ServiceRow from "./ServiceRow.jsx";
import { useData } from "../lib/data.js";
import { useReveal } from "../lib/useReveal.js";

const Services = () => {
  const { nm, services } = useData();
  const ref = useReveal();

  return (
    <section ref={ref} id="services" className="pb-20 md:pb-32">
      <div className="mx-auto max-w-site px-4 md:px-6">
        {nm.services.eyebrow && <p className="nm-label nm-reveal">{nm.services.eyebrow}</p>}
        <h2 className="nm-reveal mt-4 mb-10 max-w-[26ch] text-[28px] leading-[1.2] font-bold tracking-[-0.01em] md:mb-20 md:text-4xl">
          {nm.services.title}
        </h2>
        <div className="flex flex-col gap-16 md:gap-24">
          {services.map((service, i) => (
            <ServiceRow key={`${service.title}-${i}`} service={service} index={i} />
          ))}
        </div>
      </div>
    </section>
  );
};

export default Services;
