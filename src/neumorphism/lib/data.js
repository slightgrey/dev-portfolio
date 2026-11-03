// Printed by inc/vite.php as window.vincentData; the `neumorphism` block comes from
// vincent_get_neumorphism_data() and is only present while this design is active.
const raw = window.vincentData ?? {};

const emptyNm = {
  logo: "",
  brand: "",
  blogUrl: "",
  hero: { eyebrow: "", title: "", lead: "", stack: [], terminal: "" },
  services: { eyebrow: "", title: "" },
  about: { eyebrow: "", title: "", text: "", values: [], hobbies: [] },
  cta: { eyebrow: "", title: "" },
  contact: { eyebrow: "", title: "", text: "", replyTime: "", topics: [] },
  footer: { tagline: "", name: "" },
  form: { endpoint: "", nonce: "" },
  recaptcha: { version: "v3", siteKey: "", contact: false },
};

// Section keys match vincent_option_fields(); a missing key counts as switched on.
const sections = { hero: true, services: true, about: true, cta: true, contact: true, ...raw.sections };

const data = {
  sections,
  services: raw.services ?? [],
  socials: raw.socials ?? [],
  nm: { ...emptyNm, ...raw.neumorphism },
};

export const useData = () => data;
export const nmData = data.nm;

// "https://www.github.com/you/" → "github.com/you"
export const displayUrl = (href = "") =>
  href.replace(/^[a-z]+:\/\/(www\.)?/i, "").replace(/\/$/, "");
