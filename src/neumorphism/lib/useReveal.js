import { useEffect, useRef } from "react";

/**
 * Fade-and-rise entries: attach the returned ref to a section, and give the blocks inside it the
 * `nm-reveal` class (optionally with a `--nm-delay` style for an 80ms stagger). Each block gets
 * `is-visible` when it scrolls into view. Reduced motion is handled in CSS.
 */
export function useReveal() {
  const ref = useRef(null);

  useEffect(() => {
    const root = ref.current;
    if (!root) return undefined;

    const items = [...root.querySelectorAll(".nm-reveal")];
    if (root.classList.contains("nm-reveal")) items.unshift(root);

    if (!("IntersectionObserver" in window)) {
      items.forEach((item) => item.classList.add("is-visible"));
      return undefined;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { rootMargin: "0px 0px -8% 0px" }
    );
    items.forEach((item) => observer.observe(item));

    return () => observer.disconnect();
  }, []);

  return ref;
}

// Stagger delay for the nth item of a list.
export const stagger = (index) => ({ "--nm-delay": `${index * 80}ms` });
