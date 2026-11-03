import { useData } from "../lib/data.js";
import { stagger, useReveal } from "../lib/useReveal.js";

const Cta = () => {
  const { nm, sections } = useData();
  const ref = useReveal();

  return (
    <section ref={ref} className="pb-20 md:pb-32">
      <div className="mx-auto max-w-site px-4 text-center md:px-6">
        {nm.cta.eyebrow && <p className="nm-label nm-reveal">{nm.cta.eyebrow}</p>}
        <h2 className="nm-reveal mx-auto mt-4 mb-10 max-w-[22ch] text-[28px] leading-[1.15] font-bold tracking-[-0.02em] md:text-[44px]" style={stagger(1)}>
          {nm.cta.title}
        </h2>
        {sections.contact && (
          <a href="#contact" className="nm-btn nm-btn-primary nm-reveal min-h-14 w-full px-8 text-base md:w-auto" style={stagger(2)}>
            Contact me
          </a>
        )}
      </div>
    </section>
  );
};

export default Cta;
