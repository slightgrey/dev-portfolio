import Icon from "./Icon.jsx";
import { stagger } from "../lib/useReveal.js";

const icons = ["globe", "monitor", "shield", "pulse"];

// Seeded item descriptions are wrapped in parentheses for the current design.
const unwrap = (text = "") => text.replace(/^\((.*)\)$/s, "$1");

// One service: icon well, title and text, next to a raised list of numbered items.
// Odd rows swap sides on desktop (zig-zag); mobile is a single column.
const ServiceRow = ({ service, index }) => (
  <div className="grid grid-cols-1 items-center gap-10 md:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] md:gap-16">
    <div className={`nm-reveal ${index % 2 ? "md:order-2" : ""}`}>
      <span className="nm-pressed inline-flex size-16 items-center justify-center rounded-sm text-ink">
        <Icon name={icons[index % icons.length]} size={26} strokeWidth={1.75} />
      </span>
      <h3 className="mt-6 mb-3 text-2xl leading-[1.3] font-bold">{service.title}</h3>
      {service.description && <p className="max-w-[48ch] leading-[1.6] text-ink-soft">{service.description}</p>}
    </div>

    {service.items.length > 0 && (
      <ol className="nm-raised nm-reveal flex flex-col gap-2 rounded-md p-4" style={stagger(1)}>
        {service.items.map((item, i) => (
          <li key={`${item.title}-${i}`} className="flex items-center gap-4 rounded-sm px-[18px] py-4">
            <span className="nm-pressed inline-flex size-10 flex-none items-center justify-center rounded-full text-[13px] font-medium" aria-hidden="true">
              {String(i + 1).padStart(2, "0")}
            </span>
            <div>
              <p className="font-semibold">{item.title}</p>
              {item.description && <p className="text-sm text-ink-soft">{unwrap(item.description)}</p>}
            </div>
          </li>
        ))}
      </ol>
    )}
  </div>
);

export default ServiceRow;
