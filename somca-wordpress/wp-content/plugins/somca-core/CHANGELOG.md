# SomCA Core — Changelog

## 2.0.0
- **Subscribers**: new "Weekly Climate Briefing" signup, stored in a
  dedicated `wp_somca_subscribers` table. Public AJAX endpoint
  (`somca_subscribe`, nonce + honeypot protected), an admin **Subscribers**
  list under *SomCA Core*, and one-click CSV export.
- **Dashboard widget**: a "SomCA Climate Snapshot" widget on the main
  wp-admin Dashboard showing hotspot/report/active-alert/subscriber counts
  and the last weekly data-collection run.
- **Stats REST endpoint**: `GET /wp-json/somca/v1/stats` — the same
  snapshot numbers, for external dashboards/apps.
- **CSV export of climate reports**: any hotspot's full report history can
  now be downloaded as CSV via `admin-post.php?action=somca_export_reports&hotspot_id=<id>`
  (no login required — the data is already public).
- `[somca_chart]` shortcode gained a `hotspot="<id>"` attribute for
  per-location trend charts.
- Existing 1.x installs auto-create the new subscribers table on the first
  load after upgrading (no need to deactivate/reactivate).

## 1.0.0
- Initial release: Hotspot / Climate Report / Alert post types, Region /
  Hazard / Period taxonomies, automated weekly-through-bi-yearly climate
  data collection via Open-Meteo with anomaly-based auto-alerting, public
  REST API, map/chart/alert/report shortcodes, admin threshold settings,
  and sidebar widgets.
