import Nav from "./components/Nav.jsx";
import Hero from "./components/Hero.jsx";
import Services from "./components/Services.jsx";
import About from "./components/About.jsx";
import Cta from "./components/Cta.jsx";
import Contact from "./components/Contact.jsx";
import Footer from "./components/Footer.jsx";
import { useData } from "./lib/data.js";

// Neumorphism homepage (design/neumorphism-*.html). The data-design attribute scopes its base
// styles; the section switches at Appearance → Developer Portfolio still apply.
const App = () => {
  const { sections, services } = useData();

  return (
    <div id="top" data-design="neumorphism" className="min-h-dvh overflow-x-clip bg-ground font-sans text-ink">
      <Nav />
      {sections.hero && <Hero />}
      {sections.services && services.length > 0 && <Services />}
      {sections.about && <About />}
      {sections.cta && <Cta />}
      {sections.contact && <Contact />}
      <Footer />
    </div>
  );
};

export default App;
