import { useEffect, useRef, useState } from "react";
import Icon from "./Icon.jsx";
import { useData } from "../lib/data.js";
import { useRecaptcha } from "../lib/recaptcha.js";

// Posts to the vincent/v1/contact REST route (inc/contact.php), which sends the mail on the
// server, so the recipient address is never in the page or this bundle.
const fieldIds = { name: "nm-name", email: "nm-email", topic: "nm-topic", message: "nm-message" };

const validate = ({ name, email, message }) => {
  const errors = {};
  if (!name.trim()) errors.name = "Please enter your name.";
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim())) errors.email = "Please enter a valid e-mail address.";
  if (!message.trim()) errors.message = "Please write a message.";
  else if (message.trim().length > 5000) errors.message = "Please keep your message under 5000 characters.";
  return errors;
};

const Field = ({ id, label, error, children }) => (
  <div className="flex flex-col gap-2">
    <label htmlFor={id} className="text-sm font-medium">
      {label}
    </label>
    {children}
    {error && (
      <p id={`${id}-error`} className="text-sm text-error">
        {error}
      </p>
    )}
  </div>
);

const ContactForm = () => {
  const { form, contact } = useData().nm;
  const topics = contact.topics.length ? contact.topics : ["Something else"];
  const [values, setValues] = useState({ name: "", email: "", topic: topics[0], message: "", website: "" });
  const [errors, setErrors] = useState({});
  const [formError, setFormError] = useState("");
  const [status, setStatus] = useState("idle");
  const captcha = useRecaptcha("contact");
  const successRef = useRef(null);

  useEffect(() => {
    if (status === "sent") successRef.current?.focus();
  }, [status]);

  const update = (event) => {
    const { name, value } = event.target;
    setValues((current) => ({ ...current, [name]: value }));
    if (errors[name]) setErrors((current) => ({ ...current, [name]: undefined }));
  };

  // Props shared by every visible field: id, value, error wiring.
  const fieldProps = (name) => ({
    id: fieldIds[name],
    name,
    value: values[name],
    onChange: update,
    "aria-invalid": errors[name] ? true : undefined,
    "aria-describedby": errors[name] ? `${fieldIds[name]}-error` : undefined,
  });

  const focusFirstError = (fieldErrors) => {
    const first = Object.keys(fieldIds).find((key) => fieldErrors[key]);
    if (first) document.getElementById(fieldIds[first])?.focus();
  };

  const onSubmit = async (event) => {
    event.preventDefault();
    const fieldErrors = validate(values);
    setErrors(fieldErrors);
    setFormError("");
    if (Object.keys(fieldErrors).length) {
      focusFirstError(fieldErrors);
      return;
    }

    setStatus("sending");
    try {
      const token = await captcha.getToken();
      if (captcha.showWidget && !token) throw new Error("Please tick the reCAPTCHA box.");

      const res = await fetch(form.endpoint, {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/json", "X-WP-Nonce": form.nonce },
        body: JSON.stringify({ ...values, recaptcha_token: token || undefined }),
      });
      const json = await res.json().catch(() => ({}));

      if (!res.ok) {
        if (res.status === 403) throw new Error("Your session expired. Refresh the page and try again.");
        // Field errors from the REST args validation.
        const params = json.data?.params;
        if (res.status === 400 && params) {
          const serverErrors = Object.fromEntries(Object.keys(fieldIds).filter((key) => params[key]).map((key) => [key, params[key]]));
          if (Object.keys(serverErrors).length) {
            setErrors(serverErrors);
            focusFirstError(serverErrors);
            throw new Error("Please check the highlighted fields.");
          }
        }
        throw new Error(json.message || "Your message could not be sent. Please try again.");
      }

      setStatus("sent");
    } catch (err) {
      setFormError(err.message || "Your message could not be sent. Please try again.");
      setStatus("idle");
    } finally {
      captcha.reset();
    }
  };

  if (status === "sent") {
    return (
      <div ref={successRef} tabIndex={-1} role="status" className="nm-raised mt-8 rounded-md p-5 md:p-8">
        <p className="text-xl font-bold">Thanks, your message is on its way.</p>
        <p className="mt-2 leading-[1.6] text-ink-soft">
          I’ll get back to you{contact.replyTime ? ` within ${contact.replyTime}` : " soon"}.
        </p>
      </div>
    );
  }

  const sending = status === "sending";

  return (
    <form noValidate onSubmit={onSubmit} className="nm-raised nm-reveal relative mt-8 flex flex-col gap-5 rounded-md p-5 md:p-8">
      <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
        <Field id={fieldIds.name} label="Name" error={errors.name}>
          <input type="text" autoComplete="name" placeholder="Your name" required maxLength={100} className="nm-field" {...fieldProps("name")} />
        </Field>
        <Field id={fieldIds.email} label="E-mail" error={errors.email}>
          <input type="email" autoComplete="email" placeholder="you@company.com" required className="nm-field" {...fieldProps("email")} />
        </Field>
      </div>

      <Field id={fieldIds.topic} label="What do you need?" error={errors.topic}>
        <select className="nm-field" {...fieldProps("topic")}>
          {topics.map((topic) => (
            <option key={topic} value={topic}>
              {topic}
            </option>
          ))}
        </select>
      </Field>

      <Field id={fieldIds.message} label="Message" error={errors.message}>
        <textarea rows={5} placeholder="Tell me about your project or question" required maxLength={5000} className="nm-field min-h-32 resize-y pt-3.5" {...fieldProps("message")} />
      </Field>

      {/* Honeypot: hidden from people and assistive tech; bots that fill it are ignored. */}
      <div className="absolute -left-[9999px] size-px overflow-hidden" aria-hidden="true">
        <label htmlFor="nm-website">Website</label>
        <input id="nm-website" type="text" name="website" tabIndex={-1} autoComplete="off" value={values.website} onChange={update} />
      </div>

      {captcha.showWidget && <div ref={captcha.containerRef} />}

      {formError && (
        <p role="alert" className="rounded-sm border border-error px-4 py-3 text-sm text-error">
          {formError}
        </p>
      )}

      <div className="flex flex-wrap items-center justify-between gap-4">
        {contact.replyTime && <p className="text-[13px] text-ink-soft">I usually reply within {contact.replyTime}.</p>}
        <button type="submit" className="nm-btn nm-btn-primary w-full border-0 font-[inherit] md:w-auto" disabled={sending || !captcha.ready}>
          {sending ? "Sending…" : "Send message"}
          <Icon name="arrow" size={18} />
        </button>
      </div>

      {captcha.isV3 && (
        <p className="text-[11px] text-ink-soft">
          This site is protected by reCAPTCHA and the Google{" "}
          <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" className="underline underline-offset-2">
            Privacy Policy
          </a>{" "}
          and{" "}
          <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer" className="underline underline-offset-2">
            Terms of Service
          </a>{" "}
          apply.
        </p>
      )}
    </form>
  );
};

export default ContactForm;
