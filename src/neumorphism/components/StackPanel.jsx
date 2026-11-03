import Icon from "./Icon.jsx";
import { stagger } from "../lib/useReveal.js";

// Every third chip (2nd, 5th, 8th…) is pressed, as in the design.
const StackPanel = ({ stack, terminal }) => (
  <div className="nm-raised nm-reveal rounded-md p-6 md:rounded-lg md:p-10" style={stagger(2)}>
    <div className="flex items-center justify-between">
      <h2 className="nm-label">Stack</h2>
      <span className="nm-pressed inline-flex size-14 items-center justify-center rounded-full text-ink">
        <Icon name="code" size={22} />
      </span>
    </div>

    {stack.length > 0 && (
      <ul className="mt-7 flex flex-wrap gap-3.5">
        {stack.map((item, i) => (
          <li
            key={item}
            className={`${i % 3 === 1 ? "nm-pressed" : "nm-raised"} inline-flex min-h-9 items-center rounded-full px-3.5 text-[13px] font-medium`}
          >
            {item}
          </li>
        ))}
      </ul>
    )}

    {terminal && (
      <div className="nm-pressed mt-8 rounded-sm px-5 py-[18px]">
        <p className="font-mono text-[13px] leading-[1.6]">
          $ {terminal}
          <span className="text-ink-soft" aria-hidden="true">
            _
          </span>
        </p>
      </div>
    )}
  </div>
);

export default StackPanel;
