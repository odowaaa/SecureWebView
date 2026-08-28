# SomCA — Somali Climate Action (WordPress Core + Theme)

Custom WordPress **theme** (`somca`) and **core plugin** (`somca-core`) for
[somca.org](https://somca.org) — a platform that monitors and advocates on
climate change hotspots across Somalia and worldwide: an interactive hotspot
map, automated weekly/monthly/seasonal/yearly/bi-yearly climate data
collection, and early-warning alerts.

## What's included

```
wp-content/
  plugins/somca-core/     # Data model + automation (works with any theme)
  themes/somca/           # Front-end design, built for the plugin above
```

### `somca-core` plugin
- **Custom post types**: Hotspots, Climate Reports, Alerts
- **Taxonomies**: Region (Somaliland, Puntland, Banaadir, Jubaland, Galmudug,
  Hirshabelle, South West State, Global), Hazard Type (drought, flood,
  cyclone, heatwave, sea-level rise, desertification, locust, deforestation),
  Report Period (weekly, monthly, seasonal, yearly, bi-yearly)
- **Automated data collection**: a WP-Cron job runs on each cadence, pulls
  live + 1991-2020 climatology data per Hotspot from the free, key-less
  [Open-Meteo](https://open-meteo.com) API, computes temperature/rainfall
  anomalies, and publishes a Climate Report. If a threshold is crossed
  (configurable in *SomCA Core → Settings*) it drafts an Alert for review.
- **REST API** (public, read-only): `/wp-json/somca/v1/hotspots`,
  `/wp-json/somca/v1/reports`, `/wp-json/somca/v1/alerts` — used by the map
  and charts, and available for any external dashboard/app.
- **Shortcodes**: `[somca_map]`, `[somca_alerts]`, `[somca_chart]`,
  `[somca_latest_reports]`, `[somca_hotspots_grid]` — work in any theme.
- **Widgets**: Active Alerts, Latest Reports.

### `somca` theme
Built specifically around the plugin's data: hero, live alert bar, Leaflet
hotspot map, Chart.js trend charts, period tabs (weekly → bi-yearly), and
dedicated templates for each post type. Brand colors and typography are
lifted from the SomCA logo (black `#1a1a1a`, green `#4CAF50`, red `#D32F2F`),
defined as CSS variables at the top of `style.css` — change them there to
re-theme the whole site.

## Installation

1. Upload `wp-content/plugins/somca-core` and `wp-content/themes/somca` to
   your WordPress install (via SFTP, or zip each folder and upload through
   **Plugins → Add New → Upload** / **Appearance → Themes → Add New →
   Upload**).
2. Activate **SomCA Core** under *Plugins*.
3. Activate **SomCA** under *Appearance → Themes*.
4. Go to **Settings → Permalinks** and choose "Post name", then save (this
   registers the Hotspot/Report/Alert URL structure).
5. Set a site logo at **Appearance → Customize → Site Identity** (optional —
   the theme falls back to the bundled SomCA logo automatically).
6. Create a menu at **Appearance → Menus** and assign it to "Primary Menu"
   (optional — a sensible fallback menu is shown otherwise).

## Getting climate data flowing

1. Go to **Hotspots → Add New**. Give it a title, description, and set
   **Latitude/Longitude** plus a **Risk Level** in the Hotspot Details box.
   Assign a Region and Hazard Type.
2. Publish it. The next scheduled WP-Cron run (or **SomCA Core → Run Weekly
   Fetch Now**) will pull real climate data for that location and publish a
   Climate Report automatically.
3. Repeat for every location you want to monitor.
4. Tune alert sensitivity at **SomCA Core** in the admin menu.

### Cron reliability

WordPress's default cron only fires on page visits, which is unreliable on
a low-traffic new site. For production, disable that and use a real system
cron hitting `wp-cron.php` every 15 minutes:

```php
// wp-config.php
define( 'DISABLE_WP_CRON', true );
```

```
*/15 * * * * curl -s https://somca.org/wp-cron.php?doing_wp_cron > /dev/null 2>&1
```

Most hosts (SiteGround, Kinsta, WP Engine, cPanel) offer this as a one-click
"Cron Jobs" setting.

### Data source

Uses [Open-Meteo](https://open-meteo.com)'s ERA5 reanalysis archive and
1991–2020 climate normals — no API key required, generous free-tier limits.
To switch providers (e.g. NASA POWER, NOAA), hook the
`somca_climate_data_provider` / `somca_fetch_climate_data_custom` filters in
`somca-core/includes/class-somca-data-fetcher.php`.

## Deploying to SomCA.org

1. Point the domain's DNS (A record, or CNAME if using a host that provides
   one) to your WordPress hosting provider.
2. Install WordPress at the hosting root.
3. Follow **Installation** above.
4. Under **Settings → General**, confirm the WordPress Address and Site
   Address both use `https://somca.org`, and enable SSL (Let's Encrypt via
   your host, or Cloudflare) so the browser padlock shows and REST/API
   calls aren't blocked as mixed content.
5. Recommended free plugins to pair with this build: **Yoast SEO / Rank
   Math** (metadata), **WP Mail SMTP** (reliable email for future
   subscriber forms), **UpdraftPlus** (backups).

## Content model reference

| Post type | Purpose | Key fields |
|---|---|---|
| Hotspot | A monitored location | Latitude, Longitude, Risk Level, Status, Population Affected |
| Climate Report | A periodic data snapshot | Period Start/End, Avg. Temp, Temp Anomaly, Rainfall, Rainfall Anomaly, Data Source |
| Alert | An active warning | Severity, Issued Date, Expires Date, Recommended Actions |

## Customization

- **Brand colors / fonts**: `wp-content/themes/somca/style.css`, `:root` block.
- **Alert thresholds**: *SomCA Core* admin page.
- **Homepage layout**: `wp-content/themes/somca/front-page.php`.
- **Map/marker colors**: `RISK_COLORS` in `assets/js/map.js` (theme) and
  `somca-map.js` (plugin), plus the matching CSS badges in both stylesheets.
