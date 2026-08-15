# Somali Focus — WordPress Plugin + Theme

This directory tree contains two independently-installable WordPress
packages built for **Somali Focus** (Training, Advisory & Research):

```
somali-focus-core/   ← Plugin. Owns all data: CPTs, taxonomies, settings,
                        forms, registrations, admin UI.
somali-focus/         ← Theme. Owns all design: layout, typography,
                        colors, templates, animations.
```

They are unrelated to the rest of this repository (an Android WebView
library) — they were added here because the session's working branch
was pre-configured against this repo. If you'd rather they live in
their own repository, just copy both folders out; neither depends on
anything else in this codebase.

## What's new in 1.5

* The real Somali Focus logo ships as the default header/footer/favicon
  asset (background removed, cropped into header/footer/mark variants).
* Desktop dropdown submenus now open on tap/click/keyboard, not just
  mouse hover.
* JSON-LD structured data: Organization, BreadcrumbList, Course (on
  training pages) and Article (on Insights posts) schema.
* Related Courses on single course pages; previous/next navigation on
  single Insights posts.
* Real `languages/somali-focus.pot` translation templates in both
  packages (was a placeholder instructions file in 1.0).

See each package's `readme.txt` for the full changelog.

## Why two packages?

If `somali-focus` were ever swapped for a different theme, every
Course, Advisory Project, Research item, Expert, Partner, Testimonial,
Registration and Service Request submitted through the site must
survive untouched. Splitting the project this way guarantees it:

* **`somali-focus-core`** registers the Custom Post Types, taxonomies,
  post meta, the Settings page, the two front-end forms, and ships its
  own plain fallback templates — so the site keeps functioning under
  *any* active theme, not just this one.
* **`somali-focus`** never touches the database directly. Every piece
  of plugin data flows through one file, `inc/plugin-integration.php`,
  whose functions (`sf_theme_get_courses()`, `sf_theme_option()`, etc.)
  degrade to empty/placeholder values if the plugin is inactive,
  instead of fataling.

## Installing

1. Zip each folder (or use the ones under `dist/` if present) and
   upload via **Plugins → Add New → Upload Plugin** and
   **Appearance → Themes → Add New → Upload Theme** respectively — or
   drop both folders into `wp-content/plugins/` and `wp-content/themes/`
   over FTP/SSH.
2. Activate **Somali Focus Core** first, then activate the
   **Somali Focus** theme.
3. Follow `somali-focus/readme.txt` for menu, page-template and
   Customizer setup, and `somali-focus-core/readme.txt` for plugin
   specifics.

Demo content (clearly fictional — courses, advisory projects, research
items, experts, partner placeholders) seeds automatically the first
time the plugin activates, so the site looks complete immediately. Use
**Somali Focus → Dashboard → Install / Refill Demo Content** to top up
any section you've emptied out while testing.

## Plugin architecture (`somali-focus-core`)

```
somali-focus-core.php        Plugin bootstrap, constants
includes/
  class-plugin.php           Singleton loader, activation/deactivation
  helpers.php                Public data API — the ONLY thing a theme should call
  security.php                Capability checks, sanitizers, honeypot check
  post-types.php              sf_course, sf_advisory, sf_research, sf_expert,
                               sf_partner, sf_testimonial, sf_registration,
                               sf_service_request
  taxonomies.php               course_category, advisory_category,
                               research_category, expertise, sf_sector
  meta-fields.php              Config-driven meta boxes for every CPT above
  settings.php                 Consolidated `somali_focus_settings` option
  forms.php                    admin-post.php handlers for both front-end forms
  notifications.php            Admin email notifications
  shortcodes.php               [sf_course_registration_form], [sf_service_request_form], [sf_stats]
  template-loader.php          Fallback archive/single templates when no theme provides one
  demo-content.php             Idempotent demo-content seeding
admin/
  admin-menu.php               "Somali Focus" top-level menu, dashboard, CSV export
  settings-page.php            Renders Somali Focus → Settings
  assets/js/pdf-picker.js      Media-library PDF picker for Research reports
public/
  css/forms.css, js/forms.js   Theme-agnostic baseline styling/behavior for the forms
  css/fallback-templates.css   Styling for the plain fallback templates
templates/                     Fallback archive-*.php / single-*.php files
uninstall.php                  Removes only the plugin's own settings option
```

### Public data API (call these from any theme)

```php
somali_focus_get_option( $key, $default )
somali_focus_get_stat_blocks()
somali_focus_get_courses( $count )
somali_focus_get_advisory_projects( $count )
somali_focus_get_research( $count )
somali_focus_get_experts( $count )
somali_focus_get_partners( $count )
somali_focus_get_testimonials( $count )
somali_focus_meta( $post, $key, $default )
somali_focus_core_is_active()
```

### Adding content (no code required)

* **Course**: Somali Focus → Training → Add New. Fill in the Course
  Details meta box (trainer, dates, fee, outline, etc.) and assign a
  Course Category.
* **Advisory Project**: Somali Focus → Advisory → Add New. Never put a
  real client's name in "Client / Sector" — describe it generically
  (e.g. "International NGO").
