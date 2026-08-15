Somali Focus (theme) is translation-ready (text domain: somali-focus).

somali-focus.pot in this folder is a ready-to-use translation template
covering every translatable string in the theme. To translate (e.g.
into Somali):

1. Open somali-focus.pot in Poedit (https://poedit.net) or the Loco
   Translate WordPress plugin.
2. Save/export as somali-focus-so_SO.po — Loco Translate and Poedit
   both compile the matching .mo file automatically.
3. Place both files in this languages/ folder:

    languages/somali-focus-so_SO.po
    languages/somali-focus-so_SO.mo

4. Set your site language under Settings → General → Site Language
   (or install the locale first under Settings → General if it isn't
   listed yet).

The theme loads translations automatically via load_theme_textdomain()
— no code changes needed.

If you add or edit strings in the PHP source later and have WP-CLI
available, regenerate the template with:

    wp i18n make-pot . languages/somali-focus.pot --domain=somali-focus
