# Somali Focus v3.0.0 — Production Readiness Report

Scope: `somali-focus-core` v3.0.0 + `somali-focus` theme v3.0.0. This report
follows directly from `V3-AUDIT.md` (the pre-implementation audit) — every
item in that audit's §13 recommended-fixes list has been implemented and is
verified below. Testing method: static code analysis only (`php -l` on every
PHP file in both packages, `node --check` on every JS file, and manual
tracing of template/hook/data flow). **There is no live WordPress + MySQL +
browser environment available in this sandbox**, so nothing here was verified
by rendering pages in an actual browser. Treat the PASS marks below as "code
is correct and internally consistent," not "visually confirmed in a
browser" — a real-host smoke test before launch is still required (see §8).

## 1. What changed in v3.0.0

1. Privacy Policy + Terms of Use pages, auto-created with placeholder legal
   text and wired into WordPress's native Privacy Policy setting.
2. Ad-slot architecture: 7 named, off-by-default, clearly-labeled placements.
3. Demo-content tagging + safe one-click removal + dashboard notice.
4. `noindex,follow` on empty archive/search pages.
5. Person schema (Experts) + WebSite schema (sitewide), SEO-plugin-aware.
6. Homepage reorder (Who We Are before Core Services) + new "Why Somali
   Focus" section.
7. About page: added What We Do, Sectors We Serve, and Team sections.
8. 404 popular-page links, search result count, contact-form consent line.
9. `decoding="async"` on every card and single-page thumbnail.
10. Version bump to 3.0.0 across both packages, changelogs, this report.

Nothing was removed. No CPT, taxonomy, setting, or template was deleted or
renamed. The Core/Theme separation (`inc/plugin-integration.php` as the only
bridge) is unchanged and re-verified — see §2.

## 2. Architecture check

| Check | Result |
|---|---|
| Theme reads plugin data only through `inc/plugin-integration.php` | PASS — no direct `somali_focus_get_*()`/`somali_focus_settings` calls elsewhere in the theme |
| Plugin has zero dependency on the theme being active | PASS — plugin fallback templates (`somali-focus-core/templates/`) unchanged |
| Deactivating the plugin shows an admin notice, not a fatal error | PASS — unchanged from v2.x, re-traced |
| CPT archive slugs vs. landing Page slugs | PASS — no collision (fixed in v2.0, still holds) |
| New v3 files follow the same ownership split | PASS — `ad-slots.php` (data/settings) lives in the plugin; ad-slot *rendering* (`sf_ad_slot()` wrapper, CSS) lives in the theme |

## 3. Page-by-page status

| Page / template | Status | Notes |
|---|---|---|
| Homepage (`front-page.php`) | PASS | Reordered per audit; "Why Somali Focus" added; experts/partners sections extracted to reusable template-parts |
| About (`templates/page-about.php`) | PASS | What We Do, Sectors We Serve, Team sections added |
| Training (`templates/page-training.php`) | PASS | Unchanged this version, previously verified |
| Course archive/single (`archive-sf_course.php`, `single-sf_course.php`) | PASS | `decoding="async"` added to hero thumbnail |
| Advisory (`templates/page-advisory.php`) | PASS | Unchanged this version, previously verified |
| Advisory Projects archive/single | PASS | `decoding="async"` added |
| Research (`templates/page-research.php`) | PASS | Unchanged this version, previously verified |
| Research archive/single | PASS | `decoding="async"` added; in-content ad slot present |
| Services (`templates/page-services.php`) | PASS | Unchanged this version, previously verified |
| Insights (blog: `index.php`, `single.php`) | PASS | `decoding="async"` added; sidebar ad slot present |
| Experts archive/single | PASS | Person schema added; `decoding="async"` on portraits |
| Partners archive | PASS | `decoding="async"` on logos |
| Testimonials | PASS | `decoding="async"` on photos |
| Contact (`templates/page-contact.php`) | PASS | Consent/privacy line added to the form |
| Search (`search.php`) | PASS | Result count + popular-page fallback added |
| 404 (`404.php`) | PASS | Popular-page shortcuts added |
| Taxonomy archives (`taxonomy.php`) | PASS | `noindex,follow` now applied when a term archive is empty |
| Privacy Policy / Terms (new) | **NEEDS WORK (by site owner)** | Pages exist and are wired up correctly; the legal text inside is a structural placeholder and must be completed with real organizational/legal details before launch — this cannot be done on the organization's behalf |

## 4. SEO status: PASS

Unique per-page titles/meta/canonical/OG/Twitter tags, JSON-LD (Organization,
WebSite, BreadcrumbList, Course, Article, Person), all gated behind
`sf_seo_plugin_active()` so Yoast/Rank Math/AIOSEO are never duplicated.
Empty archives and empty search results now emit `noindex,follow`. No fake or
invented structured data was added — Person/WebSite schema only pull fields
that already exist in the database (Expert meta fields, Settings org info).

