// Content is printed by inc/vite.php as window.vincentData (see vincent_get_portfolio_data()).
const emptyHeader = { subTitle: "", title: "", text: "" };

// Section keys match vincent_option_fields(); toggled at Appearance → Developer Portfolio.
const allSections = {
  hero: true,
  service_summary: true,
  services: true,
  about: true,
  works: true,
  cta: true,
  contact: true,
};

const defaults = {
  sections: allSections,
  hero: { ...emptyHeader, scroll: "" },
  serviceSummary: [],
  servicesHeader: emptyHeader,
  services: [],
  about: { ...emptyHeader, body: "", image: "", imageAlt: "" },
  worksHeader: emptyHeader,
  projects: [],
  cta: { values: [], quote: "", marquee: "" },
  contact: { ...emptyHeader, email: "", phone: "", marquee: "" },
  socials: [],
};

const data = { ...defaults, ...(window.vincentData ?? {}) };
data.sections = { ...allSections, ...data.sections };

export const useData = () => data;

// Deterministic gradient used when a project or portrait has no image yet.
export const placeholderGradient = (seed = 0) => {
  const hue = (seed * 47 + 30) % 360;
  return `linear-gradient(135deg, hsl(${hue} 25% 30%), hsl(${(hue + 40) % 360} 35% 12%))`;
};
