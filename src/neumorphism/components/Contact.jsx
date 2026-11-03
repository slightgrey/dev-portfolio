import ContactForm from "./ContactForm.jsx";
import SocialLinks from "./SocialLinks.jsx";
import { useData } from "../lib/data.js";
import { useReveal } from "../lib/useReveal.js";

const Contact = () => {
  const { nm, socials } = useData();
  const { eyebrow, title, text } = nm.contact;
  const ref = useReveal();

  return (
    <section ref={ref} id="contact" className="pb-24">
      <div className="mx-auto grid max-w-site grid-cols-1 items-start gap-10 px-4 md:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] md:gap-16 md:px-6">
        <div>
          <div className="nm-reveal">
            {eyebrow && <p className="nm-label">{eyebrow}</p>}
            <h2 className="mt-4 mb-10 text-[28px] leading-[1.2] font-bold md:mb-4 md:text-[32px]">{title}</h2>
            {text && <p className="leading-[1.6] text-ink-soft">{text}</p>}
          </div>
          <ContactForm />
        </div>
        {socials.length > 0 && <SocialLinks />}
      </div>
    </section>
  );
};

export default Contact;