## 5. Performance status: PASS

Self-hosted variable fonts (no external font requests), lazy-loaded +
async-decoded images everywhere, click-to-play video facade (no third-party
script loads until the visitor presses play), no added JS/CSS frameworks,
ad slots render nothing when disabled (no dead markup, no reserved layout
shift). Bundled PNG compression is unchanged from v2.0 (documented then as a
manual `pngquant`/`oxipng` follow-up — still true, still non-blocking, this
tool isn't available in this environment).

## 6. Accessibility status: PASS

Skip link, landmark structure, `:focus-visible` outlines, keyboard-operable
video facade, and dropdown nav all unchanged and previously verified (v1.5,
re-confirmed in v2.0 and v3.0 audits). New markup in this version (Why
Somali Focus cards, ad-slot wrappers, 404/search link lists) uses plain
semantic HTML with no new interactive widgets, so no new accessibility
surface was introduced.

## 7. Security status: PASS

Capability checks and nonces unchanged and re-verified on every admin/form
action. The one deliberate, documented exception: ad-slot embed codes are
stored unsanitized (`raw_html` setting type) because they are literal
`<script>`/`<ins>` network snippets — this field is gated behind
`manage_options` only, is never public input, and matches how mainstream
"header/footer code" WordPress plugins handle the same requirement. No SQL
is hand-built anywhere. Private CPTs (`sf_registration`, `sf_service_request`)
remain excluded from REST and public queries.

## 8. Content & demo-content status

- Every demo post (Courses, Advisory Projects, Research, Experts, Partners,
  Testimonials) is now tagged with `_sf_demo_content` at creation.
- Somali Focus → Dashboard shows a confirm-gated "Remove All Demo Content"
  button whenever tagged content exists, and a success notice after removal.
  Real, non-tagged content is never touched by this action.
- **This does not — and cannot — solve the underlying content problem.**
  All Course curricula, Research findings, Advisory case studies,
  testimonials, and partner names shipped with this project remain
  fictional placeholder data. No real content was fabricated on the
  organization's behalf at any point in this project, per the explicit
  instruction governing this work. Before public launch, a site
  administrator must either replace or remove every demo post using the
  mechanism above.

## 9. AdSense readiness: NEEDS WORK (organization action required)

"AdSense-ready" here means *technically and structurally ready for review*,
not *guaranteed to be approved* — approval is Google's decision, not this
project's to promise.

**Code/structure — READY:**
- Ad-placement architecture exists, is non-intrusive, clearly labeled
  "Advertisement," responsive, and off by default (nothing renders, no
  layout reserved, until an admin explicitly enables and fills a slot).
- No deceptive elements, fake buttons, hidden links, auto-playing media, or
  intrusive interstitials anywhere in the codebase.
- Privacy Policy and Terms of Use pages exist, are linked from the footer,
  and are registered with WordPress's native Privacy Policy setting.
- Clear organizational identity: About and Contact pages, real navigation,
  no auto-generated or spun content.

**Organization action — NEEDS WORK, blocking:**
- Complete the Privacy Policy and Terms of Use placeholder text with real
  legal details (organization's registered name/address, actual data
  practices, applicable law).
- Replace or remove all demo Course/Advisory/Research/Expert/
  Partner/Testimonial content with real information before submitting the
  site for AdSense review — a reviewer (human or automated) seeing
  fictional case studies and testimonials is a content-quality rejection
  risk, not a code risk.
- Publish enough real Insights/Research content with clear authorship and
  dates to demonstrate the site is an active, substantive publication, not
  a thin placeholder site — this is a content decision only the
  organization can make.

## 10. Known limitations of this verification pass

- No live WordPress install, database, or browser was available in this
  environment. All checks are static: PHP/JS syntax linting, template
  hierarchy tracing, hook-order reasoning, and manual code review — not
  rendered-page or visual QA.
- Mobile breakpoint behavior (320–1440px) was verified by reading the CSS,
  not by resizing a real viewport.
- A real-device/real-browser smoke test, and an actual WordPress
  install-and-click-through, are recommended before declaring the site
  publicly launched.

## 11. Bottom line

Every code-level item from the v3.0.0 audit is implemented, linted clean
(zero PHP/JS syntax errors across both packages), and does not remove or
break any existing feature. The system is **structurally production-ready**.
The site is **not yet content-ready**: real Course/Advisory/Research content,
real testimonials/partners, and completed legal pages are required from the
organization before public launch or an AdSense application, and no part of
this project fabricated that content on the organization's behalf.
