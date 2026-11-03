import Icon from "./Icon.jsx";
import StackPanel from "./StackPanel.jsx";
import { useData } from "../lib/data.js";
import { stagger, useReveal } from "../lib/useReveal.js";

const Hero = () => {
  const { nm, sections, services } = useData();
  const { eyebrow, title, lead, stack, terminal } = nm.hero;
  const ref = useReveal();

  return (
    <section ref={ref} className="pt-10 pb-[72px] md:pt-24 md:pb-32">
      <div className="mx-auto grid max-w-site grid-cols-1 items-center gap-10 px-4 md:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] md:gap-16 md:px-6">
        <div>
          {eyebrow && <p className="nm-label nm-reveal">{eyebrow}</p>}
          <h1 className="nm-reveal mt-4 text-[40px] leading-[1.1] font-bold tracking-[-0.02em] md:text-[64px] md:leading-[1.05]" style={stagger(1)}>
            {title}
          </h1>
          {lead && (
            <p className="nm-reveal mt-6 max-w-[54ch] text-[17px] leading-[1.6] text-ink-soft" style={stagger(2)}>
              {lead}
            </p>
          )}
          <div className="nm-reveal mt-10 flex flex-col gap-4 md:flex-row md:flex-wrap" style={stagger(3)}>
            {sections.contact && (
              <a href="#contact" className="nm-btn nm-btn-primary w-full md:w-auto">
                Start a project
                <Icon name="arrow" size={18} />
              </a>
            )}
            {sections.services && services.length > 0 && (
              <a href="#services" className="nm-btn nm-btn-ghost w-full md:w-auto">
                See services
              </a>
            )}
          </div>
        </div>
        <StackPanel stack={stack} terminal={terminal} />
      </div>
    </section>
  );
};

export default Hero;
