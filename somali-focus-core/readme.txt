=== Somali Focus Core ===
Contributors: somalifocus
Tags: custom-post-type, training, consulting, research
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Core business logic and data structures for the Somali Focus website.

== Description ==

Somali Focus Core owns every business-critical data structure for the
Somali Focus site so none of it is lost if the active theme is ever
changed:

* Custom Post Types: Courses, Advisory Projects, Research, Experts,
  Partners, Testimonials, Registrations, Service Requests
* Taxonomies: Course Category, Advisory Category, Research Category,
  Expertise, Sector
* A single Settings page (Somali Focus → Settings) for organization
  info, social links, homepage copy and impact statistics
* Course registration and general service request forms, stored
  privately (never exposed via the REST API) with admin email
  notifications
* A "Somali Focus" admin menu with a dashboard, CSV export for
  registrations/service requests, and status filtering
* Plain fallback front-end templates so Courses/Advisory/Research/
  Experts remain browsable even without the Somali Focus theme active
* Realistic, clearly-labeled demo content seeded on activation

Install the companion **Somali Focus** theme for the fully designed
front end. This plugin works standalone with any theme.

== Installation ==

1. Upload the `somali-focus-core` folder to `/wp-content/plugins/`, or
   upload `somali-focus-core.zip` via Plugins → Add New → Upload Plugin.
2. Activate the plugin through the 'Plugins' menu in WordPress. This
   automatically creates the site's core pages (About, Training,
   Advisory, Research, Services, Contact, Insights) with their
   templates assigned, sets up the blog location, and builds a Primary
   menu — safe to re-run anytime from Somali Focus → Dashboard.
3. Go to **Somali Focus → Settings** and fill in your organization's
   real contact information, social links and homepage copy.
4. Go to **Somali Focus → Training / Advisory / Research / Experts /
   Partners / Testimonials** to review or replace the demo content.
5. Install and activate the **Somali Focus** theme for the full design.

== Frequently Asked Questions ==

= Does this plugin require the Somali Focus theme? =

No. It ships plain fallback templates so content displays with any
active theme. The Somali Focus theme simply provides a fully designed
front end that reads this plugin's data.

= Will uninstalling the plugin delete my content? =

No. Uninstalling only removes the plugin's own settings option. All
Courses, Advisory Projects, Research, Experts, Partners, Testimonials
and submissions remain in the WordPress database.

= Where do registrations and service requests go? =

They're stored as private post types (not publicly queryable, not in
the REST API) under **Somali Focus → Registrations** and
**→ Service Requests**, with CSV export and a notification email sent
to the address configured in Settings (falls back to the site admin
email).

== Changelog ==

= 2.0.0 =
* **Breaking (pre-launch) URL change:** Course/Advisory/Research archive
  slugs changed from `training`/`advisory`/`research` to
  `courses`/`advisory-projects`/`publications`. Those original slugs
  were silently colliding with the Training/Advisory/Research landing
  Pages — a WordPress Page can never win that routing race against a
  post type archive using the same slug, so the landing pages were
  unreachable at their intended URL. If you're updating an
  already-active install, the plugin now detects the version change and
  flushes rewrite rules automatically; no manual "Save Permalinks" step
  needed.
* New: automatic site-structure setup. On activation (and once on
  upgrade), the plugin now creates the About/Training/Advisory/Research/
  Services/Contact/Insights pages with their matching templates, points
  Reading Settings at Insights as the blog page, and builds a Primary
  navigation menu — all only if that structure doesn't already exist.
  Re-run anytime from Somali Focus → Dashboard → "Set Up Site Structure".
* Partners no longer have an orphaned, undesigned single URL — same
  public/queryable shape as Testimonials now, since both are
  embedded-only card content.

= 1.5.0 =
* Added a ready-to-use `languages/somali-focus.pot` translation template.
* Minor internal hardening pass; no data or settings changes.

= 1.0.0 =
* Initial release.