* **Research**: Somali Focus → Research → Add New. Upload a PDF via
  the "Select PDF" button in the Research Details box to enable the
  Download button on the front end.
* **Expert**: Somali Focus → Experts → Add New. Set a featured image
  (portrait) and fill in position/education/experience/LinkedIn.
* **Partner**: Somali Focus → Partners → Add New. Set a featured image
  (logo, transparent PNG recommended) and an optional website URL.
* **Registrations / Service Requests**: view, filter by status/course,
  and Export CSV from their respective admin screens. Change the
  Status field on an individual entry and update to track it through
  New → Contacted/In Progress → Confirmed/Replied → Completed/Closed.

## Theme architecture (`somali-focus`)

```
style.css                    Theme header only — see assets/css/main.css for actual styles
functions.php                Include loader
front-page.php                Homepage: hero → services → about → stats →
                               training/advisory/research highlights → approach →
                               sectors → insights → experts → partners → testimonials → CTA
header.php / footer.php       Sticky header, mobile nav, four-column footer
index.php                     Insights (blog) listing — also the ultimate fallback
archive.php / single.php      Blog category/tag archives, single post
page.php / search.php / 404.php / comments.php / searchform.php
archive-sf_*.php / single-sf_*.php   Designed templates for each plugin CPT
                               (theme templates always win over the plugin's
                               plain fallbacks, per WordPress template hierarchy)
inc/
  setup.php                   Theme supports, nav menus, image sizes, widget areas
  enqueue.php                 CSS/JS/fonts enqueueing
  security.php                Header hardening, safe inline-SVG echo helper
  seo.php                     Meta description/OG/Twitter/canonical (backs off if
                               Yoast/Rank Math/AIOSEO is active)
  customizer.php               Presentation-only controls: brand colors, header
                               CTA text/URL, breadcrumb toggle
  navigation.php               Nav walker + shared wp_nav_menu() args
  template-functions.php       Reading time, breadcrumbs, icon renderer, date/word helpers
  plugin-integration.php       THE ONLY FILE THAT TALKS TO THE PLUGIN
template-parts/                Hero, cards (course/advisory/research/expert/
                               testimonial/partner/blog), CTA band, stats,
                               approach steps, sectors grid, about/values, page-hero
templates/                     Template Name: pages — About, Training, Advisory,
                               Research, Services Overview, Contact
assets/
  css/main.css                 The entire design system (custom properties, layout,
                               components, responsive rules)
  css/editor-style.css         Approximates the front end inside Gutenberg
  js/main.js                   Sticky header, mobile nav, scroll-reveal, counters —
                               vanilla JS, no dependencies
  images/logo-horizontal.png   Bundled real logo — header/footer default
  images/logo-full.png         Bundled real logo — stacked mark + wordmark + tagline
  images/logo-mark.png         Bundled real logo — mark only
  images/site-icon.png         Bundled real logo — favicon fallback (512×512)
```

### Design tokens (`assets/css/main.css`)

```css
--sf-blue   #0B4A8F   /* primary / trust */
--sf-red    #C8102E   /* CTAs, accents, hover states — used sparingly */
--sf-navy   #0A1F33   /* headings, dark sections */
--sf-charcoal #22282F /* body text */
--sf-gray-50…600      /* neutral scale for backgrounds/borders/secondary text */
```
Typography: **Plus Jakarta Sans** (UI, body, nav) + **Merriweather**
(hero headline, section titles, testimonials) — loaded via
`inc/enqueue.php`.

### Branding — the real logo is bundled

The actual Somali Focus logo (the red checkmark/swoosh mark with the
"SomaliFocus" wordmark) ships inside the theme at
`assets/images/logo-horizontal.png`, `logo-full.png` and
`logo-mark.png`, each with the background removed so they sit cleanly
on both light and dark sections. It displays automatically in the
header and footer — no setup required.

To override it (e.g. with an updated or higher-resolution version),
upload a new file via **Appearance → Customize → Site Identity →
Logo**; WordPress's native Custom Logo always takes priority over the
bundled default. A matching favicon derived from the same mark is
bundled too (`assets/images/site-icon.png`) and is used automatically
until a **Site Icon** is set in the same Customizer panel.

### Customizer vs. Plugin Settings — what goes where

| Editable from...                              | Lives in |
|---|---|
| Hero title/description/buttons, About/Mission/Vision text, impact statistics, org name/tagline/contact info/social links, homepage CTA text | **Somali Focus → Settings** (plugin) |
| Brand accent colors, header CTA button text/URL, breadcrumb visibility | **Appearance → Customize** (theme) |
| Site title/tagline/logo/icon, menus, widgets | **Appearance → Customize / Menus / Widgets** (WordPress core) |

## Support matrix

* Gutenberg block editor: yes (classic theme, full editor styles)
* Yoast SEO / Rank Math / All in One SEO: yes — the theme's own meta
  tag output backs off automatically when any of those are active
* Elementor / Divi / WPBakery: not required, not integrated against
* Translation: both packages use the `somali-focus` text domain and
  are ready for a Somali translation (see each package's `languages/`
  folder for `wp i18n make-pot` instructions)
