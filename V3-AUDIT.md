# Somali Focus v3.0.0 — Pre-Implementation Audit

Audited: v2.1.0 codebase, full read of both packages plus targeted verification (grep/lint) against the claims below. This is a real audit, not a restatement of the v2.1 changelog — several items below were only confirmed by re-reading the actual current files.

## 1. Current architecture

Unchanged from v2.0/2.1, and still holds up on re-inspection:

- `somali-focus-core` owns CPTs (`sf_course`, `sf_advisory`, `sf_research`, `sf_expert`, `sf_partner`, `sf_testimonial`, `sf_registration`, `sf_service_request`), their taxonomies, meta fields, the consolidated `somali_focus_settings` option, the two front-end forms, admin UI, and demo content.
- `somali-focus` owns every template, the design system, and reads plugin data through exactly one file, `inc/plugin-integration.php` — reconfirmed zero direct `somali_focus_get_*()` calls anywhere else in the theme.
- Theme-level `archive-sf_*.php`/`single-sf_*.php`/`taxonomy.php` correctly take precedence over the plugin's fallback templates via WordPress's own hierarchy resolution.
- Course/Advisory/Research archive slugs (`courses`/`advisory-projects`/`publications`) no longer collide with the Training/Advisory/Research landing pages — the v2.0 fix holds.
- Site structure (7 pages + Primary menu) is created automatically and idempotently on activation/upgrade.

**Verdict: no architectural change needed.** v3 builds on this, doesn't replace it.

## 2. Current strengths

- Consistent escaping (`esc_html`/`esc_attr`/`esc_url`/`wp_kses_post`) across every template traced.
- Forms: matching nonce actions between markup and handlers, honeypot, private-CPT storage excluded from REST/public queries.
- SEO backs off correctly behind `sf_seo_plugin_active()` for Yoast/Rank Math/AIOSEO, shared by both meta tags and JSON-LD.
- Self-hosted fonts (no external requests), lazy-loaded images, deferred JS, single hand-written CSS file.
- `prefers-reduced-motion` already respected for scroll-reveal animations.
- Demo content is clearly labeled in its own copy ("(placeholder)", "Fictional…") — good intent, but (see §6/§11) not load-bearing enough on its own.

## 3. Critical bugs

**None found that break functionality.** The v2.0 audit's P0 (URL collision) and P1s are already fixed and re-verified. What follows are readiness gaps, not breakage.

## 4. UX problems

- Homepage section order doesn't match a "Who We Are before Core Services" narrative — currently Core Services fires before the About/Who-We-Are section, which reads as diving into services before establishing who's providing them.
- No "Why Somali Focus" differentiation section exists anywhere on the site.
- About page has Mission/Vision/Values but no "What We Do," "Sectors We Serve," or "Team" sections — a visitor reading About in isolation has no path to the three core services or the people delivering them.
- 404 page has a search box and a home button but no "popular pages" shortcuts (Training/Advisory/Research/Contact).
- Search results show no result count and no "no results, but here's what to try" guidance beyond the search form.
- Contact form has no visible privacy/consent line near the submit button.

## 5. Design problems

Confirmed via re-read: no stray one-off inline styles introduced beyond a handful of layout-only `style=""` attributes in `settings-page.php` (admin-side, not front-end) and `admin-menu.php`'s dashboard grid — acceptable, low-risk, not user-facing design debt. No systemic design-system inconsistency found; the token system (colors/type/spacing) from v2.1 is applied consistently across all templates re-checked. **No global redesign required for v3** — targeted additions only (see Phase 2/3 implementation).

## 6. Mobile problems

Re-verified against the v2.0 audit's findings: the stats-grid fix and no-fixed-width audit still hold. No new mobile regressions introduced since. Static review only (still no live browser in this environment) — recommend a real-device pass once hosted.

## 7. SEO problems

- **No `noindex` handling for empty archives.** If `sf_course`/`sf_advisory`/`sf_research`/`sf_expert` archives, or a taxonomy term archive, have zero published items, they're currently fully indexable — a real content-quality signal risk for both plain SEO and AdSense review (thin/empty pages).
- No XML sitemap customization needed — WordPress core's default sitemap (`wp-sitemap.xml`) is untouched and functions automatically; confirmed nothing disables it.
- Structured data coverage (Organization, BreadcrumbList, Course, Article) is real and non-duplicated — no gap here, but Phase 7 also asks for **Person** schema on Expert profiles and **WebSite** schema sitewide, both currently missing.

## 8. Accessibility problems

