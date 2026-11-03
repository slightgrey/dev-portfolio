import Icon from "./Icon.jsx";
import { useData } from "../lib/data.js";
import { stagger, useReveal } from "../lib/useReveal.js";

const hobbyIcons = ["bolt", "book", "headphones"];

const About = () => {
  const { eyebrow, title, text, values, hobbies } = useData().nm.about;
  const ref = useReveal();

  return (
    <section ref={ref} id="about" className="pb-20 md:pb-32">
      <div className="mx-auto max-w-site px-4 md:px-6">
        <div className="nm-raised nm-reveal rounded-md px-5 py-8 md:rounded-lg md:px-12 md:py-16">
          <div className="grid grid-cols-1 items-start gap-10 md:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] md:gap-16">
            <div>
              {eyebrow && <p className="nm-label">{eyebrow}</p>}
              <h2 className="mt-4 mb-10 text-[28px] leading-[1.2] font-bold tracking-[-0.01em] md:mb-5 md:text-4xl">{title}</h2>
              {text && <p className="max-w-[60ch] leading-[1.6] text-ink-soft">{text}</p>}
              {values.length > 0 && (
                <ul className="mt-8 flex flex-wrap gap-3" aria-label="Values">
                  {values.map((value) => (
                    <li key={value} className="nm-pressed inline-flex min-h-9 items-center rounded-full px-3.5 text-[13px] font-medium">
                      {value}
                    </li>
                  ))}
                </ul>
              )}
            </div>

            {hobbies.length > 0 && (
              <div className="nm-pressed nm-reveal rounded-md p-8" style={stagger(1)}>
                <h3 className="nm-label mb-5">When I’m not shipping</h3>
                <ul className="flex flex-col gap-4">
                  {hobbies.map((hobby, i) => (
                    <li key={hobby} className="flex items-center gap-3.5">
                      <span className="nm-raised inline-flex size-11 flex-none items-center justify-center rounded-sm text-ink">
                        <Icon name={hobbyIcons[i % hobbyIcons.length]} strokeWidth={1.75} />
                      </span>
                      <span>{hobby}</span>
                    </li>
                  ))}
                </ul>
              </div>
            )}
          </div>
        </div>
      </div>
    </section>
  );
};

export default About;
