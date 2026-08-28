<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="somca-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="somca-search-field"><?php esc_html_e( 'Search for:', 'somca' ); ?></label>
	<input type="search" id="somca-search-field" class="somca-search-field" placeholder="<?php esc_attr_e( 'Search hotspots, reports, alerts…', 'somca' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
	<button type="submit" class="somca-btn somca-btn-primary"><?php esc_html_e( 'Search', 'somca' ); ?></button>
</form>
