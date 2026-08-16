=== SomaliFocus AI Article Writer ===
Contributors:      somalifocus
Tags:               ai, content generation, somalifocus, abilities, connectors
Requires at least:  6.9
Requires PHP:       7.4
Stable tag:         1.0.0
License:            GPLv2 or later
License URI:        https://www.gnu.org/licenses/gpl-2.0.html

Generates complete, publication-ready draft articles for SomaliFocus from a topic, using the WordPress AI Client and whichever AI Connector is already configured.

== Description ==

SomaliFocus AI Article Writer adds one thing: a "Generate Article" screen
that turns a topic into a complete, reviewable draft — title, full body
content (introduction, developed sections, conclusion — never just an
outline), an excerpt, and SEO metadata (SEO title, meta description,
focus keyword, related keywords, suggested categories/tags, image alt
text suggestions).

It does **not** publish anything automatically. Every result is shown on
a review screen where you can edit every field before an explicit
"Create Draft Post" click creates a normal WordPress draft — you still
open it in the usual post editor and click Publish yourself.

**This plugin requires:**

* The official **AI** plugin (WordPress.org: "AI features, experiments
  and capabilities for WordPress"), active.
* At least one **AI Connector** plugin (e.g. AI Provider for Anthropic,
  AI Provider for OpenAI, AI Provider for Google) installed, active, and
  configured with valid credentials under **Settings → Connectors**.

It does **not** implement its own API key storage or its own provider
integration — it reuses the AI Client and Connectors the "AI" plugin
already provides, exactly the way the AI plugin's own built-in features
(Title Generation, Content Resizing, etc.) do. If you already have a
working AI Connector configured for those features, this plugin uses the
same configuration with no extra setup.

**Language support:** English, Somali, and a deliberate mixed
Somali/English register (matching how bilingual Somali development
professionals actually write) — select the language before generating.

**Length control:** Short (~400–600 words), Standard (~800–1200),
Detailed (~1500–2200), or Comprehensive (~2500–3500) — the generated
body content is sized to the tier, not a fixed one-size length.

**Quality & error handling:** generation requests that come back empty,
malformed, cut off, or otherwise unusable return a specific, readable
error instead of a partial article being silently handed to you as if it
were complete. A lightweight automated check also flags any heading left
without real paragraph content underneath it, or a body that came back
noticeably shorter than requested, so you know to look closely before
publishing.

== Installation ==

1. Make sure the **AI** plugin and at least one **AI Connector** plugin
   (already configured under Settings → Connectors) are active first.
2. Upload the `somalifocus-ai-writer` folder to `/wp-content/plugins/`,
   or upload `somalifocus-ai-writer.zip` via Plugins → Add New → Upload Plugin.
3. Activate the plugin through the 'Plugins' menu.
4. Go to **Settings → AI** and make sure "SomaliFocus Article Generation"
   is enabled (it is a normal feature toggle in that framework, alongside
   Title Generation, Content Resizing, etc.).
5. Go to the new **AI Writer** menu item, enter a topic, choose a
   language and length, and click **Generate Article**.
6. Review and edit every field on the results screen, then click
   **Create Draft Post**. Open the resulting draft in the normal post
   editor to do a final review and publish when ready.

== Frequently Asked Questions ==

= Does this publish articles automatically? =

No. Generation never touches the database. Creating a draft requires an
explicit click from a logged-in user with `edit_posts` capability, and
the result is always a draft (`status: draft`), never published.

= Does this need its own API key? =

No. It uses whichever AI Connector (Anthropic, OpenAI, Google, etc.) is
already configured under Settings → Connectors, through the same AI
Client the rest of the "AI" plugin uses. There is no second credential
system.

= What happens if the AI provider is unavailable, rate-limited, or times out? =

You get a specific error message identifying which of those happened
(authentication failure, rate limit, provider unavailable, network
error, empty/invalid/incomplete response). Nothing is ever saved on a
failed or incomplete generation.

= Will the SEO fields show up automatically in Yoast/Rank Math/etc.? =

The plugin detects Yoast SEO, Rank Math, All in One SEO, and SEOPress
and attempts to set their meta description/SEO title/focus keyword
fields directly when you create the draft. Whether that value is
picked up depends on your SEO plugin's own REST support, which varies
by plugin and version — the generated values are always shown on the
review screen too, so you can paste them into that plugin's own panel
manually if they don't appear automatically.

== Changelog ==

= 1.0.0 =
* Initial release: sf/generate-article Ability, "AI Writer" admin screen,
  English/Somali/Mixed language support, four length tiers, SEO metadata
  generation, category/tag suggestions matched against existing terms,
  and specific error handling for authentication, rate limiting,
  provider unavailability, network errors, and incomplete/invalid
  responses.
