Somali Focus Core is translation-ready (text domain: somali-focus).

To generate a .pot file for translators, run WP-CLI from the plugin's root:

    wp i18n make-pot . languages/somali-focus.pot --domain=somali-focus

Place compiled .mo/.po files for each locale in this folder, e.g.:

    languages/somali-focus-so_SO.po
    languages/somali-focus-so_SO.mo

The plugin loads translations automatically via load_plugin_textdomain().