- Re-confirmed skip-link, focus-visible outline, and landmark structure are present and correct.
- Video facade (`template-parts/welcome-video.php`) has `role="button"`/`tabindex="0"`/keyboard handling — correct — but no visible focus ring was defined specifically for `.video-facade`; it inherits the global `:focus-visible` outline, which is sufficient but worth explicitly confirming doesn't get clipped by `overflow:hidden` on the facade (it doesn't — outline renders outside the clipped box in all evergreen browsers). No action needed, noted for the record.
- No systemic contrast issues found in the token palette.

## 9. Performance problems

- Bundled PNGs (logo variants, favicon, screenshot) went through one lossless recompress pass in v2.0; no lossy palette conversion was applied (deliberately, to protect the anti-aliased logo edges). Real further gains would need `pngquant`/`oxipng`, unavailable in this environment — documented as a manual follow-up, not blocking.
- No `decoding="async"` on `the_post_thumbnail()`/`get_the_post_thumbnail()` calls — cheap, safe addition alongside the existing `loading="lazy"`.
- No third-party scripts load by default (fonts self-hosted, video is click-to-play) — this is already strong and must not regress when ad slots are added in Phase 9 (they will default to disabled, verified in implementation).

## 10. Security problems

**None found.** Re-confirmed: capability checks (`somali_focus_manage_cap()`) gate every admin/settings/export action, nonces match on every form, private CPTs stay excluded from REST/public queries, no hand-built SQL anywhere, PDF uploads MIME-validated. The only new surface introduced by v3 (ad-slot embed codes, Phase 9) needs a deliberate, documented exception to normal output-escaping rules since ad network snippets are literal `<script>`/`<ins>` markup — this is scoped to a `manage_options`-gated settings field only (never public input), matching how mainstream "header/footer scripts" WordPress plugins handle the same requirement; documented explicitly in the implementation rather than silently bypassing escaping.

## 11. Content problems

This is the section that matters most for AdSense readiness, and where I need to be direct: **all Course, Advisory, Research, Expert, Partner, and Testimonial content shipped with this project is fictional demo data**, seeded automatically by the plugin so the site "looks complete" out of the box. That's a reasonable development convenience, but:

- It is **not** currently possible to tell, from the database alone, which posts are demo content versus real content an admin has since added — there's no tagging.
- There is no "remove demo content" mechanism — only "install/refill," which is one-directional.
- **I cannot generate real Course curricula, real Research findings, real Advisory case studies, or real testimonials on your behalf** — doing so would mean fabricating claims about Somali Focus's actual work, which the brief explicitly (and correctly) prohibits. This has to come from the organization.

## 12. AdSense readiness problems

Per Google's publisher policies, the concrete gaps are:

- **No Privacy Policy page.** Required. Currently only a settings hook (`get_privacy_policy_url()`) that returns empty until an admin manually designates one — nothing auto-creates it.
- **No Terms of Use page.** The footer already conditionally hides this link when absent (v2.0 fix), which prevents a broken link but doesn't solve "the page should exist."
- **No cookie/privacy notice mechanism** if the site ever sets non-essential cookies (currently it doesn't — no analytics, no ad scripts by default — so this is forward-looking, not urgent, until ads or analytics are added).
- **Demo/placeholder content is indistinguishable from real content** to an outside reviewer (see §11) — this is the single biggest AdSense-readiness blocker, and it is a *content* problem, not a code problem. Code can make it safe and easy to fix; only the organization can actually fix it.
- **No ad-slot architecture exists at all** — needed before ads can ever be safely enabled (Phase 9), even though ads should stay off by default.

## 13. Recommended fixes (this implementation pass)

In priority order, all additive (nothing removed):

1. Privacy Policy + Terms pages, auto-created with clearly-marked placeholder legal text and wired into WordPress's native Privacy Policy mechanism (not fabricated legal claims — a template the owner must complete).
2. Ad-slot architecture: 7 named, settings-controlled, default-disabled, clearly-labeled slots.
3. Demo-content tagging (`_sf_demo_content` meta) + a safe "Remove All Demo Content" admin action + a persistent, honest dashboard notice while demo content is present.
4. `noindex` on empty archives/term archives.
5. Person schema on Experts, WebSite schema sitewide.
6. Homepage section reorder (Who We Are before Core Services) + new "Why Somali Focus" section.
7. About page: add What We Do / Sectors / Team sections (reusing existing card components).
8. 404 popular-links, search result count, contact-form privacy line.
9. `decoding="async"` pass on thumbnails.
10. Version bump to 3.0.0 across both packages, full changelog, `V3-PRODUCTION-READINESS.md` with an honest PASS/NEEDS WORK table.

Not attempted: fabricating real content, guaranteeing AdSense approval, adding a JS framework, or any change to the Core/Theme architecture boundary — all explicitly out of scope per your rules.
