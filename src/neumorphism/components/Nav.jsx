import { useEffect, useRef, useState } from "react";
import Icon from "./Icon.jsx";
import { useData } from "../lib/data.js";

const linkClass =
  "inline-flex min-h-11 items-center rounded-sm px-3.5 text-sm font-medium text-ink-soft no-underline transition-shadow duration-200 ease-out hover:text-ink hover:shadow-pressed-sm";

const Nav = () => {
  const { nm, sections, services } = useData();
  const [open, setOpen] = useState(false);
  const buttonRef = useRef(null);

  const links = [
    sections.services && services.length > 0 && { href: "#services", label: "Services" },
    sections.about && { href: "#about", label: "About" },
    sections.contact && { href: "#contact", label: "Contact" },
    nm.blogUrl && { href: nm.blogUrl, label: "Blog" },
  ].filter(Boolean);

  // Escape closes the menu and puts focus back on its button; so does growing past mobile width.
  useEffect(() => {
    if (!open) return undefined;
    const onKey = (event) => {
      if (event.key === "Escape") {
        setOpen(false);
        buttonRef.current?.focus();
      }
    };
    const desktop = window.matchMedia("(min-width: 768px)");
    const onResize = () => desktop.matches && setOpen(false);
    document.addEventListener("keydown", onKey);
    desktop.addEventListener("change", onResize);
    return () => {
      document.removeEventListener("keydown", onKey);
      desktop.removeEventListener("change", onResize);
    };
  }, [open]);

  const close = () => setOpen(false);

  return (
    <header className="sticky top-[var(--wp-admin--admin-bar--height,0px)] z-[100] bg-ground py-4">
      <div className="mx-auto max-w-site px-4 md:px-6">
        <div className="nm-raised relative flex min-h-16 items-center justify-between gap-4 rounded-md py-2 pr-2 pl-4">
          <a href="#top" className="flex items-center gap-3 text-base font-bold text-ink no-underline">
            {nm.logo && (
              <span className="nm-pressed inline-flex size-10 items-center justify-center rounded-sm text-[13px] font-bold" aria-hidden="true">
                {nm.logo}
              </span>
            )}
            {nm.brand}
          </a>

          <nav aria-label="Primary" className="hidden items-center gap-1 md:flex">
            {links.map((link) => (
              <a key={link.href} href={link.href} className={linkClass}>
                {link.label}
              </a>
            ))}
            {sections.contact && (
              <a href="#contact" className="nm-btn nm-btn-primary ml-2 min-h-11">
                Hire me
              </a>
            )}
          </nav>

          <button
            ref={buttonRef}
            type="button"
            className="nm-raised inline-flex size-12 cursor-pointer items-center justify-center rounded-sm border-0 text-ink transition-shadow duration-150 ease-out active:shadow-pressed md:hidden"
            aria-expanded={open}
            aria-controls="nm-mobile-menu"
            aria-label={open ? "Close menu" : "Open menu"}
            onClick={() => setOpen((value) => !value)}
          >
            <Icon name={open ? "close" : "menu"} />
          </button>

          <nav
            id="nm-mobile-menu"
            aria-label="Mobile"
            hidden={!open}
            className="nm-raised absolute inset-x-0 top-[calc(100%+12px)] rounded-md p-3 md:hidden"
          >
            <ul className="flex flex-col gap-1">
              {links.map((link) => (
                <li key={link.href}>
                  <a href={link.href} className={`${linkClass} flex w-full text-base`} onClick={close}>
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
            {sections.contact && (
              <a href="#contact" className="nm-btn nm-btn-primary mt-2 w-full" onClick={close}>
                Hire me
              </a>
            )}
          </nav>
        </div>
      </div>
    </header>
  );
};

export default Nav;
