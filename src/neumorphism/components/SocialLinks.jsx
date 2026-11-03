import { displayUrl, useData } from "../lib/data.js";
import { stagger } from "../lib/useReveal.js";

const SocialLinks = () => {
  const { socials } = useData();

  return (
    <div className="flex flex-col gap-4">
      <h3 className="nm-label nm-reveal">Social</h3>
      <ul className="flex flex-col gap-4">
        {socials.map((social, i) => (
          <li key={`${social.name}-${i}`} className="nm-reveal" style={stagger(i + 1)}>
            <a
              href={social.href}
              target="_blank"
              rel="noopener noreferrer"
              className="nm-raised flex min-h-16 items-center justify-between gap-4 rounded-sm px-[18px] py-4 text-ink no-underline transition-shadow duration-200 ease-out hover:shadow-pressed"
            >
              <span className="font-semibold">{social.name}</span>
              <span className="min-w-0 truncate font-mono text-[13px] text-ink-soft">{displayUrl(social.href)}</span>
            </a>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default SocialLinks;
