<?php
/**
 * Search form.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="sf-search-field"><?php esc_html_e( 'Search for:', 'somali-focus' ); ?></label>
	<input type="search" id="sf-search-field" class="search-form__field" placeholder="<?php esc_attr_e( 'Search…', 'somali-focus' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="search-form__submit"><?php sf_icon( 'research' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Search', 'somali-focus' ); ?></span></button>
</form>
