import { useMemo, useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import AnimatedHeaderSection from "../components/AnimatedHeaderSection";
import Marquee from "../components/Marquee";
import { useData } from "../lib/data";

const Contact = () => {
  const { contact, socials } = useData();
  const linksRef = useRef(null);
  const items = useMemo(
    () => (contact.marquee ? Array(5).fill(contact.marquee) : []),
    [contact.marquee]
  );

  useGSAP(
    () => {
      gsap.from(".social-link", {
        y: 100,
        opacity: 0,
        delay: 0.5,
        duration: 1,
        stagger: 0.3,
        ease: "back.out",
        scrollTrigger: {
          trigger: ".social-link",
        },
      });
    },
    { scope: linksRef }
  );

  return (
    <section
      id="contact"
      className="flex flex-col justify-between min-h-screen bg-black"
    >
      <div>
        <AnimatedHeaderSection
          subTitle={contact.subTitle}
          title={contact.title}
          text={contact.text}
          textColor={"text-white"}
          withScrollTrigger={true}
        />
        <div
          ref={linksRef}
          className="flex px-10 font-light text-white uppercase lg:text-[32px] text-[26px] leading-none mb-10"
        >
          <div className="flex flex-col w-full gap-10">
            {contact.email && (
              <div className="social-link">
                <h3>E-mail</h3>
                <div className="w-full h-px my-2 bg-white/30" />
                <a
                  href={`mailto:${contact.email}`}
                  className="text-xl tracking-wider lowercase md:text-2xl lg:text-3xl hover:text-gold transition-colors duration-200"
                >
                  {contact.email}
                </a>
              </div>
            )}
            {contact.phone && (
              <div className="social-link">
                <h3>Phone</h3>
                <div className="w-full h-px my-2 bg-white/30" />
                <a
                  href={`tel:${contact.phone.replace(/[^\d+]/g, "")}`}
                  className="text-xl lowercase md:text-2xl lg:text-3xl hover:text-gold transition-colors duration-200"
                >
                  {contact.phone}
                </a>
              </div>
            )}
            {socials.length > 0 && (
              <div className="social-link">
                <h3>Social Media</h3>
                <div className="w-full h-px my-2 bg-white/30" />
                <div className="flex flex-wrap gap-2">
                  {socials.map((social, index) => (
                    <a
                      key={index}
                      href={social.href}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-xs leading-loose tracking-widest uppercase md:text-sm hover:text-white/80 transition-colors duration-200"
                    >
                      {"{ "}
                      {social.name}
                      {" }"}
                    </a>
                  ))}
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
      <Marquee items={items} className="text-white bg-transparent" />
    </section>
  );
};

export default Contact;
