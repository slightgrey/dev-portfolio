import { useData } from "../lib/data.js";

const Footer = () => {
  const { tagline, name } = useData().nm.footer;

  return (
    <footer className="pb-10">
      <div className="mx-auto max-w-site px-4 md:px-6">
        <div className="nm-pressed flex flex-wrap items-center justify-between gap-4 rounded-md px-7 py-6">
          {tagline && <p className="font-semibold">{tagline}</p>}
          <p className="text-sm text-ink-soft">
            © {new Date().getFullYear()} {name}
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
