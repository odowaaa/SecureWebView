=== Somali Focus ===
Contributors: somalifocus
Tags: custom-menu, custom-logo, featured-images, threaded-comments, translation-ready, accessibility-ready, block-styles, wide-blocks, one-column
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
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

1. Install and activate **Somali Focus Core** first.
2. Upload the `somali-focus` folder to `/wp-content/themes/`, or upload
   `somali-focus-theme.zip` via Appearance → Themes → Add New → Upload
   Theme.
3. Activate the theme.
4. Appearance → Customize → Menus: create and assign a **Primary Menu**
   (Home, About, Training, Advisory, Research, Insights, Experts,
   Contact) and optionally a **Footer Menu** and **Legal Menu**.
5. The real Somali Focus logo is bundled and displays automatically —
   nothing to do here. Only visit Appearance → Customize → Site
   Identity if you want to override it with a different or
   higher-resolution logo file.
6. Create pages and assign the bundled page templates under
   Page Attributes → Template:
   - About Page → `/about/`
   - Training Page → `/training/`
   - Advisory Page → `/advisory/`
   - Research Page → `/research/`
   - Services Overview Page → `/services/`
   - Contact Page → `/contact/`
7. Settings → Reading: set the homepage to a static page and choose a
   page using the default template as the "Posts page" — or simply
   leave "Your homepage displays: Your latest posts" off and let the
   theme's `front-page.php` render automatically once a static front
   page exists (any static page works as the front page target).
8. Somali Focus → Settings (in the plugin): enter real organization
   info, social links, homepage hero/about copy and impact statistics.

== Frequently Asked Questions ==

= Why do I see "Somali Focus Core is required"? =

The theme intentionally keeps all business data out of itself. Install
and activate the Somali Focus Core plugin to remove the notice and
populate real content.

= Can I use a different page builder or block theme features? =

The theme is a classic (non-block) theme with full Gutenberg content
editing support. It does not depend on Elementor, Divi or WPBakery.

== Changelog ==

= 1.0.0 =
* Initial release.
