=== Somali Focus ===
Contributors: somalifocus
Tags: custom-menu, custom-logo, featured-images, threaded-comments, translation-ready, accessibility-ready, block-styles, wide-blocks, one-column
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 3.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A premium, professional theme for training, advisory and research organizations.

== Description ==

Somali Focus is the presentation layer for the Somali Focus website:
typography, layout, colors, navigation, animations and templates. It
owns no business data — Courses, Advisory Projects, Research, Experts,
Partners, Testimonials and site settings all live in the companion
**Somali Focus Core** plugin, read through a small set of wrapper
functions in `inc/plugin-integration.php`. If the plugin is ever
deactivated, the theme keeps working and shows graceful placeholder
content instead of fatal errors.

Built with modern PHP, semantic HTML5, hand-written CSS (no framework)
and vanilla JavaScript. Gutenberg-compatible, translation-ready,
accessibility-conscious and compatible with Yoast SEO / Rank Math.

== Requirements ==

* WordPress 6.0+
* PHP 7.4+
* **Somali Focus Core** plugin, active (installs an admin notice if missing)

== Installation ==

1. Install and activate **Somali Focus Core** first. Its activation
   hook automatically creates the About/Training/Advisory/Research/
   Services/Contact/Insights pages with the right templates already
   assigned, configures Insights as the blog page, and builds a Primary
   navigation menu.
2. Upload the `somali-focus` folder to `/wp-content/themes/`, or upload
   `somali-focus-theme.zip` via Appearance → Themes → Add New → Upload
   Theme.
3. Activate the theme — the pages created in step 1 immediately start
   rendering through their assigned templates.
4. The real Somali Focus logo is bundled and displays automatically —
   nothing to do here. Only visit Appearance → Customize → Site
   Identity if you want to override it with a different or
   higher-resolution logo file.
5. Somali Focus → Settings (in the plugin): enter real organization
   info, social links, homepage hero/about copy and impact statistics.

If you installed the plugin before this automatic setup existed, or
deleted a page/menu while testing, run it (or re-run it — it's safe
either way, it only creates what's missing) from
**Somali Focus → Dashboard → "Set Up Site Structure"**.

== Frequently Asked Questions ==

= Why do I see "Somali Focus Core is required"? =

The theme intentionally keeps all business data out of itself. Install
and activate the Somali Focus Core plugin to remove the notice and
populate real content.

= Can I use a different page builder or block theme features? =

The theme is a classic (non-block) theme with full Gutenberg content
editing support. It does not depend on Elementor, Divi or WPBakery.

== Changelog ==

= 3.0.0 =
* New: "Why Somali Focus" homepage section (four differentiator cards)
  and a reorganized homepage flow — hero, welcome video, who we are,
  three core services, stats, training/advisory/research highlights,
  why Somali Focus, approach, sectors, insights, experts, partners,
  testimonials, final call to action.
* New: the About page now includes "What We Do" (the three core
  services), "Sectors We Serve" and a "Team" section, built from the
  same reusable `template-parts/service-card.php`, `sectors.php` and the
  newly-extracted `experts-section.php` / `partners-section.php` used on
  the homepage — no duplicated markup between the two pages.
* New: 7 optional, off-by-default ad placements (header top, after hero,
  before/in/after content, sidebar, footer) via the companion plugin's
  ad-slot settings, each clearly labeled and rendered only once content
  is configured.
* New: 404 and empty-search pages now surface a "popular pages"
  shortcut list (About, Training, Advisory, Research, Insights, Contact)
  instead of leaving visitors at a dead end; search results also show a
  result count.
* Improved: `decoding="async"` added alongside `loading="lazy"` on every
  card and single-page thumbnail for smoother image rendering.
* Improved: thin/empty archive and search pages emit `noindex,follow` so
  they don't compete for search ranking while everything else stays
  indexable.
* Improved: Person schema for Expert profiles and sitewide WebSite
  schema, skipped automatically when an SEO plugin already provides
  structured data.
* Requires **Somali Focus Core 3.0.0** or later.

= 2.1.0 =
* New: homepage welcome-video section, right under the hero. Renders
  only once a video URL is set in Somali Focus → Settings — a
  click-to-play facade (poster image + play button) so no video-platform
  script or iframe loads until the visitor actually presses play.
  Supports YouTube, Vimeo, and direct video files.
* Requires **Somali Focus Core 2.1.0** or later.

= 2.0.0 =
* Fonts are now self-hosted (Plus Jakarta Sans + Merriweather,
  `assets/fonts/`) — no external Google Fonts request.
* Added `taxonomy.php` — Course/Advisory/Research categories, Expertise
  and Sector archives now render each item with its correct card
  component instead of falling back to generic blog-post styling.
* Fixed: every card thumbnail (course, advisory, research, expert,
  blog, testimonial, and single-page hero images) now has an explicit
  alt-text fallback to the post title, instead of depending entirely on
  the Media Library's stored alt text.
* Fixed: the impact-statistics grid now collapses to one column on the
  smallest phone screens, matching every other grid on the site.
* Fixed: the footer's "Terms & Conditions" link only renders once a
  page actually exists at that slug, instead of always linking to a
  guaranteed 404 on a fresh install.
* Training/Advisory/Research landing pages gained a "View All
  Courses/Projects/Publications" link to their respective archives
  (now reachable, see the Core plugin's 2.0.0 changelog for the slug fix).
* Requires **Somali Focus Core 2.0.0** or later.

= 1.5.0 =
* Real Somali Focus logo now bundled as the default brand asset (header,
  footer and favicon), with Customizer Custom Logo/Site Icon as an override.
* Fixed keyboard/touch access to desktop dropdown submenus — previously
  reachable by mouse hover only.
* Added JSON-LD structured data: sitewide Organization, BreadcrumbList,
  Course schema on training pages, and Article schema on Insights posts
  (skipped automatically when Yoast/Rank Math/AIOSEO is active, except
  Course schema which those plugins don't cover).
* Added a "Related Courses" section to single course pages and
  previous/next post navigation to single Insights posts.
* Added a ready-to-use `languages/somali-focus.pot` translation template.

= 1.0.0 =
* Initial release.
