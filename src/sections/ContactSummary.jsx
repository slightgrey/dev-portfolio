import { useMemo, useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import Marquee from "../components/Marquee";
import { useData } from "../lib/data";

const ContactSummary = () => {
  const { cta } = useData();
  const containerRef = useRef(null);
  const bottomItems = useMemo(
    () => (cta.marquee ? Array(5).fill(cta.marquee) : []),
    [cta.marquee]
  );

  useGSAP(() => {
    gsap.to(containerRef.current, {
      scrollTrigger: {
        trigger: containerRef.current,
        start: "center center",
        // Short hold on the quote; longer pins feel like the page is stuck.
        end: "+=250 center",
        scrub: 0.5,
        pin: true,
        pinSpacing: true,
      },
    });
  }, []);

  return (
    <section
      ref={containerRef}
      className="flex flex-col items-center justify-between min-h-screen gap-12 mt-16"
    >
      <Marquee items={cta.values} />
      {/* cta.quote is sanitized server-side with wp_kses (see vincent_allowed_html()). */}
      <div className="overflow-hidden font-light text-center contact-text-responsive">
        <p className="cta-quote" dangerouslySetInnerHTML={{ __html: cta.quote }} />
      </div>
      <Marquee
        items={bottomItems}
        reverse={true}
        className="text-black bg-transparent border-y-2"
        iconClassName="stroke-gold stroke-2 text-primary"
        icon="material-symbols-light:square"
      />
    </section>
  );
};

export default ContactSummary;
